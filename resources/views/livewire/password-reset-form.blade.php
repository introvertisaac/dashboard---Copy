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

    <form wire:submit.prevent="resetPassword">

        <p>Create a new password for your account. Your new password should be strong and easy for you to remember, but difficult for others to guess. Please enter and confirm your new password below to complete the reset process.</p>
        <br>
        <input type="hidden" wire:model="token">
        <div class="form-group">
            <div class="body-title mb-5 mt-3">Username</div>
            <input type="text" wire:model="username" id="username" class="form-control @error('username') is-invalid @enderror">
            @error('username') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <div class="body-title mb-5 mt-3">Password</div>
            <input type="password" wire:model="password" id="password" class="form-control @error('password') is-invalid @enderror">
            @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <div class="body-title mb-5 mt-3">Confirm Password</div>
            <input type="password" wire:model="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror">
            @error('password_confirmation') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>
        <br>
        <div class="flex justify-center mt-10 ">
            <button type="submit" class="button">Submit</button>
        </div>

    </form>
</div>
