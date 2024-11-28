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
                    <ol class="breadcrumb mb-4">
                        <li style="margin-top: 20px;" class="breadcrumb-item active">Base dados</li>
                    </ol>
                    <h1 class="mt-4">Lista de Produtos</h1>

                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">Adicionar</button>

                    <form method="GET" action="{{ route('base-dados') }}">
                        <div class="input-group mb-3">
                            <input type="text" name="search" class="form-control" placeholder="Buscar produtos" value="{{ request()->input('search') }}">
                            <button class="btn btn-primary" type="submit">Buscar</button>
                        </div>
                    </form>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Código de Barras</th>
                                <th>Descrição</th>
                                <th>SAP</th>
                                <th>Caixaria</th>
                                <th>Código do Fornecedor</th>
                                <th>CNPJ do Fornecedor</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produtos as $produto)
                            <tr>
                                <td>{{ $produto->id }}</td>
                                <td>{{ $produto->codigo_barras }}</td>
                                <td>{{ $produto->descricao }}</td>
                                <td>{{ $produto->sap }}</td>
                                <td>{{ $produto->caixaria }}</td>
                                <td>{{ $produto->cod_fornecedor }}</td>
                                <td>{{ $produto->cnpj_fornecedor }}</td>
                                <td>
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $produto->id }}">Editar</button>

                                    <form action="{{ route('produtos.destroy', $produto->id) }}" method="POST" style="display: inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este produto?')">
        Excluir
    </button>
</form>
                                    <div class="modal fade" id="editModal{{ $produto->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $produto->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel{{ $produto->id }}">Editar Produto</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('update.produto', $produto->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="page" value="{{ request()->get('page', 1) }}">
                                                        <div class="mb-3">
                                                            <label for="codigo_barras" class="form-label">Código de Barras</label>
                                                            <input type="text" class="form-control" id="codigo_barras" name="codigo_barras" value="{{ $produto->codigo_barras }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="descricao" class="form-label">Descrição</label>
                                                            <input type="text" class="form-control" id="descricao" name="descricao" value="{{ $produto->descricao }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="sap" class="form-label">SAP</label>
                                                            <input type="text" class="form-control" id="sap" name="sap" value="{{ $produto->sap }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="caixaria" class="form-label">Caixaria</label>
                                                            <input type="text" class="form-control" id="caixaria" name="caixaria" value="{{ $produto->caixaria }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="cod_fornecedor" class="form-label">Código do Fornecedor</label>
                                                            <input type="text" class="form-control" id="cod_fornecedor" name="cod_fornecedor" value="{{ $produto->cod_fornecedor }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="cnpj_fornecedor" class="form-label">CNPJ do Fornecedor</label>
                                                            <input type="text" class="form-control" id="cnpj_fornecedor" name="cnpj_fornecedor" value="{{ $produto->cnpj_fornecedor }}">
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                                    <button type="submit" class="btn btn-primary">Salvar alterações</button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addProductModalLabel">Adicionar Produto</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('produtos.store') }}" method="POST">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label for="codigo_barras" class="form-label">Código de Barras</label>
                                                            <input type="text" class="form-control" id="codigo_barras" name="codigo_barras" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="descricao" class="form-label">Descrição</label>
                                                            <input type="text" class="form-control" id="descricao" name="descricao" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="sap" class="form-label">SAP</label>
                                                            <input type="text" class="form-control" id="sap" name="sap" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="caixaria" class="form-label">Caixaria</label>
                                                            <input type="text" class="form-control" id="caixaria" name="caixaria" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="cod_fornecedor" class="form-label">Código do Fornecedor</label>
                                                            <input type="text" class="form-control" id="cod_fornecedor" name="cod_fornecedor" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="cnpj_fornecedor" class="form-label">CNPJ do Fornecedor</label>
                                                            <input type="text" class="form-control" id="cnpj_fornecedor" name="cnpj_fornecedor" required>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                                    <button type="submit" class="btn btn-primary">Adicionar Produto</button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-3">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                @if ($produtos->onFirstPage())
                                <li class="page-item disabled">
                                    <a class="page-link">Previous</a>
                                </li>
                                @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $produtos->previousPageUrl() }}">Previous</a>
                                </li>
                                @endif

                                {{-- Botão para a primeira página --}}
                                @if ($produtos->currentPage() > 3)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $produtos->url(1) }}">1</a>
                                </li>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                                @endif

                                {{-- Botões para páginas próximas --}}
                                @for ($i = max(1, $produtos->currentPage() - 2); $i <= min($produtos->lastPage(), $produtos->currentPage() + 2); $i++)
                                    <li class="page-item {{ $produtos->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $produtos->url($i) }}">{{ $i }}</a>
                                    </li>
                                    @endfor

                                    {{-- Botão para a última página --}}
                                    @if ($produtos->currentPage() < $produtos->lastPage() - 2)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $produtos->url($produtos->lastPage()) }}">{{ $produtos->lastPage() }}</a>
                                        </li>
                                        @endif

                                        @if ($produtos->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $produtos->nextPageUrl() }}">Next</a>
                                        </li>
                                        @else
                                        <li class="page-item disabled">
                                            <a class="page-link">Next</a>
                                        </li>
                                        @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </main>
        </div>
    </div>
    </div>
    @endsection