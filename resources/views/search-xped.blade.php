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
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Buscar XPED</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Search XPED</li>
                    </ol>


                    <div class="container">
                        <!-- Formulário para salvar um único pedido -->
                        <form class="d-flex" method="POST" action="{{ route('save-single-pedido') }}">
                            @csrf <!-- Token de segurança CSRF -->

                            <!-- Campo de busca simples -->
                            <input class="form-control me-2" type="search" name="pedido_unico" placeholder="Pesquisar Xped" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Salvar</button>
                        </form>

                        <!-- Botão para abrir o modal de múltiplos pedidos -->
                        <button style="margin-top: 10px;" type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Selecionar
                        </button>

                        <!-- Modal para salvar múltiplos pedidos -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Cole seus pedidos</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Formulário para salvar múltiplos pedidos -->
                                        <form method="POST" action="{{ route('save-pedidos') }}">
                                            @csrf <!-- Token de segurança CSRF -->

                                            <!-- Campo inicial onde os pedidos serão colados -->
                                            <div id="pedido-container">
                                                <input style="margin-top: 10px;" class="form-control me-2" type="text" name="pedidos[]" placeholder="Cole seus pedidos aqui">
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                                <!-- Botão para submeter os pedidos -->
                                                <button type="submit" class="btn btn-primary">Salvar Pedidos</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Adicionar JavaScript -->
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

                    <div class="container">


                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        <!-- Tabela de Pedidos -->
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Pedido</th>
                                    <th>Chave de Acesso</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($pedidos->isEmpty())
                                <tr>
                                    <td colspan="4">Nenhum pedido encontrado.</td>
                                </tr>
                                @else
                                @foreach($pedidos as $pedido)
                                <tr>
                                    <td>{{ $pedido->pedido }}</td>
                                    
                                    <td></td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm buscar-xml" data-pedido="{{ $pedido->pedido }}" data-id="{{ $pedido->id }}">
                                            Buscar XML
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $pedido->id }}">
                                            Editar
                                        </button>
                                        <form action="{{ route('deletar-pedido', $pedido->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal de Edição -->
                                <div class="modal fade" id="editModal{{ $pedido->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $pedido->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel{{ $pedido->id }}">Editar Pedido</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('atualizar-pedido', ['id' => $pedido->id]) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="pedido" class="form-label">Pedido</label>
                                                        <input type="text" class="form-control" id="pedido" name="pedido" value="{{ $pedido->pedido }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="fornecedor" class="form-label">Fornecedor</label>
                                                        <input type="text" class="form-control" id="fornecedor" name="chave_acesso" value="{{ $pedido->chave_acesso }}" required>
                                                    </div>
                                                    <!-- Adicione mais campos conforme necessário -->
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
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