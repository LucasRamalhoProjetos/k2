<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Comment;

class UserController extends Controller
{

    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        // Obtém os IDs dos amigos
        $friendIds = $user->friends()->pluck('friend_id')->toArray();
        $friendIds[] = $user->id; // Inclui o próprio usuário

        // Busca as postagens apenas dos amigos e do próprio usuário
        $posts = Post::whereIn('user_id', $friendIds)->latest()->with('user', 'comments')->paginate(10);

        $pendingRequests = $user->friendRequests()->where('status', 'pending')->with('user')->get();
        $friends = $user->friends()->withCount('friends')->get();
        $unreadMessagesCount = $user->unreadMessages()->count();

        return view('user.dashboard', compact('user', 'posts', 'pendingRequests', 'friends', 'unreadMessagesCount'));
    }

    public function show($id)
    {
        $user = User::with('friendRequests')->findOrFail($id);
        $hasPendingFriendRequests = $user->hasPendingFriendRequests();

        $isFriend = false;

        if (auth()->check()) {
            $authUserId = auth()->user()->id;

            // Verifica se o usuário autenticado é amigo
            $isFriend = Friend::where('user_id', $authUserId)
                ->where('friend_id', $id)
                ->where('status', 'accepted')
                ->exists();

            // Verifica se há solicitações de amizade pendentes
            $hasPendingFriendRequests = $hasPendingFriendRequests && Friend::where('user_id', $authUserId)
                ->where('friend_id', $id)
                ->where('status', 'pending')
                ->exists();
        }

        // Contagem de fotos (postagens com imagens)
        $photoCount = $user->posts()->whereNotNull('image')->count();

        // Contagem de vídeos (postagens com vídeos)
        $videoCount = $user->posts()->whereNotNull('video')->count();

        $friends = $user->friends()->withCount('friends')->get();

        if ($hasPendingFriendRequests) {
            session()->flash('success', 'Solicitação de amizade enviada!');
        }

        return view('user.profile', compact('user', 'hasPendingFriendRequests', 'isFriend', 'friends', 'photoCount', 'videoCount'));
    }




    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required|max:500',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'mimes:mp4,ogv,webm|max:200000',
        ], [
            'content.required' => 'O conteúdo é obrigatório.',
            'content.max' => 'O conteúdo deve ter no máximo 500 caracteres.',
            'image.image' => 'O arquivo deve ser uma imagem válida.',
            'image.mimes' => 'A imagem deve ser do tipo: jpeg, png, jpg, gif.',
            'image.max' => 'A imagem deve ter no máximo 2MB.',
            'video.mimes' => 'O vídeo deve ser do tipo: mp4, ogv, webm.',
            'video.max' => 'O vídeo deve ter no máximo 20MB.',
        ]);

        // Criação do novo post
        $post = new Post;
        $post->user_id = Auth::id(); // Melhor prática para obter o ID do usuário
        $post->content = $validatedData['content'];

        // Salvar a imagem se estiver presente no formulário
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/images');
            $post->image = Storage::url($imagePath);
        }

        // Salvar o vídeo se estiver presente no formulário
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('public/videos');
            $post->video = Storage::url($videoPath);
        }

        $post->save();

        // Redirecionar para a página de timeline
        return redirect()->route('timeline')->with('success', 'Post criado com sucesso!');
    }


    public function editProfile()
    {
        $user = Auth::user(); // Obtém o usuário autenticado

        // Verifica se o usuário autenticado é o mesmo que está editando o perfil
        if (!$user) {
            return redirect()->route('login');
        }

        return view('user.Editprofile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        // Atualizar os campos do usuário com os dados recebidos do formulário
        $user->relationship_status = $request->relationship_status;
        $user->birthday = $request->birthday;
        $user->age = $request->age;
        $user->interests = $request->interests;
        $user->about_me = $request->about_me;
        $user->children = $request->children;
        $user->ethnicity = $request->ethnicity;
        $user->humor = $request->humor;
        $user->sexual_orientation = $request->sexual_orientation;
        $user->style = $request->style;
        $user->smoking = $request->smoking;
        $user->drinking = $request->drinking;
        $user->pets = $request->pets;
        $user->location = $request->location;
        $user->hometown = $request->hometown;
        $user->website = $request->website;
        $user->passions = $request->passions;

        // Salvar a imagem somente se for enviada no formulário
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->image;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $request->image->move(public_path('img/profile'), $imageName);
            $user->image = $imageName;
        }

        $user->save();

        return redirect('/profile/' . $user->id)->with('success', 'Perfil editado com sucesso!');
    }

    public function addFriend($friend)
{
    $user = auth()->user();

    // Criar uma nova instância de Friend
    $friendship = new Friend();
    $friendship->user_id = $user->id;
    $friendship->friend_id = $friend;
    $friendship->status = true;
    $friendship->save();

    // Verificar se o usuário autenticado é o remetente da solicitação de amizade
    if ($user->id == $friend) {
        // Redirecionar para a página de perfil com uma mensagem de sucesso
        return redirect('/profile/' . $friend)->with('success', 'Solicitação de amizade enviada!');
    } else {
        // Redirecionar para a página de perfil sem adicionar a mensagem
        return redirect('/profile/' . $friend);
    }
}


    public function cancel(Request $request, $friend)
    {
        $user = auth()->user();

        // Verificar se o usuário autenticado é o remetente da solicitação de amizade
        $friendship = Friend::where('user_id', $user->id)
            ->where('friend_id', $friend)
            ->where('status', 'pending')
            ->first();

        if ($friendship) {
            // Excluir a solicitação de amizade pendente
            $friendship->delete();

            // Redirecionar para a página de perfil com uma mensagem de sucesso
            return redirect('/profile/' . $friend)->with('warning', 'Solicitação de amizade cancelada!');
        }
    }



    public function acceptFriendRequest(Request $request, $friend)
    {
        $user = auth()->user();

        // Localizar a solicitação de amizade pendente
        $friendship = Friend::where('user_id', $friend)
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($friendship) {
            // Atualizar o status da solicitação para "aceita"
            $friendship->status = 'accepted';
            $friendship->save();

            // Criar uma nova solicitação de amizade para o mesmo usuário
            $newFriendship = new Friend();
            $newFriendship->user_id = $user->id;
            $newFriendship->friend_id = $friend;
            $newFriendship->status = 'accepted';
            $newFriendship->save();

            // Redirecionar para a página de perfil com uma mensagem de sucesso
            return redirect('/dashboard')->with('success', 'Solicitação de amizade aceita!');
        }
    }


    public function declineFriendRequest(Request $request, $friend)
    {
        $user = auth()->user();

        // Localizar a solicitação de amizade pendente
        $friendship = Friend::where('user_id', $friend)
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($friendship) {
            // Excluir a solicitação de amizade pendente
            $friendship->delete();

            // Redirecionar para a página de perfil com uma mensagem de sucesso
            return redirect('/profile/' . $friend)->with('warning', 'Solicitação de amizade recusada!');
        }
    }

    public function storeComment(Request $request)
    {
        // Validação dos dados do formulário
        $validatedData = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'name' => 'required',
            'comment' => 'required',
        ]);

        // Pegar o email do usuário autenticado
        $email = Auth::user()->email;

        // Criação do novo comentário
        $comment = new Comment;
        $comment->post_id = $validatedData['post_id'];
        $comment->name = $validatedData['name'];
        $comment->comment = $validatedData['comment'];
        $comment->email = $email; // Atribuir o email do usuário
        $comment->save();

        // Redirecionar para a página de timeline
        return redirect()->route('timeline');
    }

    public function sendMessage()
    {
        // Lógica para enviar mensagem

        return redirect()->route('user.messages')->with('success', 'Mensagem enviada com sucesso!');
    }

    public function showMessages()
    {
        /** @var User $user */
        $user = auth()->user();
        $receivedMessages = $user->receivedMessages;
        $sentMessages = $user->sentMessages;
        $users = User::where('id', '!=', $user->id)->get();
        $friends = $user->friends()->withCount('friends')->get();

        return view('user.messages', compact('receivedMessages', 'sentMessages', 'users', 'friends'));
    }


    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        // Verifique se o usuário autenticado é o dono da postagem
        if (Auth::id() !== $post->user_id) {
            return redirect()->route('timeline')->with('error', 'Você não tem permissão para excluir esta postagem.');
        }

        $post->delete();

        return redirect()->route('timeline')->with('success', 'Postagem excluída com sucesso!');
    }


    public function showPhotos($id)
    {
        $user = User::findOrFail($id);
        $postsWithPhotos = $user->posts()->whereNotNull('image')->with('comments')->get(); // Inclui comentários

        return view('user.photos', compact('user', 'postsWithPhotos'));
    }

    public function showVideos($id)
    {
        $user = User::findOrFail($id);
        $postsWithVideos = $user->posts()->whereNotNull('video')->with('comments')->get(); // Inclui comentários

        return view('user.videos', compact('user', 'postsWithVideos'));
    }



}
