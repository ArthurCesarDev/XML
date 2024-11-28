<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Pedido;
use App\Models\XmlFile;
use App\Models\User;

class DashboardController extends Controller
{

    public function index()
    {
        $files = XmlFile::where('user_id', auth()->id())->paginate(10);

        return view('dashboard', ['files' => $files]);
    }

    public function importXML(Request $request)
    {
        try {
            $request->validate([
                'xmlFiles' => 'required',
                'xmlFiles.*' => 'file|max:1048576', // Máximo 1 MB por arquivo
            ]);

            foreach ($request->file('xmlFiles') as $xmlFile) {
                if ($xmlFile->isValid()) {
                    // Crie um nome único para o arquivo
                    $uniqueName = auth()->id() . '_' . time() . '_' . $xmlFile->getClientOriginalName();
                    $xmlPath = $xmlFile->storeAs('public/xml_files', $uniqueName);

                    // Salve o arquivo no banco de dados
                    XmlFile::create([
                        'file_name' => $uniqueName,
                        'user_id' => auth()->id(),
                    ]);
                }
            }

            return redirect()->route('dashboard')->with('success', 'Arquivos importados com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Erro: ' . $e->getMessage());
        }
    }
    public function visualizarXML($arquivo)
    {
        // Verifica se o arquivo pertence ao usuário autenticado
        $xmlFile = XmlFile::where('file_name', $arquivo)
            ->where('user_id', auth()->id())
            ->first();

        if (!$xmlFile) {
            return redirect()->route('dashboard')->with('error', 'Você não tem permissão para acessar este arquivo.');
        }

        $xmlPath = storage_path('app/public/xml_files/' . $arquivo);

        if (file_exists($xmlPath)) {
            $xmlContent = file_get_contents($xmlPath);
            return view('visualizar-xml', ['xmlContent' => $xmlContent]);
        } else {
            return redirect()->route('dashboard')->with('error', 'O arquivo XML não foi encontrado.');
        }
    }

