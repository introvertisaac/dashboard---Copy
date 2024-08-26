<div>
    <h2>Reset Password</h2>
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
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" wire:model="email" id="email" class="form-control @error('email') is-invalid @enderror">
            @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="btn btn-primary">Send Password Reset Link</button>
    </form>
</div>
