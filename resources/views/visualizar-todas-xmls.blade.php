@extends('master')

@section('content')

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="dashboard">Central de Notas</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
            </div>
        </form>
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Configurações</a></li>
                    <li><a class="dropdown-item" href="#!">Atividades</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li>
                        @if(auth()->check())
                        <form action="{{ route('login.destroy') }}" method="post">
                            @csrf
                            <button class="dropdown-item" type="submit">Logout</button>
                        </form>
                        @else
                        <a class="dropdown-item" href="#!">Login</a>
                        @endif
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Simplificado</div>
                        <a class="nav-link" href="dashboard">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Import XML
                        </a>
                        <a class="nav-link" href="{{ route('base-dados') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Base de dados
                        </a>
                        <a class="nav-link" href="{{ route('list-users') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Usuário
                        </a>
                        <div class="sb-sidenav-menu-heading">Avançado</div>
                        <a class="nav-link collapsed" href="" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Search Xped

                        </a>
                        <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href=""></a>
                            </nav>
                        </div>
                        <a class="nav-link collapsed" href="" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                            Processamento XML

                        </a>
                        <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                    Portal Sefaz
                                </a>
                            </nav>
                        </div>
                        <div class="sb-sidenav-menu-heading">Addons</div>
                        <a class="nav-link" href="charts.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            @if(auth()->check())
                            <form action="{{ route('login.destroy') }}" method="POST">
                                @csrf
                                <button class="dropdown-item" type="submit">Logout</button>
                            </form>
                            @else
                        </a>
                        <a>

                            <a class="dropdown-item" href="{{ route('login') }}">Login</a>
                            @endif
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logado:</div>
                    @if(auth()->check())
                    {{ auth()->user()->name }}
                    @else
                    Guest
                    @endif
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid ">
                    <h1 class="mt-4">Visualização de XMLs</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">XML - dashboard</li>



                    </ol>

                    <div class="container- fluid">

                        <div style="margin-top: 100px;" class="container">
                            <div class="d-grid gap-2 col-6 mx-auto">
                                <!-- Adicione este botão -->
                                <button class="btn btn-primary" type="button" id="downloadexcel">ZMM235</button>
                            </div>
                        </div>
                        <div style="margin-top: 100px;" class="container">
                            <div class="d-grid gap-2 col-6 mx-auto">
                                <!-- Adicione este botão -->
                                <button class="btn btn-primary" type="button" id="downloadexcel2">Dados XML</button>
                            </div>
                        </div>
                        <table id="myTable" class="table" style="margin-top: 50px;">
                            <thead>
                                <tr>
                                    <th scope="col" class="export">Pedido</th>
                                    <th scope="col">cProd</th>
                                    <th scope="col">For</th>
                                    <th scope="col" class="export">SAP</th>
                                    <th scope="col">Material</th>
                                    <th scope="col">EAN</th>
                                    <th scope="col">CX</th>
                                    <th scope="col">CXSAP</th>
                                    <th scope="col" class="export">Faturado</th>
                                    <th scope="col">Despesas</th>
                                    <th scope="col" class="export">Custo</th>
                                    <th scope="col">Valor</th>
                                    <th scope="col" class="export">Fator</th>
                                    <th scope="col" class="export">Desconto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $groupedItems = []; // Array para armazenar os itens agrupados

                                foreach ($xmlFiles as $xmlFile) {
                                $xmlContent = file_get_contents(storage_path('app/public/xml_files/' . $xmlFile->file_name));

                                // Verifica se a string está em UTF-8, caso contrário, tenta converter
                                if (!mb_check_encoding($xmlContent, 'UTF-8')) {
                                $xmlContent = mb_convert_encoding($xmlContent, 'UTF-8', 'ISO-8859-1, Windows-1252');
                                }

                                $xmlObject = simplexml_load_string($xmlContent);

                                if ($xmlObject === false) {
                                // Se o XML não puder ser carregado
                                echo "Erro ao carregar o XML.";
                                continue;
                                }

                                foreach ($xmlObject->NFe->infNFe->det as $item) {
                                $xPed = (string) $item->prod->xPed;
                                $cProd = (string) $item->prod->cProd;
                                $key = $xPed . '-' . $cProd;

                                if (!isset($groupedItems[$key])) {
                                $groupedItems[$key] = [
                                'pedido' => $xPed,
                                'cProd' => $cProd,
                                'descricao' => (string) $item->prod->xProd,
                                'EAN' => ltrim((string) $item->prod->cEANTrib, '0'),
                                'quantidade' => (float) $item->prod->qCom,
                                'valor' => (float) $item->prod->vProd,
                                'despesas' => (float) $item->prod->vOutro,
                                'desconto' => (float) $item->prod->vDesc,
                                'xmlObject' => $xmlObject, // Armazena o objeto XML para futuras referências
                                ];
                                } else {
                                $groupedItems[$key]['quantidade'] += (float) $item->prod->qCom;
                                $groupedItems[$key]['valor'] += (float) $item->prod->vProd;
                                $groupedItems[$key]['despesas'] += (float) $item->prod->vOutro;
                                $groupedItems[$key]['desconto'] += (float) $item->prod->vDesc;
                                }
                                }
                                }
                                @endphp

                                @foreach($groupedItems as $item)
                                <tr>
                                    <!-- Pedido -->
                                    <td class="export">
                                        @php
                                        $xPed = $item['pedido'];
                                        $xmlObject = $item['xmlObject'];

                                        if (substr($xPed, 0, 3) === '450') {
                                        $valorFinal = $xPed;
                                        } else {
                                        $infCpl = (string) $xmlObject->NFe->infNFe->infAdic->infCpl;
                                        preg_match('/450\d*/', $infCpl, $matches);
                                        $valorFinal = !empty($matches) ? $matches[0] : 'Não encontrado';
                                        }
                                        @endphp

                                        <div class="input-group input-group-sm mb-3">
                                            <input type="text" name="xPed[]" value="{{ $valorFinal }}" class="form-control" />
                                        </div>
                                    </td>

                                    <!-- cProd -->
                                    <td>{{ ltrim($item['cProd'], '0') }}</td>

                                    <!-- Fornecedor -->
                                    
                                    <td>
                                        @php
                                        $cnpjFornecedor = (string) $xmlObject->NFe->infNFe->emit->CNPJ;
                                        $fornecedor = \App\Models\Product::where('cnpj_fornecedor', $cnpjFornecedor)->first();
                                        $chaveAcesso = (string) $xmlObject->NFe->infNFe['Id']; // Acesso à chave de acesso no atributo Id
                                        @endphp

                                        @if ($fornecedor)
                                        <span
                                            data-bs-toggle="popover"
                                            data-bs-content="Chave de Acesso - {{ $chaveAcesso }}"
                                            class="text-primary"
                                            style="cursor: pointer;">
                                            {{ $fornecedor->cod_fornecedor }}
                                        </span>
                                        @else
                                        <span class="text-danger">CNPJ não encontrado</span>
                                        @endif
                                    </td>


                                    <!-- SAP -->
                                    <td class="export">
                                        @php
                                        $produto = \App\Models\Product::whereRaw('LOWER(TRIM(codigo_barras)) = ?', [trim(strtolower($item['EAN']))])->first();

                                        // Caso não encontre pelo EAN, tenta buscar pelo xProd
                                        if (!$produto) {
                                        $produto = \App\Models\Product::whereRaw('LOWER(TRIM(codigo_barras)) = ?', [trim(strtolower($item['descricao']))])->first();
                                        }
                                        @endphp

                                        {{ $produto ? $produto->sap : 'Material não encontrado' }}
                                    </td>

                                    <!-- Material -->
                                    <td>{{ $item['descricao'] }}</td>

                                    <!-- EAN -->
                                    <td>{{ $item['EAN'] }}</td>

                                    <!-- Quantidade -->
                                    <td>{{ $item['quantidade'] }}</td>

                                    <!-- CXSAP -->
                                    <td>
                                        @if ($produto)
                                        @php
                                        // A caixaria vem do banco de dados associado ao produto
                                        $caixaria = (float) $produto->caixaria;
                                        @endphp
                                        <input type="text"
                                            class="form-control caixaria-field"
                                            data-id="{{ $produto->id }}"
                                            value="{{$caixaria }}"
                                            placeholder="Insira o valor da caixaria"
                                            style="max-width: 150px;" />
                                        @else
                                        <p>Produto não encontrado.</p>
                                        @endif
                                    </td>

                                    <!-- Faturado -->
                                    <td>
                                        @php
                                        $caixaria = $produto ? (float) $produto->caixaria : 0;
                                        $quantidadeX = ($item['quantidade'] > 0 && $caixaria > 0) ? $item['quantidade'] * $caixaria : $item['quantidade'];
                                        @endphp
                                        {{ $quantidadeX }}
                                    </td>

                                    <!-- Despesas -->
                                    <td>{{ $item['despesas'] }}</td>

                                    <!-- Custo -->
                                    <td>
                                        @php
                                        // Soma do valor com as despesas
                                        $valorComDespesas = $item['valor'] + $item['despesas'];

                                        // Cálculo do custo: (valor + despesas) dividido pela quantidade faturada
                                        $custo = ($quantidadeX > 0) ? $valorComDespesas / $quantidadeX : 0;
                                        @endphp
                                        {{ number_format($custo, 8, '.', '') }} <!-- Exibe com 5 casas decimais -->
                                    </td>

                                    <!-- Valor -->
                                    <td>{{ number_format($item['valor'], 2, '.', '') }}</td>

                                    <!-- Fator -->
                                    <td>1000</td>


                                    <!-- Desconto -->
                                    <td class="export">{{ $item['desconto'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>


                </div>
            </main>

            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Arthur 2022</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

</body>


@endsection