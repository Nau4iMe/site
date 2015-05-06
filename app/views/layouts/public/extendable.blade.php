@extends('layouts.public._template')

@section('main')
    <!-- Left Section  -->
    <div type="button" data-target="#toggleLeft" data-toggle="collapse" class="toggle-menu" id="left-toggle">
        <span>Покажи всички категории</span>
        <span class="fa fa-bars"></span>
    </div>
    <div class="col-md-2 left-menu" id="toggleLeft">
        @if (isset($categories))
            {{ HTML::nav($categories, null, $active) }}
        @endif
    </div>
    <!-- End of Left Section  -->

    <!-- Main Section -->
    <div class="col-md-8">
        @yield('content')
    </div>
    <!-- End of Main Section -->

    <!-- Rigth Section -->
    <div class="col-md-2">
        @if (isset($links))
            {{ HTML::nav($links, null, $active) }}
        @endif
    </div>
    <!-- End of Right Section -->
@stop