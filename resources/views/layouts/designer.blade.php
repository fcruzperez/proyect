<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
{{--    <script src="{{ asset('js/app.js') }}" defer></script>--}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/designer/home') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('designer.home')}}">My Offers</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('designer.posts')}}">Requests</a>
                            </li>
                            @php
                                $userId = \Illuminate\Support\Facades\Auth::id();
                                $user = \App\Models\User::find($userId);
                                $messages = $user->messages()->where('status', 'unread')->get();
                                @endphp
                            <li class="nav-item dropdown">
                                <a id="notification-alert" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
                                    <span class="glyphicon glyphicon-bell">Messages</span>
                                    <span class="badge badge-pill badge-danger"
                                          id="messageBadge" data-count="{{count($messages)}}">
                                        {{count($messages)}}
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" id="messageList" aria-labelledby="navbarDropdown">
                                    @foreach($messages as $msg)
                                        <a href="{{url('/designer/offer-detail/'.$msg->offer_id)}}">
                                            {{$msg->subject}} {{--$msg->content--}}
                                        </a>
                                    @endforeach
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    @yield('stylesheet')

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://js.pusher.com/5.1/pusher.min.js"></script>

    @auth
    <script>
        var userId = {!! \Illuminate\Support\Facades\Auth::id() !!}
        var messageBadge   = $('#messageBadge');
        var messageList   = $('#messageList');
        var messageCount   = parseInt(messageBadge.data('count'));

        if (messageCount <= 0) {
            messageBadge.hide();
        }

        var pusher = new Pusher('f8714cee15893f9d7764', {
            encrypted: true
        });

        // Subscribe to the channel we specified in our Laravel Event
        var channel = pusher.subscribe('proposal-accepted');

        // Bind a function to a Event (the full Laravel class)
        channel.bind('App\\Events\\ProposalAccepted', function(data) {
            if(data.user_id === userId) {
                console.log(data)
                messageCount++;
                messageBadge.attr('data-count', messageCount);
                messageBadge.text(messageCount);
                messageBadge.show();
                var newMessage = `<a href="/designer/offer-detail/${data.offer_id}">You have new order!</a>`
                messageList.prepend(newMessage);
            }
        });

    </script>
    @endauth

    @yield('js')

</body>
</html>
