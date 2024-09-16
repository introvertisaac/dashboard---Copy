<div>
    <div class="text-center mb-4">
        <span class="login-title d-block tf-color">Reset Password</span>
    </div>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="sendResetLink">

        <p>Please enter your username below to continue with the password reset process</p>

        <br>
        <div class="form-group">
            <div class="body-title mb-5 mt-3">Your Email</div>
            <input type="email" wire:model="email" id="email" class="form-control @error('email') is-invalid @enderror">
            @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>

        <br>
        <div class="flex justify-center mt-10 ">
            <button type="submit" class="button">Submit</button>
        </div>

    </form>
</div>
