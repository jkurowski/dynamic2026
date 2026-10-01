@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-6 pl-0">
                    <h4 class="page-title"><i class="fe-mail"></i><a href="{{route('admin.crm.clients.index')}}">Klienci</a><span class="d-inline-flex me-2 ms-2">/</span><a href="{{ route('admin.crm.clients.show', $client->id) }}">{{$client->name}}</a><span class="d-inline-flex me-2 ms-2">/</span>Wiadomości</h4>
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center form-group-submit"></div>
            </div>
        </div>
        @include('admin.crm.client.client_shared.menu')
        <div class="row">
            <div class="col-4">
                @include('admin.crm.client.client_shared.aside')
            </div>
            <div class="col-8">
                <div class="card mt-3">
                    <div class="card-body card-body-rem">
                        <div id="chat">
                            <div id="info"></div>
                            <div id="messages"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @routes('chat')
    @push('scripts')
        <script src="{{ mix('js/app.js') }}"></script>
        <script>
            const chat = $("#chat");

            chat.on('click', '.dropdown-item-replay', function(event){
                const target = event.target;
                const parent = target.closest(".chat-box");
                jQuery.ajax({
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'id': parent.dataset.msgId
                    },
                    url: route('admin.crm.clients.chat.form', {{$client->id}}),
                    success: function(response) {
                        if(response) {
                            document.querySelectorAll('.modal,.tox,.modal-script').forEach(el => el.remove());
                            $('body').append(response);
                        } else {
                            alert('Error');
                        }
                    }
                });
            });

            chat.on('click', '.dropdown-item-mark', function(event){
                const target = event.target;
                const parent = target.closest(".chat-box");
                jQuery.ajax({
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'id': parent.dataset.msgId
                    },
                    url: route('admin.crm.clients.chat.mark', {{$client->id}}),
                    success: function(response) {
                        const mark = parent.classList.contains('chat-mark');
                        if(mark){
                            parent.classList.remove('chat-mark');
                        } else {
                            parent.classList.add('chat-mark');
                        }
                    }
                });
            });

            const ClientMessage = ({ text, created, createdDate }) => `<div class="chat-box d-flex align-items-end float-end mb-4 flex-row-reverse"><div class="chat-text d-flex flex-wrap"><div class="chat-text-content w-100">${ text }</div></div></div><div class="clearfix"></div>`;

            const UserMessage = ({ text, created, createdDate}) => `<div class="chat-box d-flex align-items-end float-start mb-4"><div class="chat-text d-flex flex-wrap"><div class="chat-text-content w-100">${ text }</div></div></div><div class="clearfix"></div>`;

            document.addEventListener('DOMContentLoaded', function () {
                const messagesDiv = document.getElementById('messages');
                const messageInput = document.getElementById('messageInput');
                const messageForm = document.getElementById('messageForm');
                const infoDiv = document.getElementById('info');

                const clientId = @json($client->id);

                function appendMessage(message) {
                    const messageElement = document.createElement('div');
                    const text = message.message;
                    const created = message.created_at_diff;
                    const createdDate = message.created_date;

                    if (message.user_id === 0) {
                        messageElement.innerHTML = ClientMessage({ text, created, createdDate });
                    } else {
                        messageElement.innerHTML = UserMessage({ text, created, createdDate });
                    }
                    messagesDiv.insertBefore(messageElement, messagesDiv.firstChild);
                }

                // Fetch initial messages
                fetch('/admin/crm/clients/messages/{{ $client->id }}')
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(message => appendMessage(message));
                    });

                messageForm.addEventListener('submit', function (e) {
                    e.preventDefault(); // Prevent default form submission

                    const message = messageInput.value;

                    if (message) {
                        fetch('/admin/crm/clients/messages', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                client_id: clientId,
                                message: message,
                                source: "Chat"
                            })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'Message Sent!') {
                                    messageInput.value = '';
                                }
                            });
                    }
                });

                window.Echo.channel('public-notification-channel')
                    .listen('MessageSent', (e) => {
                        console.log('Message received: ', e);
                        appendMessage(e);
                    });

                window.Echo.connector.pusher.connection.bind('connected', () => {
                    infoDiv.innerHTML = `<div class="alert alert-primary" role="alert">WebSocket connection established</div>`;
                });

                window.Echo.connector.pusher.connection.bind('error', (error) => {
                    infoDiv.innerHTML = `<div class="alert alert-warning" role="alert">WebSocket connection error</div>`;
                });

                window.Echo.connector.pusher.connection.bind('disconnected', () => {
                    infoDiv.innerHTML = `<div class="alert alert-danger" role="alert">WebSocket connection closed</div>`;
                });
            });

        </script>
    @endpush
@endsection
