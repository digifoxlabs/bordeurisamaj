@php($member = $member ?? null)
@php($v = fn($key, $fallback = '') => old($key, $member->$key ?? $fallback))
<div class="form-grid">
    <label class="field"><span>Name <small class="label-as">/ {{ __('membership.name') }}</small> <i>*</i></span><input
            name="name" value="{{ $v('name') }}" placeholder="Your full name" required maxlength="160"><small
            class="error-text">{{ $errors->first('name') }}</small></label>
    <label class="field"><span>Mobile number <small class="label-as">/ {{ __('membership.mobile') }}</small>
            <i>*</i></span><input id="mobile-field" name="mobile" value="{{ $v('mobile') }}"
            placeholder="10-digit mobile number" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" required><small
            id="mobile-status" class="hint">Enter your 10-digit number</small><small
            class="error-text">{{ $errors->first('mobile') }}</small></label>
    <label class="field"><span>WhatsApp number <small class="label-as">/
                {{ __('membership.whatsapp') }}</small></span><input name="whatsapp" value="{{ $v('whatsapp') }}"
            placeholder="WhatsApp number" inputmode="numeric" maxlength="10"><small
            class="error-text">{{ $errors->first('whatsapp') }}</small></label>
    <label class="field"><span>Email <small class="label-as">/ {{ __('membership.email') }}</small></span><input
            type="email" name="email" value="{{ $v('email') }}" placeholder="you@example.com"><small
            class="error-text">{{ $errors->first('email') }}</small></label>
    <fieldset class="field field-wide">
        <legend>Community role <small class="label-as">/ {{ __('membership.role') }}</small> <i>*</i></legend>
        <div class="role-options">@foreach(['Burha'=>'burha','Deka'=>'deka','Hota'=>'hota','Bidhipathak'=>'bidhipathak']
            as $role=>$key)<label class="role-choice"><input type="radio" name="role" value="{{ $role }}"
                    @checked($v('role')===$role) @required($loop->first)><span>{{ $role }}
                    <small>{{ __("membership.$key") }}</small></span></label>@endforeach</div><small
            class="error-text">{{ $errors->first('role') }}</small>
    </fieldset>
    <label class="field"><span>Father's name <small class="label-as">/
            {{ __('membership.father_name') }}</small> <i>*</i></span><input name="father_name"
            value="{{ $v('father_name') }}" placeholder="Father's full name" required maxlength="160"><small
            class="error-text">{{ $errors->first('father_name') }}</small></label>
    <label class="field"><span>Grandfather's name <small class="label-as">/
                {{ __('membership.grandfather_name') }}</small> <i>*</i></span><input name="grandfather_name"
            value="{{ $v('grandfather_name') }}" placeholder="Grandfather's full name" required maxlength="160"><small
            class="error-text">{{ $errors->first('grandfather_name') }}</small></label>
    <label class="field"><span>Date of birth <small class="label-as">/
                {{ __('membership.date_of_birth') }}</small></span><input type="date" name="date_of_birth"
            value="{{ $v('date_of_birth') }}" max="{{ now()->toDateString() }}"><small
            class="error-text">{{ $errors->first('date_of_birth') }}</small></label>
    <label class="field"><span>Occupation / designation <small class="label-as">/
                {{ __('membership.occupation') }}</small></span><input name="occupation" value="{{ $v('occupation') }}"
            placeholder="Your occupation or designation"><small
            class="error-text">{{ $errors->first('occupation') }}</small></label>
    <label class="field field-wide"><span>Address <small class="label-as">/
                {{ __('membership.address') }}</small></span><textarea name="address" rows="3"
            placeholder="House, village / locality, district">{{ $v('address') }}</textarea><small
            class="error-text">{{ $errors->first('address') }}</small></label>
    <div class="field field-wide"><span>Photo <small class="label-as">/ {{ __('membership.photo') }}</small></span>
        <div class="photo-tools">
            <div class="photo-preview" id="photo-preview">@if(old('photo_preview'))<img src="{{ old('photo_preview') }}"
                    alt="Selected member photo">@elseif($member?->photo)<img src="{{ Storage::url($member->photo) }}"
                    alt="Current member photo">@else<span>PHOTO</span>@endif</div>
            <div class="photo-actions">
                <div class="upload-actions"><label class="button button-soft" for="photo-input">Choose
                        photo</label><button class="button button-soft" type="button" id="camera-button">Use
                        camera</button><input id="photo-input" type="file" name="photo" accept="image/*"
                        class="sr-only"></div>
                <div class="camera-wrap hidden" id="camera-wrap"><video id="camera-video" autoplay
                        playsinline></video><button class="button button-soft" type="button" id="capture-photo">Capture
                        photo</button></div>
                <div id="crop-controls" class="crop-controls hidden"><label>Adjust crop <input id="crop-zoom"
                            type="range" min="1" max="3" step="0.05" value="1"></label><label>Horizontal <input
                            id="crop-x" type="range" min="0" max="100" value="50"></label><label>Vertical <input
                            id="crop-y" type="range" min="0" max="100" value="50"></label></div><small
                    class="hint">Square portrait · JPG, PNG or WebP · Max 5 MB</small>
            </div><input type="hidden" name="photo_preview" id="photo-preview-data" value="{{ old('photo_preview') }}">
        </div><small class="error-text">{{ $errors->first('photo') }}</small>
    </div>
    <div class="field field-wide document-fields">
        <button class="button button-soft document-toggle" type="button" aria-expanded="{{ old('show_documents') ? 'true' : 'false' }}">Upload documents <span>＋</span></button>
        <input type="hidden" name="show_documents" value="{{ old('show_documents') ? '1' : '' }}">
        <div class="document-choices {{ old('show_documents') ? '' : 'hidden' }}">
            @for($i = 0; $i < 2; $i++)
            <label class="field"><span>Document {{ $i + 1 }} name</span><input name="documents[{{ $i }}][title]" value="{{ old("documents.$i.title") }}" maxlength="160" placeholder="e.g. Identity proof"><input class="document-file" type="file" name="documents[{{ $i }}][file]" accept="*/*"><small class="error-text">{{ $errors->first("documents.$i.file") }}</small></label>
            @endfor
        </div>
    </div>
</div>
