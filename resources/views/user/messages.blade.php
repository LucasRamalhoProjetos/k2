@extends('layouts.main')
@section('title', 'Perfil')
@section('content')
    <div class="container">
        <h1>Recados</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <h2>Recados Recebidos</h2>
                <ul id="received-messages" class="message-list">
                    @foreach($receivedMessages as $message)
                        <li>
                            <strong>De:</strong> {{ $message->sender->name }}
                            <p>{{ $message->message }}</p>
                            <p class="text-muted">{{ $message->created_at->diffForHumans() }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="col-md-6">
                <h2>Recados Enviados</h2>
                <ul id="sent-messages" class="message-list">
                    @foreach($sentMessages as $message)
                        <li>
                            <strong>Para:</strong> {{ $message->receiver->name }}
                            <p>{{ $message->message }}</p>
                            <p class="text-muted">{{ $message->created_at->diffForHumans() }}</p>
                        </li>
                    @endforeach
                </ul>

                <form id="message-form" action="{{ route('user.messages.send') }}" method="POST">
                    @csrf
                    <input type="hidden" name="receiver_id" value="">
                    <div class="form-group">
                        <label for="message">Mensagem:</label>
                        <textarea name="message" id="message" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
    <h2>Lista de Amigos</h2>
    <ul class="friend-list">
        @foreach($friends as $friend)
            <li>
                <span>{{ $friend->name }}</span>
                @if($friend->isOnline())
                    <span class="online-status">Online</span>
                @else
                    <span class="offline-status">Offline</span>
                @endif
            </li>
        @endforeach
    </ul>
</div>


    <script>
        // AJAX request to send message
        document.getElementById('message-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the form from submitting normally
            var form = event.target;
            var receiverId = form.querySelector('input[name="receiver_id"]').value;
            var message = form.querySelector('textarea[name="message"]').value;

            // Send AJAX request to the server
            var xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Request succeeded
                    form.querySelector('textarea[name="message"]').value = ''; // Clear the message input field
                    // You can update the received-messages and sent-messages lists here if needed
                } else {
                    // Request failed
                    console.error('Error sending message:', xhr.responseText);
                }
            };
            xhr.onerror = function() {
                console.error('Error sending message: Request failed');
            };
            xhr.send('receiver_id=' + encodeURIComponent(receiverId) + '&message=' + encodeURIComponent(message));
        });

                // Periodically update the received-messages list
        setInterval(function() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '/messages/received', true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Request succeeded
                    var receivedMessages = JSON.parse(xhr.responseText);
                    var receivedMessagesList = document.getElementById('received-messages');
                    receivedMessagesList.innerHTML = ''; // Clear the previous messages

                    receivedMessages.forEach(function(message) {
                        var listItem = document.createElement('li');
                        listItem.innerHTML = `
                            <strong>De:</strong> ${message.sender.name}
                            <p>${message.message}</p>
                            <p class="text-muted">${message.created_at}</p>
                        `;
                        receivedMessagesList.appendChild(listItem);
                    });
                } else {
                    // Request failed
                    console.error('Error updating received messages:', xhr.responseText);
                }
            };
            xhr.onerror = function() {
                console.error('Error updating received messages: Request failed');
            };
            xhr.send();
        }, 5000); // Update every 5 seconds
    </script>
@endsection

