@extends('layouts.main')
@section('title', 'Dash-Board')
@section('content')
<main class="estrutura">
    <aside class="dados">
        @if ($user->image)
            <img src="{{ asset('img/profile/' . $user->image) }}" alt="foto-perfil">
        @else
            <img src="{{ asset('img/profile/default.png') }}" alt="foto-perfil">
        @endif
        <p class="dados-nome">{{ $user->name }}</p>

        <ul class="dados-lista">
            <li><a href="{{ route('profile', ['id' => $user->id]) }}"><img src="img/perfil.png" alt=""> perfil</a></li>
            <li><a href="{{ route('profile.photos', ['id' => $user->id]) }}"><img src="img/fotos.png" alt=""> fotos</a></li>
            <li><a href="{{ route('profile.videos', $user->id) }}"><img src="img/videos.png" alt=""> vídeos</a></li>
            <li><a href=""><img src="img/depoimentos.png" alt=""> depoimentos</a></li>
        </ul>
    </aside>
    <section class="descricao" style="width: auto; max-width: 750px; height: auto; margin: 90px auto;">
        <h1 class="descricao-titulo">Bem-vindo(a), {{ $user->name }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <article class="descricao-informacoes">
            @if (!empty($pendingRequests) && count($pendingRequests) > 0)
                <ul>
                    @foreach ($pendingRequests as $request)
                        <li>
                            <div style="display: inline-flex; align-items: center;">
                                <a href="{{ route('profile', ['id' => $request->user->id]) }}" style="font-weight: bold; font-size: 1.2em;">
                                    {{ $request->user->name }} quer ser seu amigo(a)!&nbsp;&nbsp;&nbsp;&nbsp;
                                </a>
                                <form action="{{ route('user.acceptFriendRequest', ['friend' => $request->user->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" style="margin-right: 5px;">Aceitar</button>
                                </form>
                                <form action="{{ route('user.declineFriendRequest', ['friend' => $request->user->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit">Recusar</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
            <br>
            <button id="new-post-btn" class="btn btn-primary">Criar Nova Postagem</button>
            <br><br>
            <div id="post-options" style="display: none; margin-bottom: 20px;">
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="content">O que está pensando hoje:</label>
                        <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Tipo de postagem:</label><br>
                        <input type="radio" id="photo" name="post_type" value="image" checked>
                        <label for="photo">Foto</label>
                        <input type="radio" id="video" name="post_type" value="video">
                        <label for="video">Vídeo</label>
                    </div>

                    <div id="image-upload" class="form-group" style="display: none;">
                        <label for="image">Imagem (apenas jpeg, png, jpg, gif):</label>
                        <input type="file" class="form-control-file" id="image" name="image" accept=".jpeg,.jpg,.png,.gif">
                    </div>

                    <div id="video-upload" class="form-group" style="display: none;">
                        <label for="video">Vídeo (apenas mp4, ogv, webm):</label>
                        <input type="file" class="form-control-file" id="video" name="video" accept=".mp4,.ogv,.webm">
                    </div>

                    <div id="progress-container" style="display: none;">
                        <div id="progress-bar" style="width: 0%; height: 20px; background-color: green;"></div>
                    </div>

                    <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
            </div>

            @foreach ($posts as $post)
                <div class="card">
                    <div class="card-header">
                        <h5>{{ $post->user->name }}:</h5> <br>
                        {{ $post->created_at->locale('pt_BR')->isoFormat('LLLL') }}
                    </div>
                    <div class="card-body">
                        @if ($post->image)
                            <img src="{{ asset('storage/images/' . basename($post->image)) }}" alt="Imagem do post">
                        @endif
                        @if ($post->video)
                            <video controls>
                                <source src="{{ asset($post->video) }}" type="video/mp4">
                                Seu navegador não suporta a reprodução de vídeos.
                            </video>
                        @endif
                        <p class="card-text">{{ $post->content }}</p>
                        <h4>Comentários: </h4>
                        <ul class="comments-list">
                            @foreach ($post->comments as $comment)
                                <li>
                                    <h4>{{ $comment->name }}</h4>
                                    <p>{{ $comment->comment }}</p>
                                </li>
                            @endforeach
                        </ul>

                        <form action="{{ route('comments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <div class="form-group">
                                <textarea class="form-control" name="comment" id="comment" rows="3" required></textarea>
                            </div>
                            <input type="hidden" name="name" value="{{ $user->name }}">
                            <button type="submit">Enviar Comentário</button>
                        </form>
                    </div>
                </div>
            @endforeach

            <div class="custom-pagination">
                <ul class="pagination">
                    <li class="page-item {{ $posts->previousPageUrl() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $posts->previousPageUrl() }}" aria-label="Anterior">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    @foreach ($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $posts->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    <li class="page-item {{ $posts->nextPageUrl() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $posts->nextPageUrl() }}" aria-label="Próxima">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </div>
        </article>
    </section>

    <aside class="amigos-comunidades">
        <article class="amigos">
            <h2>Amigos <span><a href="">{{ $friends->count() }}</a></span></h2>
            <ul>
                @foreach ($friends as $friend)
                    <li>
                        <a href="{{ route('profile', ['id' => $friend->id]) }}"><img src="{{ asset('img/profile/' . $friend->image) }}" alt="{{ $friend->name }}'s foto-perfil"></a>
                        <span>{{ $friend->name }} ({{ $friend->friends_count }})</span>
                    </li>
                @endforeach
            </ul>
        </article>

        <article class="comunidades">
            <h2>Comunidades <span><a href="">(128)</a></span></h2>
            <ul>
                <li><a href=""><img src="img/acordar.jpg" alt=""></a><span>Eu odeio acordar cedo</span></li>
                <li><a href=""><img src="img/geladeira.jpg" alt=""></a><span>Eu abro a geladeira para pensar</span></li>
                <li><a href=""><img src="img/desce.jpg" alt=""></a><span>Deus disse, desce e arrasa!</span></li>
                <li><a href=""><img src="img/legal.jpg" alt=""></a><span>Sou legal, não estou te dando mole</span></li>
                <li><a href=""><img src="img/boardgames.jpeg" alt=""></a><span>Eu AMO jogos de tabuleiro</span></li>
                <li><a href=""><img src="img/campanhia.png" alt=""></a><span>Eu tocava campainha e corria</span></li>
            </ul>
        </article>
    </aside>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/emoji-button@3.4.0/dist/emoji-button.js"></script>
<script>
    document.getElementById('new-post-btn').addEventListener('click', function() {
        const postOptions = document.getElementById('post-options');
        postOptions.style.display = postOptions.style.display === 'none' ? 'block' : 'none';
        this.style.display = postOptions.style.display === 'block' ? 'none' : 'block'; // Esconder ou mostrar o botão
    });

    document.querySelectorAll('input[name="post_type"]').forEach((input) => {
        input.addEventListener('change', function() {
            if (this.value === 'image') {
                document.getElementById('image-upload').style.display = 'block';
                document.getElementById('video-upload').style.display = 'none';
            } else {
                document.getElementById('image-upload').style.display = 'none';
                document.getElementById('video-upload').style.display = 'block';
            }
        });
    });

    document.querySelector('input[name="post_type"]:checked').dispatchEvent(new Event('change'));

    // Adiciona o evento de submissão ao formulário para incluir a barra de carregamento
    const form = document.querySelector('form[action="{{ route('posts.store') }}"]');
    form.addEventListener('submit', function(event) {
        const progressContainer = document.getElementById('progress-container');
        progressContainer.style.display = 'block';
        const progressBar = document.getElementById('progress-bar');
        const xhr = new XMLHttpRequest();

        xhr.open('POST', this.action, true);
        xhr.onload = function() {
            progressContainer.style.display = 'none';
            if (xhr.status === 200) {
                // Atualiza a página após o upload concluído com sucesso
                location.reload();
            } else {
                alert('Erro no upload.');
            }
        };

        xhr.upload.onprogress = function(event) {
            if (event.lengthComputable) {
                const percentComplete = (event.loaded / event.total) * 100;
                progressBar.style.width = percentComplete + '%';
            }
        };

        // Impede o envio padrão do formulário
        event.preventDefault();
        xhr.send(new FormData(form));
    });
</script>

@endsection
