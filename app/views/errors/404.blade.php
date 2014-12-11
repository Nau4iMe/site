@extends('layouts.public._template')

@section('main')
<div class="container-fluid" id="error404">
    <p>
        A problem has been detected and the site has been shut down to prevent damage to your computer.
    </p>
    <p>
        The problem seems to be cause by the following file: <strong>{{ strip_tags(urldecode($url)) }}</strong>
    </p>
    <p>
        PAGE NOT FOUND 404
    </p>
    <p>
        If this is the first time you've seen this Stop error screen, restart your computer.
        If this screen appears again, follow these steps:
    </p>
    <p>
        Check to make sure any new hardware of software is properly installed. If this is a new installation,
        ask your hardware or software manufacturer for any windows updates you might need.
    </p>
    <p>
        If the problems continue, disable or remove any newly installed hardware or software.
        Disable BIOS memory options such as caching or shadowing.
        If you need to use Safe Mode to remove or disable components, restart your computer,
        press F8 to select Advanced Startup Options, and then select Safe Mode.
    </p>
    <p>
        Technical information:
    </p>
    <p>
        *** Stop 0x000000050 (0xFD3989C2, 0x0000000001, )xFBFB7658, 0x00000000)
    </p>
    <p>
        *** SPCMDCON.SYS. Address FBFB7658 base at FBFE50000, DataStamp 3d6dd67c
    </p>
</div>
@stop
