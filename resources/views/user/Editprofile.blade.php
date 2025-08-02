
    @extends('layouts.main')
    @section('title','Editar perfil')
    @section('content')

    <form action="{{ route('profile', ['id' => Auth::id()]) }}" method="POST" class='profileedit'
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <!-- Resto do formulário -->

        <label for="profile_picture">Foto do Perfil:</label>
        @if($user->image)
        <img src="{{ asset('img/profile/'.$user->image) }}" alt="Foto do perfil" style="width: auto; height: 200px;">
        @endif
        <input type="file" id="profile_picture" name="image">
        <br><br>

        <label for="relationship_status">Relacionamento:</label>
        <input type="text" id="relationship_status" name="relationship_status" value="{{ $user->relationship_status}} ">
        <br><br>

        <label for="birthday">Aniversário:</label>
        <input type="date" id="birthday" name="birthday" value="{{ $user->birthday }}">
        <br><br>

        <label for="age">Idade:</label>
        <input type="number" id="age" name="age" value="{{$user->age}}">
        <br><br>

        <label for="interests">Interesses:</label>
        <input type="text" id="interests" name="interests" value="{{$user->interests}}">
        <br><br>

        <label for="about_me">Quem sou eu:</label>
        <textarea id="about_me" name="about_me">{{$user->about_me}}</textarea>
        <br><br>

        <label for="children">Filhos:</label>
        <input type="text" id="children" name="children" value="{{$user->children}}">
        <br><br>

        <label for="ethnicity">Etnia:</label>
        <input type="text" id="ethnicity" name="ethnicity" value="{{$user->ethnicity}}">
        <br><br>

        <label for="humor">Humor:</label>
        <input type="text" id="humor" name="humor" value="{{$user->humor}}">
        <br><br>

        <label for="sexual_orientation">Orientação Sexual:</label>
        <input type="text" id="sexual_orientation" name="sexual_orientation" value="{{$user->sexual_orientation}}">
        <br><br>

        <label for="style">Estilo:</label>
        <input type="text" id="style" name="style" value="{{$user->style}}">
        <br><br>

        <label for="smoking">Fumo:</label>
        <input type="text" id="smoking" name="smoking" value="{{$user->smoking}}">
        <br><br>

        <label for="drinking">Bebo:</label>
        <input type="text" id="drinking" name="drinking" value="{{$user->drinking}}">
        <br><br>

        <label for="pets">Animais:</label>
        <input type="text" id="pets" name="pets" value="{{$user->pets}}">
        <br><br>

        <label for="location">Moro:</label>
        <input type="text" id="location" name="location" value="{{$user->location}}">
        <br><br>

        <label for="hometown">Cidade Natal:</label>
        <input type="text" id="hometown" name="hometown" value="{{$user->hometown}}">
        <br><br>

        <label for="website">Página Web:</label>
        <input type="text" id="website" name="website" value="{{$user->website}}">
        <br><br>

        <label for="passions">Paixões:</label>
        <input type="text" id="passions" name="passions" value="{{$user->passions}}">
        <br><br>

        <button type="submit">Salvar</button>
    </form>


    @endsection
