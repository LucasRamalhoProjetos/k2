@extends('layouts.main')

@section('title', 'Postagens com Vídeos de ' . $user->name)

@section('content')
    <div class="gallery-wrapper">
        <div class="gallery-body">
            <br><br><br><br>
            <h1>Postagens com Vídeos de {{ $user->name }}</h1>
            <br>
            @if ($postsWithVideos->isEmpty())
                <p>Não há postagens com vídeos.</p>
            @else
                <div class="gallery-container">
                    @foreach ($postsWithVideos as $post)
                        <div class="gallery-card">
                            <div class="gallery-card-header">
                                <h5 class="author">{{ $post->user->name }}</h5>
                                <div class="gallery-date">{{ $post->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                            <div class="gallery-card-body">
                                @if ($post->video)
                                    <video class="post-video" controls>
                                        <source src="{{ $post->video }}" type="video/mp4">
                                        Seu navegador não suporta o elemento de vídeo.
                                    </video>
                                    <div class="gallery-caption">{{ $post->content }}</div>
                                @endif
                            </div>
                            <div class="comments-section">
                                <h6>Comentários:</h6>
                                @foreach ($post->comments as $comment)
                                    <div class="comment">
                                        <strong>{{ $comment->name }}:</strong> {{ $comment->comment }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

<!-- Estilos -->
<style>
    body {
        margin: 0;
        padding: 0;
        background-color: #f9f9f9;
    }

    .gallery-wrapper {
        max-width: 1000px;
        margin: 20px auto 0;
        padding: 0 20px;
        font-family: 'Arial', sans-serif;
    }

    .gallery-body {
        text-align: center;
    }

    h1 {
        color: #333;
        margin: 10px 0;
        font-size: 24px;
    }

    .gallery-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
    }

    .gallery-card {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .gallery-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    }

    .gallery-card-header {
        background-color: #007bff;
        color: #ffffff;
        padding: 8px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .author {
        margin: 0;
        font-size: 16px;
    }

    .gallery-date {
        font-size: 12px;
        opacity: 0.8;
    }

    .gallery-card-body {
        padding: 10px;
        display: flex;
        flex-direction: column;
    }

    .post-video {
        width: 100%;
        height: 200px;
        border-radius: 5px;
    }

    .gallery-caption {
        margin-top: 10px;
        font-size: 12px;
        color: #333;
        text-align: left;
    }

    .comments-section {
        margin-top: 10px;
        font-size: 14px;
        color: #555;
    }

    .comment {
        margin-bottom: 5px;
    }

    @media (max-width: 768px) {
        .gallery-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .gallery-container {
            grid-template-columns: 1fr;
        }
    }
</style>
