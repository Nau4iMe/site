<div id="videos">
@if (count($videos))
    <table class="table table-hover">
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th width="5%"></th>
            </tr>
        </thead>
    @foreach($videos as $video)
        <tr>
            <td>
                <a target="_blank" href="{{ URL::route('admin.video.edit', $video->id) }}" >
                    {{ $video->name }}
                </a>
            </td>
            <td>
                <a target="_blank" href="{{ URL::route('admin.video.edit', $video->id) }}" >
                    {{ $video->youtube }}
                </a>
            </td>
            <td>
                {{ Form::open(array(
                    'route' => array('admin.video.destroy.ajax.user', $video->id),
                    'method' => 'delete',
                    'class' => 'video-destroy-ajax'
                ))}}
                {{ Form::submit('изтрий', array('class' => 'btn btn-danger')) }}
                {{ Form::close() }}
            </td>
        </tr>
    @endforeach
    </table>
@endif
</div>
