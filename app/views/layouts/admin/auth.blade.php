<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Научи ме! Администрация</title>
    <!-- Cascase Style Sheets -->
    <!-- <link href="{{ URL::asset('css/reset.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('css/main.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('css/backend.css') }}" rel="stylesheet" type="text/css"> -->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ URL::asset('css/backend.css') }}">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css"> -->
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

    <script type="text/javascript" src="{{ URL::asset('js/jquery-2.1.0.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/backend.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('packages/ckeditor/ckeditor.js') }}"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container-fluid">
        @if (Session::has('global_error'))
            <div class="alert alert-danger">{{ Session::get('global_error') }}</div>
        @endif
        @if (Session::has('global_success'))
            <div class="alert alert-success">{{ Session::get('global_success') }}</div>
        @endif
        <header>     
            <nav>
                <ul class="nav navbar-nav navbar-right">
                    <li><i class="fa fa-user"></i></li>
                    <li><strong>{{ Auth::user()->member_name }}</strong>.</li>
                    <li><span class="badge">0</span></li>
                </ul>
                <ul class="nav nav-tabs text-uppercase" role="tablist" role="tablist">
                    <li>
                        <a href="{{ URL::to('/') }}" id="home"><img src="{{ URL::asset('img/home.png') }}" alt=""></a>
                    </li>
                    <li {{ isset($active) && $active == 'home' ? 'class="active"' : null }}>
                        <a href="{{ URL::route('admin') }}">Начало</a>
                    </li>

                    {{-- Grant user access to modules if they have enough posts --}}
                    @if (!Session::get('is_admin') && Auth::user()->posts >= User::$enough_posts)
                    <li {{ isset($active) && $active == 'content' ? 'class="active"' : null }}>
                        <a href="{{ URL::route('admin.content.user.create') }}">Добави урок</a>
                        <ul>
                            <li><a href="{{ URL::route('admin.content.user.index') }}">Моите уроци</a></li>
                        </ul>
                    </li>
                    <li {{ isset($active) && $active == 'video' ? 'class="active"' : null }}>
                        <a href="{{ URL::route('admin.video.user.index') }}">Видео</a>
                        <ul>
                            <li><a href="{{ URL::route('admin.video.user.index') }}">Моето видео</a></li>
                        </ul>
                    </li>
                    @endif

                    {{-- Admins only --}}
                    @if (Session::get('is_admin'))
                    <li  {{ isset($active) && $active == 'category' ? 'class="active"' : null }}>
                        <a href="{{ URL::route('admin.category.index') }}">Категории</a>
                        <ul>
                            <li><a href="{{ URL::to('/admin/category/create') }}">добавяне на нова категория</a></li>
                        </ul>
                    </li>
                    <li {{ isset($active) && $active == 'content' ? 'class="active"' : null }}>
                        <a href="{{ URL::route('admin.content.index') }}">Уроци</a>
                        <ul>
                            <li><a href="{{ URL::route('admin.content.create') }}">Добавяне на нов урок</a></li>
                        </ul>
                    </li>
                    <li {{ isset($active) && $active == 'video' ? 'class="active"' : null }}>
                        <a href="{{ URL::route('admin.video.index') }}">Видео</a>
                    </li>
                    <li  {{ isset($active) && $active == 'ban' ? 'class="active"' : null }}>
                        <a href="{{ URL::route('admin.ban.index') }}">Бан</a>
                        <ul>
                            <li><a href="{{ URL::route('admin.ban.create') }}">Добавяне</a></li>
                        </ul>
                    </li>
                    @endif
                    <li><a class="fa fa-power-off" href="{{ URL::route('admin.logout') }}" title="изход"></a></li>
                </ul>
            </nav>
        </header>
    </div>
    <div class="container-fluid" id="main">
        <div class="col-lg-12">
            @yield('content')
        </div>
    </div>
    @include('layouts.public._footer')
</body>
</html>