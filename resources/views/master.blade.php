<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logística</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://71a2-2804-138-10af-8018-a3df-c912-a81b-9ac8.ngrok-free.app/css/styles.css">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.css" rel="stylesheet" />
</head>

<body>

    <div>
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Incluindo apenas uma vez -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Table2Excel/1.1.1/table2excel.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/table2excel.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/fullcalendar.min.js"></script>
    <script src="https://71a2-2804-138-10af-8018-a3df-c912-a81b-9ac8.ngrok-free.app/js/scripts.js"></script>
    <script src="https://71a2-2804-138-10af-8018-a3df-c912-a81b-9ac8.ngrok-free.app/js/leitura_codigo_barras.js"></script>
    <script src="https://71a2-2804-138-10af-8018-a3df-c912-a81b-9ac8.ngrok-free.app/js/table2excel.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/table-to-excel@1.0.0"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

    <script>
    document.getElementById('downloadexcel2').addEventListener('click', function() {
        // Gera um número aleatório para o nome do arquivo
        const numeroAleatorio = Math.floor(Math.random() * 1000000); // Número aleatório entre 0 e 999999
        const nomeArquivo = `dadosXML${numeroAleatorio}`; // Nome do arquivo sem extensão (extensão será adicionada automaticamente)

        // Cria uma nova instância de Table2Excel
        var table2excel = new Table2Excel();

        // Configura manualmente o nome do arquivo antes de exportar
        table2excel.export(document.querySelectorAll("table"), nomeArquivo);
    });
</script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.buscar-xml').forEach(function(button) {
                button.addEventListener('click', function() {
                    const pedido = this.getAttribute('data-pedido');
                    const pedidoId = this.getAttribute('data-id');
                    const chaveTd = this.parentNode.previousElementSibling; // A célula onde a chave será exibida

                    // Exibe um indicador de carregamento enquanto a chave está sendo buscada
                    chaveTd.innerHTML = 'Buscando...';

                    // Faz a requisição AJAX para buscar a chave do XML
                    fetch(`/buscar-chave-xml/${pedido}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Erro na requisição: ' + response.statusText);
                            }
                            return response.json(); // Muda para .json() para processar a resposta como JSON
                        })
                        .then(data => {
                            console.log(data); // Verifique o conteúdo do JSON retornado

                            // Verifica se há chaves retornadas
                            if (data.chaves && data.chaves.length > 0) {
                                chaveTd.innerHTML = data.chaves.join('<br>'); // Exibe as chaves na célula
                            } else {
                                chaveTd.innerHTML = 'Nenhuma chave encontrada';
                            }
                        })
                        .catch(error => {
                            console.error('Erro ao buscar a chave:', error);
                            chaveTd.innerHTML = 'Erro ao buscar a chave';
                        });
                });
            });
        });
    </script>
   <script>
$(document).on('blur', '.caixaria-field', function() {
    var newValue = $(this).val(); // Novo valor da caixaria
    var produtoId = $(this).data('id'); // ID do produto

    if (isNaN(newValue) || newValue === '') {
        return; // Caso o valor não seja válido, não faz nada
    }

    $.ajax({
        url: '/produto/' + produtoId + '/atualizar-caixaria',
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'), // CSRF Token
            caixaria: newValue
        },
        success: function(response) {
            if (response.success) {
                // Atualiza o valor da caixaria na tabela com o novo valor
                $('span[data-id="' + produtoId + '"]').text(response.caixaria); // Atualiza o valor na tabela
            }
        },
        error: function() {
            // Não exibe nenhuma mensagem em caso de erro
        }
    });
});
</script>
<script>
    document.getElementById('downloadexcel').addEventListener('click', function() {
        const colunasExportar = [1, 4, 9, 11, 13, 14]; // Ordem desejada das colunas
        const table = document.getElementById('myTable');

        if (!table) {
            console.error("Tabela não encontrada!");
            return;
        }

        const rows = table.rows;
        const wb = XLSX.utils.book_new(); // Cria uma nova pasta de trabalho
        const ws = XLSX.utils.aoa_to_sheet([]); // Cria uma nova planilha

        // Itera sobre as linhas da tabela
        for (let i = 0; i < rows.length; i++) {
            let rowData = [];
            const cells = rows[i].cells;

            // Itera sobre as células da linha
            for (let j = 0; j < cells.length; j++) {
                if (colunasExportar.includes(j + 1)) {
                    let cellValue;

                    // Verifica se há um <input> na célula
                    const input = cells[j].querySelector('input');
                    if (input) {
                        // Pega o valor do <input>
                        cellValue = input.value;
                    } else {
                        // Caso contrário, pega o texto da célula
                        cellValue = cells[j].innerText;
                    }

                    // Força o valor a ser exportado como texto, sem a adição de aspas
                    rowData.push({
                        v: cellValue,
                        t: 's'
                    }); // "t: 's'" define como texto
                }
            }
            XLSX.utils.sheet_add_aoa(ws, [rowData], {
                origin: -1
            }); // Adiciona a linha à planilha
        }

        XLSX.utils.book_append_sheet(wb, ws, "Planilha"); // Adiciona a planilha ao livro

        // Gera um número aleatório para o nome do arquivo
        const numeroAleatorio = Math.floor(Math.random() * 1000000); // Número aleatório entre 0 e 999999
        const nomeArquivo = `zmm235-${numeroAleatorio}.xlsx`; // Nome do arquivo

        XLSX.writeFile(wb, nomeArquivo); // Salva o arquivo Excel
    });
</script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const pedidoContainer = document.getElementById('pedido-container');
            const firstInput = pedidoContainer.querySelector('input');

            firstInput.addEventListener('paste', function(event) {
                // Capturar o texto colado
                let pasteContent = event.clipboardData.getData('text');
                let pedidos = pasteContent.split(/\r?\n/); // Separar por linha

                // Limpar o campo atual e colocar o primeiro valor nele
                firstInput.value = pedidos[0];

                // Adicionar campos extras para os outros pedidos
                for (let i = 1; i < pedidos.length; i++) {
                    let newInput = document.createElement('input');
                    newInput.setAttribute('type', 'text');
                    newInput.setAttribute('name', 'pedidos[]');
                    newInput.setAttribute('class', 'form-control me-2');
                    newInput.setAttribute('style', 'margin-top: 10px;');
                    newInput.setAttribute('placeholder', 'Pedido ' + (i + 1));

                    // Colocar o pedido no campo
                    newInput.value = pedidos[i];

                    // Adicionar o campo ao container
                    pedidoContainer.appendChild(newInput);
                }

                event.preventDefault(); // Previne o comportamento padrão de colar
            });
        });
    </script>
</body>

</html>