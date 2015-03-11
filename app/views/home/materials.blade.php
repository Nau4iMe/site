@extends('layouts.public.extendable')

@section('content')
<ol class="breadcrumb">
    <li><i class="fa fa-terminal"></i></li>
    <li>{{ HTML::link('/', 'Начало') }}</li>
    <li>Материали</li>
</ol>

<h3><a href="#">Материали</a></h3>
<p>
    Всички материали в този сайт са публикувани под
    <a href="http://creativecommons.org/licenses/by-nc-nd/2.5/deed.bg" target="_blank">
        Creative Commons - Attribution Non-Commercial
    </a> лиценз, освен ако изрично не е посочен друг.
</p>
<p>
    Уроците са правени от <strong>непрофесионалисти, на добра воля и желание</strong>.
</p>
<p>
    Сайтът не носи отговорност за качеството на предоставените материали.
    Можете да използвате системата за гласуване или да дадете своето мнение или препоръка
    <a href="http://nau4i.me/forum" target="_blank">във форума</a> за подобрение на съдържанието.
</p>
@stop