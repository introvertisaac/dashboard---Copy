<div>
    @include('partials.alerts')
    <div class="flex items-center flex-wrap justify-between gap20 mb-5">
        <div class="mb-20">
            <h4>Reset your password</h4>
            <p>You can reset your password here</p>
        </div>
    </div>

    <form wire:submit.prevent="password_settings" class="form-login flex flex-column gap16 pt-2">
        <div class="row w-75">
            <div class="col-md-6 form-group">

                <fieldset class="password">
                    <div class="body-title mb-5">New Password</div>
                    <input class="password-input" wire:model="new_password" type="password" id="new_password"
                           placeholder="Please enter a new password" name="password" tabindex="0" value="" aria-required="true"
                           required="">
                    <span class="show-pass">
                                    <i class="icon-eye view"></i>
                                    <i class="icon-eye-off hide"></i>
                                </span>
                    @error('new_password') <span class="error text-danger d-block">{{ $message }}</span> @enderror
                </fieldset>


            </div>
            <div class="col-md-6 form-group">
                <fieldset class="password">
                    <div class="body-title mb-5">Confirm New Password</div>
                    <input class="password-input" wire:model="confirm_password" type="password" id="confirm_password"
                           placeholder="Please confirm the new password" name="password" tabindex="0" value="" aria-required="true"
                           required="">
                    <span class="show-pass">
                                    <i class="icon-eye view"></i>
                                    <i class="icon-eye-off hide"></i>
                                </span>
                    @error('confirm_password') <span class="error text-danger d-block">{{ $message }}</span> @enderror
                </fieldset>

            </div>
        </div>

        <div class="flex mt-2">
            <button type="submit" class="button">Reset Password</button>
        </div>
    </form>


</div>
