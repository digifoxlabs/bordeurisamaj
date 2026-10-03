@extends('layouts.app')
@section('title','Registration complete')
@section('content')
<div class="success-wrap"><div class="success-card surface"><div class="success-mark">✓</div><span class="section-kicker">REGISTRATION COMPLETE</span><h1>Thank you, {{ $member->name }}.</h1><p>Your membership information has been received. We’re glad to have you as part of the Kamakhya Devalaya community.</p><div class="reference-number"><small>YOUR MOBILE NUMBER</small><b>{{ $member->mobile }}</b></div><a class="button button-primary" href="{{ route('home') }}">Return to home <span>→</span></a></div></div>
@endsection
