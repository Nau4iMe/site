@extends('layouts.public.extendable')

@section('content')
    <ol class="breadcrumb">
        <li>~$</li>
        <li>{{ HTML::link('/', 'Начало') }}</li>
        <li>Резултати</li>
        <li>{{ Input::get('find') }}</li>
    </ol>
    @if (count($result))
    <table class="table">
        <thead>
            <tr>
                <th>Намерени Уроци</th>
            </tr>
        </thead>
        @foreach ($result as $v)
        <tr>
            <td><div class="content-block">{{ HTML::link_to_content($v) }}</div></td>
        </tr>
        @endforeach
    </table>
    <div class="text-center">
        {{ $result->links() }}
    </div>
    @else
        <div class="alert alert-danger">
            <span class="glyphicon glyphicon-warning-sign"></span>
            Няма намерени уроци по зададената критерия: <strong>{{ Input::get('find') }}</strong>
        </div>
    @endif
@stop