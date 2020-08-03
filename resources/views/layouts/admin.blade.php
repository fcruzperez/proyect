<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
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
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="{{asset('css/sticky-footer-navbar.css')}}">
</head>
<body class="d-flex flex-column h-100" style="font-family: Arial, Helvetica, sans-serif">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/admin/dashboard') }}">
                    <img src="{{asset('images/emb-icon2.png')}}" width="50" height="50" class="mr-3">
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
                                <a class="nav-link" href="{{ route('admin.home') }}">{{ __('Home') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.settings') }}">{{ __('Setting') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.score') }}">{{ __('Score') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.register') }}">{{ __('Add User') }}</a>
                            </li>
                            <!-- finance menu -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
                                    Finance
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" id="messageList" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{url("/admin/withdraw-list")}}">
                                        Withdraw
                                    </a>
                                    <a class="dropdown-item" href="{{url("/admin/deposit-list")}}">
                                        Deposit
                                    </a>
                                </div>
                            </li>

                            @php
                                $userId = \Illuminate\Support\Facades\Auth::id();
                                $user = \App\Models\User::find($userId);
                                $messages = $user->messages()->where('status', 'unread')->get();
                            @endphp
                            <li class="nav-item dropdown">
                                <a id="messageDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
                                    <span class="fa fa-bell"></span>
                                    <span class="badge badge-pill badge-danger"
                                          id="messageBadge" data-count="{{count($messages)}}">
                                        {{count($messages)}}
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" id="messageList" aria-labelledby="navbarDropdown">
                                    @foreach($messages as $msg)
                                        <a class="dropdown-item" href="{{$msg->action_url}}">
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

        <footer class="footer mt-auto py-3 w-100">
            <div class="container">
                <span class="text-muted">Place sticky footer content here.</span>
            </div>
        </footer>
    </div>

    <style>
        #messageDropdown:after {
            display: none;
        }
    </style>
    @yield('stylesheet')

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
{{--    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://js.pusher.com/5.1/pusher.min.js"></script>

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
        var channel = pusher.subscribe('admin-channel');

        // Bind a function to a Event (the full Laravel class)
        channel.bind('App\\Events\\AdminEvent', function(data) {
            var payload = data.payload;
            if(payload.user_id === userId) {
                console.log(payload)
                messageCount++;
                messageBadge.attr('data-count', messageCount);
                messageBadge.text(messageCount);
                messageBadge.show();
                var newMessage = `<a class="dropdown-item" href="${payload.action_url}">${payload.message}</a>`;
                var old = messageList.html();
                messageList.html(newMessage + old);
            }
        });

        // $('a').click(function(e) {
        //     // uncomment the following line to prove it
        //     // e.preventDefault();
        //     $(this).closest('ul').find('li').removeClass('active');
        //     $(this).closest('li').addClass('active');
        // })

    </script>


    @yield('js')


</body>
</html>