    public function visualizarTodasXmls()
    {
        // Obtém todos os arquivos XML do usuário autenticado
        $xmlFiles = XmlFile::where('user_id', auth()->id())->get();

        // Obtém todos os produtos (ou faz a consulta que você precisa para os produtos)
        $produtos = Product::all(); // Ajuste conforme necessário, você pode filtrar ou paginar se for o caso

        return view('visualizar-todas-xmls', [
            'xmlFiles' => $xmlFiles,
            'produtos' => $produtos
        ]);
    }
    public function apagarXML($arquivo)
    {
        try {
            $xmlFile = XmlFile::where('file_name', $arquivo)
                ->where('user_id', auth()->id())
                ->first();

            if (!$xmlFile) {
                return redirect()->route('dashboard')->with('error', 'Você não tem permissão para apagar este arquivo.');
            }

            // Utilize o nome original para verificar no sistema de arquivos
            $filePath = 'public/xml_files/' . $xmlFile->file_name; // Aqui você deve usar o nome único

            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
                $xmlFile->delete(); // Remove a entrada do banco de dados
                return redirect()->route('dashboard')->with('success', 'XML apagado com sucesso!');
            } else {
                return redirect()->route('dashboard')->with('error', 'O arquivo XML não existe.');
            }
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Erro ao apagar o arquivo XML: ' . $e->getMessage());
        }
    }

    public function apagarTudo()
    {
        try {
            // Obtenha todos os arquivos do usuário autenticado
            $files = XmlFile::where('user_id', auth()->id())->get();

            // Remova os arquivos físicos do storage
            foreach ($files as $file) {
                Storage::delete('public/xml_files/' . $file->file_name);
            }

            // Remove todos os registros da tabela XmlFile do usuário autenticado
            XmlFile::where('user_id', auth()->id())->delete();

            return redirect()->route('dashboard')->with('success', 'Todos os seus arquivos XML foram apagados com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Erro ao apagar os arquivos: ' . $e->getMessage());
        }
    }

    public function buscarSapPorEan($ean)
    {
        // Limpa espaços extras
        $eanNormalizado = trim($ean);

        // Busca flexível no banco de dados
        $produto = \App\Models\Product::where('codigo_barras', 'LIKE', '%' . $eanNormalizado . '%')->first();

        if ($produto) {
            return view('detalhes-produto', ['sap' => $produto->sap, 'material' => $produto->material]);
        } else {
            return redirect()->route('dashboard')->with('error', 'Produto não encontrado.');
        }
    }
    public function baseDados(Request $request)
    {
        $query = $request->input('search');
        $page = $request->input('page', 1); // Captura o número da página ou define como 1

        // Configura a consulta com paginação
        $produtos = \App\Models\Product::when($query, function ($queryBuilder) use ($query) {
            return $queryBuilder->where('descricao', 'LIKE', "%{$query}%")
                ->orWhere('codigo_barras', 'LIKE', "%{$query}%")
                ->orWhere('sap', 'LIKE', "%{$query}%");
        })
            ->paginate(10, ['*'], 'page', $page); // Especifica a página correta para a paginação

        return view('basedados', compact('produtos'));
    }
    public function store(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'codigo_barras' => 'required|string|max:255',
            'descricao' => 'required|string|max:255',
            'sap' => 'required|string|max:255',
            'caixaria' => 'required|string|max:255',
            'cod_fornecedor' => 'required|string|max:255',
            'cnpj_fornecedor' => 'required|string|max:255',
        ]);

        // Criação do produto
        \App\Models\Product::create([
            'codigo_barras' => $request->codigo_barras,
            'descricao' => $request->descricao,
            'sap' => $request->sap,
            'caixaria' => $request->caixaria,
            'cod_fornecedor' => $request->cod_fornecedor,
            'cnpj_fornecedor' => $request->cnpj_fornecedor,
        ]);

        // Redirecionamento após salvar o produto
        return redirect()->route('base-dados')->with('success', 'Produto adicionado com sucesso!');
    }
    public function destroy($id)
    {
        $produto = \App\Models\Product::findOrFail($id); // Encontra o produto pelo ID
        $produto->delete(); // Exclui o produto

        return redirect()->route('base-dados')->with('success', 'Produto excluído com sucesso!');
    }
    public function update(Request $request, $id)
    {
        // Validação dos dados do formulário
        $validatedData = $request->validate([
            'descricao' => 'required|string|max:255',
            'codigo_barras' => 'required|string|max:255',
            'sap' => 'required|string|max:255',
            'caixaria' => 'required|numeric',
            'cod_fornecedor' => 'required|string|max:255',
            'cnpj_fornecedor' => 'required|string|max:255',
        ]);

        // Encontre o produto pelo ID e atualize
        $produto = \App\Models\Product::findOrFail($id);
        $produto->update($validatedData);

        // Captura a página atual para redirecionar
        $page = $request->input('page', 1);

        // Redireciona de volta para a página correta
        return redirect()->route('base-dados', ['page' => $page])->with('success', 'Produto atualizado com sucesso!');
    }



    public function listUsers(Request $request)
    {
        $query = \App\Models\User::query();

        if ($request->has('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%")
                ->orWhere('email', 'LIKE', "%{$request->search}%");
        }

        $users = $query->paginate(10);
        return view('list-users', compact('users'));
    }
    public function updateUser(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        $user = \App\Models\User::findOrFail($id);
        $user->update($validatedData);

        return redirect()->route('list-users')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function storeUser(Request $request)
    {
        // Validação dos dados
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:3|confirmed',
        ]);

        // Cria o novo usuário e salva a senha criptografada
        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        return redirect()->route('list-users')->with('success', 'Usuário adicionado com sucesso!');
    }

    public function savePedidos(Request $request)
    {
        $pedidos = $request->input('pedidos');

        if ($pedidos && is_array($pedidos)) {
            foreach ($pedidos as $pedido) {
                if (!empty($pedido)) {
                    Pedido::create([
                        'pedido' => $pedido,
                        'chave_acesso' => '', // Ajustar conforme sua lógica
                        'user_id' => auth()->id(), // Associando ao usuário autenticado
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Pedidos salvos com sucesso!');
    }

    public function saveSinglePedido(Request $request)
    {
        // Validação do pedido único
        $request->validate([
            'pedido_unico' => 'required|string|max:255',
        ]);

        // Salvando o pedido único
        Pedido::create([
            'pedido' => $request->input('pedido_unico'),
            'chave_acesso' => '', // Ajustar conforme sua lógica
            'user_id' => auth()->id(), // Associando ao usuário autenticado
        ]);

        return redirect()->back()->with('success', 'Pedido único salvo com sucesso!');
    }

    public function searchXped()
    {
        // Filtra os pedidos para pegar apenas os do usuário autenticado
        $pedidos = Pedido::where('user_id', auth()->id())->get(); // Apenas os pedidos do usuário autenticado
        return view('search-xped', compact('pedidos'));
    }



    public function editPedido($id)
    {
        $pedido = Pedido::findOrFail($id); // Busca o pedido pelo ID
        return view('pedidos.edit', compact('pedido')); // Retorna a view de edição
    }

    // Método para atualizar um pedido
    public function atualizarPedido(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id); // Busca o pedido pelo ID
        $pedido->pedido = $request->input('pedido'); // Atualiza o campo 'pedido'
        $pedido->fornecedor = $request->input('fornecedor'); // Atualiza o campo 'fornecedor'
        // Adicione outras atualizações conforme necessário
        $pedido->save(); // Salva as alterações

        return redirect()->back()->with('success', 'Pedido atualizado com sucesso!');
    }


    public function deletarPedido($id)
    {
        $pedido = Pedido::findOrFail($id); // Busca o pedido pelo ID
        $pedido->delete(); // Deleta o pedido

        return redirect()->back()->with('success', 'Pedido excluído com sucesso!');
    }
    public function buscarChaveXml($pedido)
    {
        $userId = auth()->id(); // Obtém o ID do usuário autenticado

        $chavesAcesso = [];
        $xmlFiles = \App\Models\XmlFile::where('user_id', $userId)->get();

        foreach ($xmlFiles as $xmlFile) {
            $filePath = storage_path('app/public/xml_files/' . $xmlFile->file_name);

            if (file_exists($filePath)) {
                // Carrega o conteúdo do XML
                $xmlContent = file_get_contents($filePath);

                // Tenta detectar e corrigir a codificação
                $xmlContent = mb_convert_encoding($xmlContent, 'UTF-8', 'UTF-8');

                // Tenta carregar o XML
                try {
                    $xmlObject = simplexml_load_string($xmlContent);

                    // Verifica se houve erro ao carregar o XML
                    if ($xmlObject === false) {
                        throw new \Exception('Erro ao carregar o XML.');
                    }

                    $xmlObject->registerXPathNamespace('n', 'http://www.portalfiscal.inf.br/nfe');

                    $numeroPedidoXML = $xmlObject->xpath('//n:prod/n:xPed');
                    $chaveAtual = $xmlObject->xpath('//n:protNFe/n:infProt/n:chNFe');

                    if (!empty($numeroPedidoXML) && !empty($chaveAtual)) {
                        $numeroPedidoXML = (string) $numeroPedidoXML[0];
                        $chaveAtual = (string) $chaveAtual[0];

                        if ($numeroPedidoXML == $pedido) {
                            $chavesAcesso[] = $chaveAtual;
                        }
                    }
                } catch (\Exception $e) {
                    // Registra o erro ou trate conforme necessário
                    Log::error('Erro ao processar XML: ' . $e->getMessage());
                    continue; // Ignora este arquivo e continua com o próximo
                }
            } else {
                Log::warning("Arquivo XML não encontrado: " . $filePath);
            }
        }

        return response()->json([
            'chaves' => !empty($chavesAcesso) ? $chavesAcesso : ['Não encontrada'],
        ]);
    }
    public function atualizarProdutoCaixaria(Request $request, $id)
    {
        try {
            // Valida o valor da caixaria
            $request->validate([
                'caixaria' => 'required|numeric',
            ]);

            // Encontra o produto pelo ID
            $produto = Product::findOrFail($id);

            // Atualiza a caixaria
            $produto->caixaria = $request->input('caixaria');
            $produto->save();

            // Retorna o valor atualizado da caixaria para a resposta
            return response()->json([
                'success' => true,
                'caixaria' => $produto->caixaria, // Envia o valor atualizado
                'message' => 'Caixaria atualizada com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro ao tentar atualizar a caixaria.'
            ], 500);
        }
    }
}
