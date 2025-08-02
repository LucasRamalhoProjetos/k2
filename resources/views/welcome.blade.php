<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hubkut</title>

    <link rel="stylesheet" href="css/style.css">

    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">


    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;0,700;1,400&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="home">
            <section class="welcome">
                <div class="logo">
                    <figure>
                        <img src="/assets/login/LogoMetiFy.png" alt="Logo rosa com o nome Hubkut" width="311" height="111">
                    </figure>
                </div>

                <div class="description">
                    <p>
                        <span>Siga</span> amigos e outros devs do seu interesse usando o botão seguir
                    </p>

                    <p>
                        <span>Conheça</span> novos devs e repositórios através da aba explorar
                    </p>

                    <p>
                        <span>Conheça</span> ideias e soluções em um só lugar
                    </p>

                </div>
            </section>

            <section class="login">
                <figure>
                    <img src="/assets/login/illustration-login.svg"
                        alt="Ilustração de pessoa sentada em frente ao computador programando">
                </figure>

                <p>Acesse o <span>hubkut</span> com seu seu email</p>

                <form  method="POST" id="user-github" action="{{ route('login') }}" autocomplete="off" >
                    @csrf

                    <label class="sr-only" for="login" value="{{ __('Email') }}">Digite seu usuário</label>
                    <input type="email" name="email" :value="old('email')" required
                    autofocus autocomplete="username" id="login" placeholder="Digite seu usuário">

                    <div>
                        <label class="sr-only" for="login" value="{{ __('Password') }}">Digite sua senha</label>
                        <x-input type="password" id="login" class="block mt-1 w-full" type="password" name="password" required
                            autocomplete="current-password" />
                    </div>



                   <x-button class="ml-4">
                        {{ __('Log in') }}
                    </x-button>
                    </a>

                </form>
                <br>
                <a href="/register">Cadastre-se</a>
            </section>

        </div>

        <footer>
            <p>&copy; 2023 NetiFy</p>

            <ul>
                <li><a href="#">Sobre o NetiFy</a></li>
                <li><a href="#">Centro de segurança</a></li>
                <li><a href="#">Privacidade</a></li>
                <li><a href="#">Termos</a></li>
                <li><a href="#">Contato</a></li>
            </ul>
        </footer>
    </div>
</body>

</html>
