<div class="tab-pane fade" id="web-properties" role="tabpanel" aria-labelledby="web-properties-tab">
    <form action="{{ route('settings.update') }}" method="POST" role="form"
          enctype="multipart/form-data">
        @method('PATCH')
        @csrf
        <input type="hidden" name="site_logo">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="">{{ __('Logo Website (Header)') }}</label><br>
                    @empty($settings->logowebsite)
                        <img id="image_logowebsite" src="{{ asset('themes/magz/images/logo.png') }}" alt="" class="border mb-3 col-12 col-md-6" style="max-width:380px;max-height:400px">
                    @endempty
                    <img id="image_preview_logowebsite" src="{{ $logowebsite }}" alt="" class="border mb-3 col-12 col-md-6" style="max-width:380px;max-height:400px">
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="logowebsite" class="custom-file-input" value="{{ $settings->logowebsite }}" onchange="readImage(this)">
                            <label class="custom-file-label">{{ __('Choose file') }}</label>
                        </div>
                    </div>
                    <p>
                        <small>
                            {{ __('Browse file File format must be in the format jpg, jpeg, png,
                            and the size 762x242') }}<br>
                        </small>
                    </p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="">{{ __('Logo Website (Footer)') }}</label><br>
                    @empty($settings->logowebsite_footer)
                        <img id="image_logowebsite_footer" src="{{ asset('themes/magz/images/logo-light.png') }}" alt="" class="border mb-3 col-12 col-md-6" style="max-width:380px;max-height:400px">
                    @endempty
                    <img id="image_preview_logowebsite_footer" src="{{ $logowebsite_footer }}" alt="" class="border mb-3 col-12 col-md-6" style="max-width:380px;max-height:400px">
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="logowebsite_footer" class="custom-file-input" value="{{ $settings->logowebsite_footer }}" onchange="readImage(this)">
                            <label class="custom-file-label">{{ __('Choose file') }}</label>
                        </div>
                    </div>
                    <p>
                        <small>
                            {{ __('Browse file File format must be in the format jpg, jpeg, png,
                            and the size 762x242') }}<br>
                        </small>
                    </p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="">{{ __('Favicon') }}</label><br>
                    @empty($settings->favicon)
                        <img id="image_favicon" src="{{ asset('favicons/favicon-96x96.png') }}" alt="" class="border mb-3" style="width:100px;max-width:100px;max-height:100px">
                    @endempty
                    <img id="image_preview_favicon" src="{{ $favicon }}" alt="" class="border mb-3" style="width:100px;max-width:100px;max-height:100px">
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="favicon" class="custom-file-input" value="{{ $settings->favicon }}" accept="image/x-png,image/jpeg, image/x-icon" onchange="readImage(this)">
                            <label class="custom-file-label">{{ __('Choose file') }}</label>
                        </div>
                    </div>
                    <p>
                        <small>
                            {{ __('Browse file
                            File format must be in the format jpg, jpeg, ico ,png and the
                            max size 256x256px.') }}
                        </small>
                    </p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="">{{ __('Open Graph Image') }}</label><br>
                    @empty($settings->ogimage)
                        <img id="image_ogimage" src="{{ asset('img/cover.png') }}" alt="" class="border mb-3" style="width:250px;max-width:250px;max-height:250px">
                    @endempty
                    <img id="image_preview_ogimage" src="{{ $ogimage }}" alt="" class="border mb-3" style="width:250px;max-width:250px;max-height:250px">
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="ogimage" class="custom-file-input" value="{{ $settings->ogimage }}" onchange="readImage(this)">
                            <label class="custom-file-label">{{ __('Choose file') }}</label>
                        </div>
                    </div>
                    <p>
                        <small>
                            {{ __('Browse file File format must be in the format jpg, jpeg, png,
                            and the max size 1484x1200px') }}<br>
                        </small>
                    </p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="">{{ __('Dashboard Logo') }}</label><br>
                    @empty($settings->logodashboard)
                        <img id="image_logodashboard" src="{{ asset('img/logo.png') }}" alt="" class="border mb-3" style="width:100px;max-width:100px;max-height:100px">
                    @endempty
                    <img id="image_preview_logodashboard" src="{{ $logodashboard }}" alt="" class="border mb-3" style="width:100px;max-width:100px;max-height:100px">
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="logodashboard" class="custom-file-input" value="{{ $settings->logodashboard }}" onchange="readImage(this)">
                            <label class="custom-file-label">{{ __('Choose file') }}</label>
                        </div>
                    </div>
                    <p>
                        <small>
                            {{ __('Browse file File format must be in the format jpg, jpeg, png,
                            and recomended size 100px') }}<br>
                        </small>
                    </p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="">{{ __('Auth Logo') }}</label><br>
                    @empty($settings->logoauth)
                        <img id="image_logoauth" src="{{ asset('img/logo-auth.png') }}" alt="" class="border mb-3" style="width:100px;max-width:100px;max-height:100px">
                    @endempty
                    <img id="image_preview_logoauth" src="{{ $logoauth }}" alt="" class="border mb-3" style="width:100px;max-width:100px;max-height:100px">
                    <div class="input-group">
                        <div class="custom-file">
                            <input id="uploadLogoAuth" type="file" name="logoauth" class="custom-file-input" value="{{ $settings->logoauth }}" onchange="readImage(this)">
                            <label class="custom-file-label">{{ __('Choose file') }}</label>
                        </div>
                    </div>
                    <p>
                        <small>
                            {{ __('Browse file File format must be in the format jpg, jpeg, png,
                            and recomended size 100px') }}<br>
                        </small>
                    </p>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-info float-right">{{ __('Save') }}</button>
        </div>
    </form>
</div>
