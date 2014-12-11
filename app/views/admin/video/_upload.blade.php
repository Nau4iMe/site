<script type="text/javascript" src="{{ URL::asset('js/flow.min.js') }}"></script>
<script type="text/javascript">
    $(function() {
        var flow = new Flow({
            target: '{{ URL::route('admin.content.video', $content->id) }}',
            query: { _token: '{{ csrf_token() }}' },
            singleFile: false
        });

        flow.assignBrowse($('#browse'), false, true);

        $('#upload').click(function() {
            flow.upload();
        });
        $('#pause').click(function() {
            flow.pause();
        });
        $('#resume').click(function() {
            flow.resume();
        });

        flow.on('fileAdded', function(file, event) {
            if (file.size > 209715200) {
                file.cancel();
                $('#uploader').append('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Затвори</span></button>Избраният файл е повече от 200МБ.</div>');
                return false;
            }
            if (file.getType() != 'mp4' && file.getExtension() != 'mp4') {
                file.cancel();
                $('#uploader').append('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Затвори</span></button>Избраният файл трябва да бъде в MP4 формат.</div>');
                return false;
            }
            $('#uploader').append(
                '<div class="file-upload file-' + file.uniqueIdentifier +
                '"><span class="file-info">' + file.name + ' - ' + readablizeBytes(file.size) +'</span>'+
                '<span class="btn btn-danger delete">изтрий</span></div>'
                // '<div class="progress-bar bar-'+file.uniqueIdentifier+'"></div>'
                );
            var self = $('.file-' + file.uniqueIdentifier);
            self.find('.delete').on('click', function () {
                file.cancel();
                self.remove();
                $('.bar-' + file.uniqueIdentifier).remove();
            });
        });

        flow.on('fileSuccess', function(file, message) {
            $('.file-' + file.uniqueIdentifier).append('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Затвори</span></button>'+
                'Видеото беше добавено успешно.</div>');
            getVideos("{{ URL::route('admin.video.user.get') }}", "{{ $content->id }}", "{{ csrf_token() }}", function(data) {
                var fill = '<table class="table table-hover"><th></th><th width="5%"></th>';
                $.each(data, function(i, video) {
                    fill += '<tr>';
                    fill += '<td><a target="_blank" href="{{ URL::to("/") }}/admin/video/user/'+ video.id +'">' + video.name + '</a></td>';
                    fill += '<td>';
                    fill += '<form method="post" action="{{ URL::to("/") }}/admin/video/ajax/user/'+ video.id +'" class="video-destroy-ajax">';
                    fill += '<input name="_method" type="hidden" value="DELETE">';
                    fill += '<input name="_token" type="hidden" value="{{ csrf_token() }}">';
                    fill += '{{ Form::submit("изтрий", array("class" => "btn btn-danger")) }}';
                    fill += '</form>';
                    fill += '</td>';
                    fill += '</tr>';
                });
                fill += '</table>';
                $('#videos').html(fill);
            });
        });

        flow.on('fileError', function(message) {
            $('#uploader').append('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Затвори</span></button>Грешка при добавяне на файл.</div>');
        });

        flow.on('fileProgress', function(file) {
            // Handle progress for both the file and the overall upload
            $('.file-'+file.uniqueIdentifier)
              .html('<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Затвори</span></button>' + Math.floor(file.progress()*100) + '% '
                + readablizeBytes(file.averageSpeed) + '/s '
                + secondsToStr(file.timeRemaining()) + ' remaining');
                $('.bar-' + file.uniqueIdentifier).css({width:Math.floor(flow.progress()*100) + '%</div>'});
        });
        function readablizeBytes(bytes) {
          var s = ['bytes', 'kB', 'MB', 'GB', 'TB', 'PB'];
          var e = Math.floor(Math.log(bytes) / Math.log(1024));
          return (bytes / Math.pow(1024, e)).toFixed(2) + " " + s[e];
        }
        function secondsToStr(temp) {
          function numberEnding(number) {
            return (number > 1) ? 's' : '';
          }
          var years = Math.floor(temp / 31536000);
          if (years) {
            return years + ' year' + numberEnding(years);
          }
          var days = Math.floor((temp %= 31536000) / 86400);
          if (days) {
            return days + ' day' + numberEnding(days);
          }
          var hours = Math.floor((temp %= 86400) / 3600);
          if (hours) {
            return hours + ' hour' + numberEnding(hours);
          }
          var minutes = Math.floor((temp %= 3600) / 60);
          if (minutes) {
            return minutes + ' minute' + numberEnding(minutes);
          }
          var seconds = temp % 60;
          return seconds + ' second' + numberEnding(seconds);
        }
    });
</script>

<h4>изберете файлове</h4>

<div class="form-group" id="uploader">
    <p class="alert alert-warning">
        Приема файлове само във формат MP4.<br/>
        Максимален размер на файл - 200МБ.<br/>
        Можете да качите най-много 10 видеа за един ден.
    </p>
    <div class="upload-form">
        {{ Form::open(array('route' => 'admin.content.video')) }}
        <span class="btn btn-default" id="browse">избери файлове</span>
        <span class="btn btn-success" id="upload">качи</span>
        <span class="btn btn-warning" id="pause">пауза</span>
        <span class="btn btn-info" id="resume">продължи</span>
        {{ Form::close() }}    
    </div>    
</div>

<hr />
