<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Научи ме! Вход към профил</title>
    <!-- Cascase Style Sheets -->
    <!-- <link href="{{ URL::asset('css/reset.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('css/main.css') }}" rel="stylesheet" type="text/css"> -->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link href="{{ URL::asset('css/backend.css') }}" rel="stylesheet" type="text/css">
    <!-- <link rel="stylesheet" href="{{ URL::asset('css/common.css') }}"> -->
</head>
<body>
    <div class="container">
    @if (isset($global_error))
    <div class="alert alert-danger text-center">{{ $global_error }}</div>
    @endif
        {{ Form::open(array('class' => 'form-signin', 'role' => 'form')) }}
        <h2 class="form-signin-heading">вход към профил</h2>
        {{ Form::text('username', null, array('id' => 'user', 'required', 'class' => 'form-control',
                                            'placeholder' => 'име')) }}
        {{ Form::password('password', array('required', 'class' => 'form-control', 'placeholder' => 'парола')) }}
        {{ Form::submit('влез', array('class' => 'btn btn-lg btn-primary btn-block')) }}

        <hr>

        <a href="{{ URL::to('/forum/index.php?action=reminder') }}" class="btn btn-info">забравена парола?</a>
        <a href="{{ URL::to('/forum/index.php?action=register') }}" class="btn btn-success">регистрация</a>

        {{ Form::close() }}
      </form>
    </div>
</body>
</html>