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
                        <a class="nav-link" href="index.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Import XML
                        </a>
                        <a class="nav-link" href="index.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Base de dados
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
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Nota fiscal</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">XML - dashboard</li>
                    </ol>

                    <div class="container">
                        <?php
                        $xmlObject = simplexml_load_string($xmlContent);
                        ?>

                        <ul style="margin-top: 50px;" class="list-group">
                            <li class="list-group-item"><strong>Chave de Acesso:</strong> {{ str_replace("NFe", "", $xmlObject->NFe->infNFe['Id']) }}</li>
                            <li class="list-group-item"><strong>Nota Fiscal:</strong> {{ $xmlObject->NFe->infNFe->ide->nNF }}</li>
                            <li class="list-group-item"><strong>Série:</strong> {{ $xmlObject->NFe->infNFe->ide->serie }}</li>
                        </ul>

                        <h6 style="margin-top: 50px;">Informação</h6>
                        <p><strong>Emitente/CNPJ:</strong> {{ $xmlObject->NFe->infNFe->emit->CNPJ }}</p>
                        <p><strong>Razão Social:</strong> {{ $xmlObject->NFe->infNFe->emit->xNome }}</p>

                        <h6 style="margin-top: 50px;">Informação</h6>
                        <p><strong>Destinatario/Doc:</strong> {{ $xmlObject->NFe->infNFe->dest->CNPJ }}</p>
                        <p><strong>Destinatario/Nome:</strong> {{ $xmlObject->NFe->infNFe->dest->xNome }}</p>
                    </div>
                </div>
                <div style="width:1700px; margin-top: 50px;" class="container-fluid">

                    <a style="top:100px" href='dashboard'>Ir para envio de arquivos.</a><br />

                    <table id="myTable" style="margin-top: 50px;" class="table">
                        <thead>
                            <tr>

                                <th scope="col">Pedido</th>
                                <th scope="col">SAP</th>
                                <th scope="col">Material</th>
                                <th scope="col">EAN</th>
                                <th scope="col">CX</th>
                                <th scope="col">CXSAP</th>
                                <th scope="col">Faturado</th>
                                <th scope="col">Despesas</th>
                                <th scope="col">Desconto</th>
                                <th scope="col">Custo</th>
                                <th scope="col">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($xmlObject->NFe->infNFe->det as $item)
                            <tr>
                                <!-- Campo xPed -->
                                <td>
                                    @php
                                    // Obtenha o valor de xPed
                                    $xPed = (string) $item->prod->xPed;

                                    // Verifica se os 3 primeiros números são "450"
                                    if (substr($xPed, 0, 3) === '450') {
                                    // Se sim, exibe o valor de xPed
                                    $valorFinal = $xPed;
                                    } else {
                                    // Se não, busque no campo infCpl
                                    $infCpl = (string) $xmlObject->NFe->infNFe->infAdic->infCpl;

                                    // Busca pelo padrão "450" no infCpl
                                    preg_match('/450\d*/', $infCpl, $matches);
                                    $valorFinal = !empty($matches) ? $matches[0] : 'Não encontrado'; // Se encontrar, usa o valor; caso contrário, mensagem padrão
                                    }
                                    @endphp

                                    {{-- Exibe o valor final --}}
                                    {{ $valorFinal }}
                                </td>


                                <!-- Banco de dados - Busca EAN -->
                                </td>

                                <td class="export">
                                        @php
                                        $produto = \App\Models\Product::whereRaw('LOWER(TRIM(codigo_barras)) = ?', [trim(strtolower($item->prod->cEANTrib))])->first();

                                        // Caso não encontre pelo cEANTrib, tenta buscar pelo xProd
                                        if (!$produto) {
                                        $produto = \App\Models\Product::whereRaw('LOWER(TRIM(codigo_barras)) = ?', [trim(strtolower($item->prod->xProd))])->first();
                                        }
                                        @endphp

                                        @if ($produto)
                                        {{ $produto->sap }}
                                        @else
                                        Material não encontrado
                                        @endif
                                    </td>

                                <!-- XML - descrição produto -->
                                <td>{{ $item->prod->xProd }}</td>

                                <!-- XML EAN do xml -->
                                <td>
                                    {{-- Exibe o EAN diretamente --}}
                                    {{ $item->prod->cEANTrib }}
                                </td>

                                <!-- XML CX -->
                                <td>{{ $item->prod->qCom }}</td>

                                <!-- Banco de dados - buscar caixaria -->
                                <td>
                                    {{-- Busca o produto (SAP e caixaria) --}}
                                    @php
                                    $produto = \App\Models\Product::where('codigo_barras', $item->prod->cEANTrib)->first();
                                    @endphp
                                    @if ($produto)
                                    {{-- Exibe a caixaria --}}
                                    {{ $produto->caixaria }}
                                    @else
                                    Caixaria não encontrada
                                    @endif
                                </td>

                                <!-- Quantidade X -->
                                <td>
                                    @php
                                    // Verifica se o valor de qCom é maior que 0
                                    $qCom = (float) $item->prod->qCom;
                                    $caixaria = $produto ? (float) $produto->caixaria : 0;

                                    // Se qCom for maior que 0 e houver caixaria, multiplica qCom pela caixaria, senão exibe qCom diretamente
                                    $quantidadeX = ($qCom > 0 && $caixaria > 0) ? $qCom * $caixaria : $qCom;
                                    @endphp

                                    {{-- Exibe o valor de Quantidade X --}}
                                    {{ $quantidadeX }}
                                </td>

                                <!-- Campo vOutro -->
                                <td>{{ $item->prod->vOutro }}</td>

                                <!-- Campo vDesc -->
                                <td>{{ $item->prod->vDesc }}</td>

                                <!-- Campo Valor Unitário -->
                                <td>
                                    @php
                                    // Obtenção dos valores de vProd e vOutro
                                    $vProd = (float) $item->prod->vProd;
                                    $vOutro = (float) $item->prod->vOutro;

                                    // Verifica se a caixaria é maior que 0 para evitar divisão por 0
                                    if ($caixaria > 0) {
                                    // Se vOutro for maior que 0, soma vProd com vOutro e divide pela caixaria
                                    if ($vOutro > 0) {
                                    $valorUnitario = ($vProd + $vOutro) / $quantidadeX;
                                    } else {
                                    // Se vOutro for 0, apenas divide vProd pela caixaria
                                    $valorUnitario = $vProd / $quantidadeX;
                                    }
                                    } else {
                                    // Caso a caixaria não exista, o valor unitário será apenas o vProd
                                    $valorUnitario = $vProd;
                                    }
                                    @endphp

                                    {{-- Exibe o valor unitário --}}
                                    {{ $valorUnitario }}
                                </td>

                                <!-- Campo vProd -->
                                <td>{{ $vProd }}</td>


                            </tr>
                            @endforeach
                    </table>


                </div>
                <div style="margin-top: 100px;" class="container">
                    <div class="d-grid gap-2 col-6 mx-auto">
                        <!-- Adicione este botão -->
                        <button class="btn btn-primary" type="button" id="downloadexcel">Exporta Excel</button>
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