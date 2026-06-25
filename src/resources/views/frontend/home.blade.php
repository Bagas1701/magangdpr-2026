@extends('frontend.layouts.app')

@section('title', 'SIMALEX - Aspirasi Konstituen Digital')
@section('body_class', 'simalex-home')

@section('content')
    <main class="simalex-shell">
        <div id="simalexTrack" class="simalex-track">
            @include('frontend.partials.hero')
            @include('frontend.partials.profil')
            @include('frontend.partials.dashboard')
            @include('frontend.partials.workflow')
            @include('frontend.partials.tracking')
            @include('frontend.partials.contact')
        </div>
    </main>
    
<div class="simalex-panel-indicator">
    <button type="button" class="active" data-slide="0">
        <span>01</span>
        Beranda
    </button>

    <button type="button" data-slide="1">
        <span>02</span>
        Fitur
    </button>

    <button type="button" data-slide="2">
        <span>03</span>
        Workflow
    </button>

    <button type="button" data-slide="3">
        <span>04</span>
        Dashboard
    </button>



    <button type="button" data-slide="4">
        <span>05</span>
        Cek Aspirasi
    </button>

    <button type="button" data-slide="5">
        <span>06</span>
        Kontak
    </button>
</div>

<div class="simalex-panel-control">
    <button type="button" id="simalexPrev">
        <i class="lni lni-arrow-left"></i>
    </button>

    <button type="button" id="simalexNext">
        <i class="lni lni-arrow-right"></i>
    </button>
</div>
@endsection