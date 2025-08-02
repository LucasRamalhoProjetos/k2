@extends('layouts.main')
@section('title', 'Perfil')
@section('content')

    <main class="estrutura">
        <aside class="dados">
            @if (!empty($user->image))
                <img src="{{ asset('img/profile/' . $user->image) }}" alt="foto-perfil">
            @else
                <img src="{{ asset('img/profile/default.png') }}" alt="foto-perfil">
            @endif
            <p class="dados-nome">{{ $user->name }}</p>
            <p class="dados-infos">{{ $user->sexual_orientation }}, {{ $user->relationship_status }}, {{ $user->hometown }}
            </p>
            <ul class="dados-lista">

                @if ($isFriend)
                    <p>Vocês são amigos</p>
                @elseif (Auth::user() && $user->id !== Auth::user()->id)
                    @if ($hasPendingFriendRequests)
                        <div class="alert alert-success">
                            Solicitação de amizade enviada!
                            <form action="{{ route('friendRequest.cancel', ['friend' => $user->id]) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-danger">Cancelar</button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('user.addFriend', ['friend' => $user->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">Adicionar amigo</button>
                        </form>
                    @endif
                @endif

                <!-- <li><a href=""> <img src="{{ asset('img/fotos.png') }}" alt=""> fotos</a></li>
                <li><a href=""> <img src="{{ asset('img/videos.png') }}" alt=""> videos</a></li> -->
                <li><a href=""> <img src="{{ asset('img/depoimentos.png') }}" alt=""> depoimentos</a></li>

            </ul>

        </aside>
        <section class="descricao" style="width: auto; min-width: 750px; max-width: 750px; height: auto; margin: 90px auto;">

            <h1 class="descricao-titulo">{{ $user->name }}</h1>
            <article class="descricao-listas">
                <ul class="descrica-lista-1">

                    <li><a href="{{ route('profile.photos', ['id' => $user->id]) }}"><span class="lista-nomes">fotos</span> <img src="{{ asset('img/fotos.png') }}"
                                alt=""><span class="lista-numeros">{{ $photoCount }}</span></a></li>
                    <li><a href="{{ route('profile.videos', $user->id) }}"><span class="lista-nomes">videos</span><img src="{{ asset('img/videos.png') }}"
                                alt=""><span class="lista-numeros">{{ $videoCount }}</span></a></li>
                    <li><a href=""><span class="lista-nomes">fãs</span><img src="{{ asset('img/fas.png') }}"
                                alt=""> <span class="lista-numeros">50</span></a></li>
                </ul>
                <ul class="descrica-lista-2">
                    <li><a href=""><span class="lista-nomes">confiavel</span>
                            <img src="{{ asset('img/confiavel-completo.png') }}" alt=""></a></li>
                    <li><a href=""><span class="lista-nomes">legal</span>
                            <img src="{{ asset('img/legal-completo.png') }}" alt=""></a></li>
                    <li><a href=""><span class="lista-nomes">sexy</span>
                            <img src="{{ asset('img/sexy-completo.png') }}" alt=""></a></li>
                </ul>
            </article>

            <article class="descricao-informacoes">
                @if ($user->id === Auth::user()->id)
                    <a href="/edit">
                        <h2> Editar <ion-icon style="color: rgb(255, 255, 255)" name="create-outline"></ion-icon>
                        </h2>
                    </a>
                @else
                    <h2>Social</h2>
                @endif
                <hr>
                <ul>
                    <li>
                        <p>Relacionamento: <span>{{ $user->relationship_status }}</span></p>
                    </li>
                    <li>
                        <p>Aniversário: <span>{{ date('d/m/Y', strtotime($user->birthday)) }}</span></p>
                    </li>
                    <li>
                        <p>Idade: <span>{{ $user->age }}</span></p>
                    </li>
                    <li>
                        <p>Interesses: <span>{{ $user->interests }}</span></p>
                    </li>
                    <li>
                        <p>Quem sou eu: <span>{{ $user->about_me }}</span></p>
                    </li>
                    <li>
                        <p>Filhos: <span>{{ $user->children }}</span></p>
                    </li>
                    <li>
                        <p>Etnia: <span>{{ $user->ethnicity }}</span></p>
                    </li>
                    <li>
                        <p>Humor: <span>{{ $user->humor }}</span></p>
                    </li>
                    <li>
                        <p>Orientação sexual: <span>{{ $user->sexual_orientation }}</span></p>
                    </li>
                    <li>
                        <p>Estilo: <span>{{ $user->style }}</span></p>
                    </li>
                    <li>
                        <p>Fumo: <span>{{ $user->smoking }}</span></p>
                    </li>
                    <li>
                        <p>Bebo: <span>{{ $user->drinking }}</span></p>
                    </li>
                    <li>
                        <p>Animais: <span>{{ $user->pets }}</span></p>
                    </li>
                    <li>
                        <p>Moro: <span>{{ $user->location }}</span></p>
                    </li>
                    <li>
                        <p>Cidade natal: <span>{{ $user->hometown }}</span></p>
                    </li>
                    <li>
                        <p>Página web: <span> <a href="http://{{ $user->website }}">{{ $user->website }}</a>
                            </span></p>
                    </li>
                    <li>
                        <p>Paixões: <span>{{ $user->passions }}</span></p>
                    </li>
                </ul>
            </article>
        </section>

        <aside class="amigos-comunidades">
            <article class="amigos">
                <h2>Amigos <span><a href="">{{ $friends->count() }}</a></span></h2>
                <ul>
                    @foreach ($friends as $friend)
                        <li>
                            <a href="{{ route('profile', ['id' => $friend->id]) }}" .><img
                                    src="{{ asset('img/profile/' . $friend->image) }}"
                                    alt="{{ $friend->name }}'s foto-perfil"></a>
                            <span>{{ $friend->name }} ({{ $friend->friends_count }})</span>
                        </li>
                    @endforeach
                </ul>
            </article>

            <article class="comunidades">
                <h2>Comunidades <span><a href="">(128)</a></span></h2>
                <ul>
                    <li><a href=""><img src="{{ asset('img/acordar.jpg') }}" alt=""></a><span>Eu odeio
                            acordar cedo</span></li>
                    <li><a href=""><img src="{{ asset('img/geladeira.jpg') }}" alt=""></a><span>Eu abro a
                            geladeira para pensar</span></li>
                    <li><a href=""><img src="{{ asset('img/desce.jpg') }}" alt=""></a><span>Deus disse,
                            desce e arrasa!</span></li>
                    <li><a href=""><img src="{{ asset('img/legal.jpg') }}" alt=""></a><span>Sou legal, ñ
                            estou te dando mole</span></li>
                    <li><a href=""><img src="{{ asset('img/boardgames.jpeg') }}" alt=""></a><span>Eu AMO
                            jogos de tabuleiro</span></li>
                    <li><a href=""><img src="{{ asset('img/campanhia.png') }}" alt=""></a><span>Eu tocava
                            campainha e corria</span></li>
                </ul>
            </article>
        </aside>
    </main>

    <script src="{{ asset('js/script.js') }}"></script>

@endsection
