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
                        <a class="nav-link" href="{{ route('search-xped') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Search XPED
                        </a>

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
                    <h1 class="mt-4">Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Importa XML</li>
                    </ol>

                    <div>
                        @if(session('error'))
                        <p class="text-danger">{{ session('error') }}</p>
                        @endif

                        <form action="{{ route('import.xml') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="xmlFiles" class="form-label">Selecione os arquivos XML</label>
                                <input type="file" class="form-control" id="xmlFiles" name="xmlFiles[]" accept=".xml" multiple required>
                            </div>
                            <button type="submit" class="btn btn-primary">Importar XML</button>
                        </form>

                        <form style="margin-top: 10px;" action="{{ route('apagar-tudo') }}" method="post" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger " onclick="return confirm('Tem certeza que deseja apagar todos os seus XMLs?')">Apagar Tudo</button>
                        </form>

                        <form style="margin-top: 10px;" action="{{ route('visualizar-todas-xmls') }}" method="post" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-warning">Visualizar Todos os XMLs</button>
                        </form>


                        <div style="margin-top: 80px;" class="container">
                            <ul class="list-group">
                                @foreach($files as $file)
                                <li class="list-group-item">
                                    <a href="{{ asset('storage/xml_files/' . $file->file_name) }}">{{ $file->file_name }}</a>
                                    <a style="margin-left: 150px;" href="{{ route('visualizar-xml', ['arquivo' => $file->file_name]) }}" class="btn btn-secondary btn-sm">Visualizar</a>
                                    <form action="{{ route('apagar-xml', ['arquivo' => $file->file_name]) }}" method="post" style="display:inline;">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja apagar este XML?')">Apagar</button>
                                    </form>
                                </li>
                                @endforeach
                            </ul>

                            <div class="mt-3">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center">
                                        @if ($files->onFirstPage())
                                        <li class="page-item disabled">
                                            <a class="page-link">Previous</a>
                                        </li>
                                        @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $files->previousPageUrl() }}">Previous</a>
                                        </li>
                                        @endif

                                        @for ($i = 1; $i <= $files->lastPage(); $i++)
                                            <li class="page-item {{ $files->currentPage() == $i ? 'active' : '' }}">
                                                <a class="page-link" href="{{ $files->url($i) }}">{{ $i }}</a>
                                            </li>
                                            @endfor

                                            @if ($files->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $files->nextPageUrl() }}">Next</a>
                                            </li>
                                            @else
                                            <li class="page-item disabled">
                                                <a class="page-link">Next</a>
                                            </li>
                                            @endif
                                    </ul>
                                </nav>
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