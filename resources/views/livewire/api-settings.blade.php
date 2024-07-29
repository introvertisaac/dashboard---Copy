<div>

    @include('partials.alerts')
    <div class="flex items-center flex-wrap justify-between gap20 mb-3">
        <div class="mb-20">
            <h4>API Credentials</h4>
            <p>Set up your integrations using the secret key below</p>
        </div>

        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10 d-none"><a wire:click="generate()"
                                                                                          {{--href="{{route('customers.create')}}"--}}
                                                                                          class="button w-full">Generate
                New
                Credentials</a>
        </ul>
    </div>


    <form wire:submit.prevent="api_settings" class="form-login flex flex-column gap16 pt-2">
        <div class="row w-75">
            <div class="col-md-6 form-group">


                <fieldset class="password">
                    <div class="body-title mb-5">API Client ID</div>
                    <span class="d-none" id="api_client_id">{{$api_client_id}}</span>
                    <input readonly class="password-input" wire:model="api_client_id" type="text"
                           placeholder="" name="api_client_id" tabindex="0" value=""
                           aria-required="true"
                           required="">
                    <span title="Copy to Clipboard" data-target="#api_client_id"
                          class="input-icon text-dark clipboard_copy">
                        <i class="icon-copy"></i>
                    </span>
                    @error('api_client_id') <span class="error text-danger d-block">{{ $message }}</span> @enderror
                </fieldset>


            </div>


            <div class="col-md-6 form-group">
                <fieldset class="password">
                    <span class="d-none" id="api_secret">{{$api_secret}}</span>
                    <div class="body-title mb-5">API Secret Key</div>
                    <input readonly class="password-input" wire:model="api_secret" type="password"
                           placeholder="" name="api_secret" tabindex="0" value=""
                           aria-required="true"
                           required="">
                    <span title="Copy to Clipboard" data-target="#api_secret"
                          class="input-icon text-dark clipboard_copy">
                        <i class="icon-copy"></i>
                    </span>
                    @error('api_secret') <span class="error text-danger d-block">{{ $message }}</span> @enderror

                </fieldset>

            </div>
        </div>


        <div class="flex items-center flex-wrap justify-between gap10 mt-5">
            <div>
                <h4>API Documentation</h4>
                <p>Explore our API documentation on how to use our APIs</p>
            </div>
        </div>


        <div class="row w-75">
            <div class="form-group">
                <fieldset class="password">
                    <span><a target="_blank" href="{{route('scramble.docs.ui')}}">{{route('scramble.docs.ui')}}</a></span>
                </fieldset>


            </div>
        </div>


    </form>


</div>

