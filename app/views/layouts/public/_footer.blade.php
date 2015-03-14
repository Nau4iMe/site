<footer class="footer container text-center">
    <ul class="list-inline">
        <li><a href="{{ URL::to('/') }}" title="Научи ме!">nau4i.me</a></li>
        <li><a href="{{ URL::to('/') . '/forum' }}" title="Форум" target="_blank">форум</a></li>
        <li><a href="{{ urldecode(URL::route('about'))  }}" title="Форум">за нас</a></li>
        <li><a href="{{ urldecode(URL::route('materials')) }}" title="Материали">материали</a></li>
        <li>&copy; 2009 - {{ date('Y') }}</li>
    </ul>
</footer>