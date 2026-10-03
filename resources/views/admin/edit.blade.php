@extends('layouts.app')
@section('title','Edit '.$member->name)
@section('content')
<div class="page-shell"><div class="page-heading"><a href="{{ route('admin.members.show',$member) }}" class="back-link">← Member record</a><span class="section-kicker">EDIT MEMBER</span><h1>Update details.</h1><p>Changes are saved directly to the membership register.</p></div><form class="surface form-surface" method="POST" action="{{ route('admin.members.update',$member) }}" enctype="multipart/form-data">@csrf @method('PUT')@include('frontend.fields')<div class="form-footer"><span>Member #{{ $member->id }}</span><button class="button button-primary">Save changes <span>→</span></button></div></form></div>
@endsection
@push('scripts')<script>const f=document.querySelector('#photo-input'),p=document.querySelector('#photo-preview');if(f)f.addEventListener('change',()=>{if(f.files[0]){p.innerHTML='';const i=document.createElement('img');i.src=URL.createObjectURL(f.files[0]);p.append(i)}});</script>@endpush
