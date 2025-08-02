@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Enviar Recado</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('user.messages.send') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="receiver_id">Destinatário:</label>
                <select name="receiver_id" id="receiver_id" class="form-control">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="message">Mensagem:</label>
                <textarea name="message" id="message" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>
@endsection
