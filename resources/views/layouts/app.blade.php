<!doctype html>
<html lang="en" @class(['scroll-smooth'])>
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"><title>@yield('title', 'Kamakhya Devalaya')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Noto+Sans+Assamese:wght@400;500;600;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css','resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @endif
    <script>if(localStorage.getItem('kd-theme')==='dark')document.documentElement.classList.add('dark')</script>
</head>
<body @class(['min-h-screen', 'bg-[#f7f8fa]', 'text-slate-800', 'antialiased', 'dark:bg-[#0e1218]', 'dark:text-slate-100'])>
<header @class(['site-header'])><a href="{{ route('home') }}" @class(['brand'])><span @class(['brand-mark'])>ॐ</span><span><b>বড়দেউৰী সমাজ, কামাখ্যা দেৱালয়</b><small>প্ৰাপ্তবয়স্ক পুৰুষ সদস্য পঞ্জীয়ন প্ৰপত্ৰ</small></span></a><div @class(['header-actions'])><button @class(['theme-toggle']) type="button" aria-label="Toggle dark mode" onclick="document.documentElement.classList.toggle('dark');localStorage.setItem('kd-theme',document.documentElement.classList.contains('dark')?'dark':'light')"><span @class(['sun'])>☼</span><span @class(['moon'])>☾</span></button>@if(request()->is('admin*') && auth()->check())<a @class(['nav-link']) href="{{ route('admin.dashboard') }}">Dashboard</a><a @class(['nav-link']) href="{{ route('admin.documents.index') }}">Documents</a><a @class(['nav-link']) href="{{ route('admin.profile') }}">Profile</a><form method="POST" action="{{ route('admin.logout') }}">@csrf<button @class(['nav-link']) type="submit">Sign out</button></form>@else<a href="{{ route('home') }}" @class(['nav-link'])>Home</a>@endif</div></header>
<main>@yield('content')</main>
<footer @class(['site-footer'])><span>বড়দেউৰী সমাজ, কামাখ্যা দেৱালয়</span><span>Made by <a href="https://digifoxlabs.com">Digifoxlabs</a></span></footer>
@stack('scripts')
</body></html>
