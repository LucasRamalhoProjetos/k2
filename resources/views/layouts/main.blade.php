<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('csslogado/style.css')}}">
    <link rel="stylesheet" href="{{ asset('css/styleedit.css')}}">
    <link rel="shortcut icon" href="favicon.ico">
    <title>@yield('title')</title>
</head>

<body class="perfil">
    <header class="header">
        <nav class="menu">
            <img src="{{ asset('img/orkut-logo-nome.png')}}" alt="">
            <ul class="menu-opcoes">
                <li><a href="/dashboard">Início</a></li>
                <li><a href="">Página de Recados</a></li>
                <li><a href="">Amigos</a></li>
                <li><a href="">Comunidade</a></li>
            </ul>
        </nav>

        <nav class="menu-mobile">

            <div class="menu-hamburguer">
                <input type="checkbox" id="toggle">
                <label data-menu-botao class="checkbox" for="toggle">
                    <div class="trace"> </div>
                    <div class="trace"> </div>
                    <div class="trace"> </div>
                </label>
            </div>

            <img class="logo-icon" src="{{ asset('img/orkut-logo-nome.png')}}" alt="">
            <ul data-menu-lista class="menu-mobile-opcoes">
                <li><a href="/dashboard"><img src="{{ asset('img/home.png')}}" alt="">Início</a></li>
                <li><a href=""><img src="{{ asset('img/recados.png')}}" alt="">Recados</a></li>
                <li><a href=""><img src="{{ asset('img/amigos.png')}}" alt="">Amigos</a></li>
                <li><a href=""><img src="{{ asset('img/comunidades.png')}}" alt="">Comunidades</a></li>
            </ul>
        </nav>
        <div class="header-infos">
            <a href="" class="header-email">{{ Auth::user()->email }}</a>
            <div class="logout">
                <form action="/logout" method="POST">
                    @csrf
                    <a href="#" class="nav-link"
                        onclick="event.preventDefault();
                                                                                        this.closest('form').submit();">Sair</a>
                </form>
            </div>
            <div class="pesquisa">
                <input type="text" name="pesquisa" id="pesquisa">
                <img src="{{ asset('img/lupa.png')}}" alt="">
            </div>
        </div>
    </header>

    @yield('content'){{--aqui irá ser incluido o conteudo das paginas--}}


    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    </body>
    </html>
