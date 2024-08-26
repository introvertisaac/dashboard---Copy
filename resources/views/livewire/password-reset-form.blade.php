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

    <form wire:submit.prevent="resetPassword">
        <input type="hidden" wire:model="token">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" wire:model="username" id="username" class="form-control @error('username') is-invalid @enderror">
            @error('username') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" wire:model="password" id="password" class="form-control @error('password') is-invalid @enderror">
            @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" wire:model="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror">
            @error('password_confirmation') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="btn btn-primary">Reset Password</button>
    </form>
</div>
