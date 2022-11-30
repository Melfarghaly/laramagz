<div class="tab-pane fade" id="web-config" role="tabpanel" aria-labelledby="web-config-tab">
    <form id="form-web-config" action="{{ route('settings.update') }}" method="POST" role="form" enctype="multipart/form-data">
        @method('PATCH')
        @csrf
        <input type="hidden" name="web_config">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label>{{ __('Google Analytics ID') }}</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fab fa-google"></i></span>
                        </div>
                        <input type="text" name="googleanalyticsid" class="form-control" placeholder="UA-45868728-1" value="{{ $settings->googleanalyticsid }}">
                        <div class="msg-googleanalyticsid"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">{{ __('Publisher ID') }}</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-bullhorn"></i></span>
                        </div>
                        <input type="text" name="publisherid" class="form-control" placeholder="ca-pub-969333888777222111" value="{{ $settings->publisherid }}">
                        <div class="msg-publisherid"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">{{ __('Disqus Short Name') }}</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-comments"></i></span>
                        </div>
                        <input type="text" name="disqusshortname" class="form-control" placeholder="{{ __('Your website shortname') }}" value="{{ $settings->disqusshortname }}">
                        <div class="msg-disqusshortname"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>{{ __('Analytics View ID') }}</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-chart-line"></i></span>
                        </div>
                        <input type="text" name="analytics_view_id" class="form-control" placeholder="223111254" value="{{ $analytics_view_id }}">
                        <div class="msg-googleanalyticsid"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{ __('Credentials File') }}</label><br>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="credentials_file" class="custom-file-input" id="credentialFile" value="{{ $settings->credentials_file }}">
                            <label class="custom-file-label" for="credentialFile">{{ __('Choose File') }}</label>
                        </div>
                    </div>
                    <p>
                        <small>
                            {{ __('Browse service account credentials json file') }}<br>
                        </small>
                    </p>
                </div>
                <div class="form-group">
                    <label>{{ __('GOOGLE MAP CODE') }}</label>
                    <textarea name="googlemapcode" class="form-control" rows="5" placeholder="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3972.07212464098!2d105.2985505143532!3d-5.40598465551376!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e40db829b6498f7%3A0x2846d50abe54ac6e!2sSigerweb!5e0!3m2!1sid!2sid!4v1582281377731!5m2!1sid!2sid">{{ $settings->googlemapcode }}</textarea>
                    <div class="msg-googlemapcode"></div>
                </div>
            </div>
        </div>
    <div class="row mt-3">
        <div class="col-lg-12">
            <button id="submit-web-config" type="submit" class="btn btn-info float-right">{{ __('Save') }}</button>
        </div>
    </div>
    </form>
    <hr>
    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="form-group">
                <label>{{ __('Maintenance mode') }}</label>
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="maintenance"
                           class="custom-control-input" data-id="" id="customSwitch1" {{ $check }}>
                    <label class="custom-control-label" for="customSwitch1">
                        {{ __('Click to activate or deactivate Maintenance Mode on the Website') }}
                    </label>
                </div>
            </div>
            @can('register-member')
            <div class="form-group">
                <label>{{ __('Register Member') }}</label>
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="maintenance" class="custom-control-input" data-id="" id="customSwitch2" {{ $register }}>
                    <label class="custom-control-label" for="customSwitch2">
                        {{ __('Click to activate or deactivate Register Member') }}
                    </label>
                </div>
            </div>
            @endcan
        </div>
    </div>
</div>
