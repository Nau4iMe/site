@extends('layouts.admin.auth')

@section('content')

@if(count($bans))
    <table class="table table-hover">
        <thead>
            <tr>
                <th width="10%">потребител</th>
                <th>причина</th>
                <th width="10%"></th>
            </tr>
        </thead>
    @foreach ($bans as $ban)
        <tr>
            <td>{{ $ban->member_name }}</td>
            <td>{{ $ban->reason }}</td>
            <td>
                {{ Form::open(array('route' => array('admin.ban.destroy', $ban->id), 'method' => 'delete')) }}
                {{ Form::submit('премахни наказание', array('class' => 'btn btn-danger')) }}
                {{ Form::close() }}
            </td>
        </tr>        
    @endforeach        
    </table>
@else
    <p class="alert alert-info text-center">Няма потребители с наложени забрани.</p>
@endif
<div class="text-center">
    {{ $bans->links() }} 
</div>
@stop