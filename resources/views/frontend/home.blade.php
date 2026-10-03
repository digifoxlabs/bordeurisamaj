@extends('layouts.app')
@section('title','Kamakhya Devalaya | Membership')
@section('content')
<section class="hero-wrap"><div class="hero-orbit orbit-one"></div><div class="hero-orbit orbit-two"></div><div class="hero-content"><div class="eyebrow"><span class="eyebrow-dot"></span> OUR COMMUNITY, TOGETHER</div><div class="hero-seal">ॐ</div><h1>Bordeuri Samaj, Kamakhya Devalaya<br><em>Registration Form</em></h1><p class="hero-copy">Register Here</p><a class="button button-primary button-large" href="{{ route('membership.create') }}">Begin your registration <span>→</span></a><div class="hero-note"><span>✦</span> Takes just a few minutes · Your information stays with us</div></div><div class="hero-side"><div class="side-card"><div class="side-icon">✺</div><span>ONE COMMUNITY</span><b>A bond that<br>brings us closer.</b><div class="side-line"></div></div></div></section>
<section class="home-bottom"><div><span class="section-kicker">A SHARED HERITAGE</span><h2>Every member is part of our story.</h2></div><a href="{{ route('admin.login') }}" class="muted-link">Administrator access <span>↗</span></a></section>
@endsection
