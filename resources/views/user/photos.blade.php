@extends('layouts.main')

@section('title', 'Postagens com Fotos de ' . $user->name)

@section('content')
    <div class="gallery-wrapper">
        <div class="gallery-body">
            <br><br><br><br>
            <h1>Postagens com Fotos de {{ $user->name }}</h1>
            <br>
            @if ($postsWithPhotos->isEmpty())
    <p>Não há postagens com fotos.</p>
@else
    <div class="gallery-container">
        @foreach ($postsWithPhotos as $post)
            <div class="gallery-card">
                <div class="gallery-card-header">
                    <h5 class="author">{{ $post->user->name }}</h5>
                    <div class="gallery-date">{{ $post->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="gallery-card-body">
                    @if ($post->image)
                        <img src="{{ $post->image }}" alt="Post Image" class="post-image">
                    @endif
                    <div class="gallery-caption">{{ $post->content }}</div>
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

    <!-- Modal para visualização da imagem -->
    <div id="imageModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="imgModal">
        <div id="caption"></div> <!-- Legenda da imagem -->
    </div>
@endsection

<!-- Script para o modal -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = document.getElementById('imageModal');
        var modalImg = document.getElementById("imgModal");
        var captionText = document.getElementById("caption");
        var images = document.getElementsByClassName('post-image');

        for (let i = 0; i < images.length; i++) {
            images[i].onclick = function() {
                modal.style.display = "block";
                modalImg.src = this.src;
                captionText.innerHTML = this.getAttribute("data-caption");
            }
        }
    });

    function closeModal() {
        var modal = document.getElementById('imageModal');
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        var modal = document.getElementById('imageModal');
        if (event.target == modal) {
            closeModal();
        }
    }
</script>

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

    .post-image {
        width: 100%;
        height: 200px;
        border-radius: 5px;
        object-fit: cover;
        cursor: pointer;
    }

    .gallery-caption {
        margin-top: 10px;
        font-size: 12px;
        color: #333;
        text-align: left;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.9);
    }

    .modal-content {
        margin: auto;
        display: block;
        max-width: 70%; /* Reduzido para 70% */
        max-height: 70%; /* Reduzido para 70% */
        position: relative;
    }

    #caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
        font-size: 18px;
    }

    .close {
        position: absolute;
        top: 15%; /* Ajustado para 50% */
        left: 95%; /* Centralizado em relação à imagem */
        transform: translate(-50%, -50%); /* Centralização */
        color: #ffffff;
        font-size: 70px;
        font-weight: bold;
        cursor: pointer;
        z-index: 1001;
    }

    .close:hover,
    .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }

    @media only screen and (max-width: 700px) {
        .modal-content {
            width: 100%;
        }
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
