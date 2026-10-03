@extends('layouts.app')
@section('title','Membership registration')
@section('content')
<div class="page-shell"><div class="page-heading"><a href="{{ route('home') }}" class="back-link">← Back home</a><span class="section-kicker">REGISTRATION FORM</span><h4>Persons who complete 18 (eighteen) years of age may submit this form</h4><p>Fields marked <i>*</i> are required.</p></div>
@if($errors->any())<div class="alert alert-error">Please review the highlighted fields and try again.</div>@endif
<form id="member-form" class="surface form-surface" method="POST" action="{{ route('membership.preview') }}" enctype="multipart/form-data">@csrf
<div class="form-section-title"><span class="step-number">01</span><div><b>Personal information</b><small>Your details help us keep in touch.</small></div></div>
@include('frontend.fields')
<div class="form-footer"><span>🔒 Your information is kept private and secure.</span><button class="button button-primary" type="submit">Preview details <span>→</span></button></div></form></div>
@endsection
@push('scripts')
<script>
(()=>{const mobile=document.querySelector('#mobile-field'),status=document.querySelector('#mobile-status'),token=document.querySelector('meta[name="csrf-token"]').content;let timer;
mobile?.addEventListener('input',()=>{mobile.value=mobile.value.replace(/\D/g,'').slice(0,10);clearTimeout(timer);if(mobile.value.length<10){status.textContent='Enter your 10-digit number';status.className='hint';return}status.textContent='Checking…';status.className='hint';timer=setTimeout(async()=>{try{const r=await fetch('{{ route('membership.check-mobile') }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':token,'Accept':'application/json'},body:JSON.stringify({mobile:mobile.value})});const d=await r.json();status.textContent=d.available?'✓ Number is available':'✕ Number already exists';status.className=d.available?'hint success':'hint danger'}catch{status.textContent='Could not check number';status.className='hint danger'}},250)});
const input=document.querySelector('#photo-input'),preview=document.querySelector('#photo-preview'),controls=document.querySelector('#crop-controls'),zoom=document.querySelector('#crop-zoom'),cx=document.querySelector('#crop-x'),cy=document.querySelector('#crop-y'),camWrap=document.querySelector('#camera-wrap'),video=document.querySelector('#camera-video');let source=null,stream=null;
function showPhoto(blob){source=new Image();source.onload=()=>{preview.innerHTML='';preview.append(source);controls.classList.remove('hidden');drawCrop()};source.src=URL.createObjectURL(blob)}
function drawCrop(){if(!source)return;preview.style.setProperty('--crop-zoom',zoom.value);preview.style.setProperty('--crop-x',cx.value+'%');preview.style.setProperty('--crop-y',cy.value+'%')}
const existingPhoto=preview.querySelector('img');if(existingPhoto){source=new Image();source.onload=()=>{controls.classList.remove('hidden');drawCrop()};source.src=existingPhoto.src}
input?.addEventListener('change',()=>{if(input.files[0])showPhoto(input.files[0])});[zoom,cx,cy].forEach(el=>el.addEventListener('input',drawCrop));
document.querySelector('#camera-button')?.addEventListener('click',async()=>{try{stream=await navigator.mediaDevices.getUserMedia({video:{facingMode:'user'},audio:false});video.srcObject=stream;camWrap.classList.remove('hidden')}catch{alert('Camera access is unavailable. Please choose a photo instead.')}});
document.querySelector('#capture-photo')?.addEventListener('click',()=>{const c=document.createElement('canvas');c.width=video.videoWidth;c.height=video.videoHeight;c.getContext('2d').drawImage(video,0,0);c.toBlob(b=>showPhoto(b),'image/jpeg',.92);stream?.getTracks().forEach(t=>t.stop());camWrap.classList.add('hidden')});
document.querySelector('#member-form')?.addEventListener('submit',e=>{if(!source)return; e.preventDefault();const form=e.currentTarget,side=Math.min(source.naturalWidth,source.naturalHeight)/Number(zoom.value),left=(source.naturalWidth-side)*(Number(cx.value)/100),top=(source.naturalHeight-side)*(Number(cy.value)/100),c=document.createElement('canvas');c.width=640;c.height=640;c.getContext('2d').drawImage(source,left,top,side,side,0,0,640,640);c.toBlob(blob=>{const reader=new FileReader();reader.onload=()=>{document.querySelector('#photo-preview-data').value=reader.result;form.submit()};reader.readAsDataURL(blob)},'image/jpeg',.88)});
})();
</script>
@endpush
