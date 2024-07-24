<div>
    <div class="text-center">
        <span class="login-title d-block tf-color">LOGIN</span>
    </div>
    <form wire:submit.prevent="login" class="form-login flex flex-column gap16 pt-2">
        @include('partials.alerts')


        <fieldset class="email">
            <div class="body-title mb-5">Username</div>
            <input class="flex-grow form-control" type="email" id="username" wire:model="username"
                   placeholder="Please enter the username" name="email" tabindex="0" value="" aria-required="true"
                   required="">
            @error('username') <span class="error text-danger d-block">{{ $message }}</span> @enderror
        </fieldset>


        <fieldset class="password">
            <div class="body-title mb-5">Password</div>
            <input class="password-input" wire:model="password" type="password" id="password"
                   placeholder="Please enter the password" name="password" tabindex="0" value="" aria-required="true"
                   required="">
            <span class="show-pass">
                                    <i class="icon-eye view"></i>
                                    <i class="icon-eye-off hide"></i>
                                </span>
            @error('password') <span class="error text-danger d-block">{{ $message }}</span> @enderror
        </fieldset>
        <div class="flex justify-end items-end my-1">
            <span class="body-text bold">Forgot password? </span> <a href="#" class="body-text bold tf-color ml-6">
                Reset Password</a>
        </div>
        <div class="flex justify-center mt-10">
            <button type="submit" class="button">Login</button>
        </div>
    </form>

</div>
