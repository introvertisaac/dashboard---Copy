<div>
    @include('partials.alerts')
    <div class="flex items-center flex-wrap justify-between gap20 mb-5">
        <div class="mb-20">
            <h4>Profile Information</h4>
            <p>Edit your profile details here</p>
        </div>
    </div>

    <form wire:submit.prevent="account_settings" class="form-login flex flex-column gap16 pt-2">
        <div class="row w-75">
            <div class="col-md-6 form-group">
                <label for="name" class="body-title mb-3">Name</label>
                <input type="text" wire:model="name" class="form-control" id="name"
                       placeholder="">
                @error('name') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-6 form-group">
                <label for="phone" class="body-title mb-3">Phone Number</label>
                <input type="text" wire:model="phone" class="form-control" id="phone"
                       placeholder="">
                @error('phone') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
        </div>



        <div class="flex mt-2">
            <button type="submit" class="button">Save Changes</button>
        </div>
    </form>


</div>
