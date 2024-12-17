<div>
    <style>
        .form-check-input:checked {
            background-color: #e76c21;
            border-color: #e76c21;
        }
        .form-check-input {
            border: 2px solid #666;
        }
        .alert-dropdown {
            height: 45px;
            padding: 10px;
            font-size: 15px;
        }
        .alert-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        .alerts-section {
            margin-top: 15px;
        }
    </style>

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
                <input type="text" wire:model="name" class="form-control" id="name">
                @error('name') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-6 form-group">
                <label for="phone" class="body-title mb-3">Phone Number</label>
                <input type="text" wire:model="phone" class="form-control" id="phone">
                @error('phone') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mt-5">
            <div class="flex items-center flex-wrap justify-between gap20 mb-4">
                <div>
                    <h4>Balance Alert Settings</h4>
                    <p>Configure when you want to receive balance notifications</p>
                </div>
            </div>

            <div class="row w-75">
                <div class="col-md-6 form-group alerts-section">
                    <div class="d-flex align-items-center">
                        <label class="body-title mb-0 me-3">Enable Balance Alerts</label>
                        <input type="checkbox" wire:model="balance_alerts" class="form-check-input alert-checkbox" id="balanceAlerts">
                    </div>
                </div>

                <div class="col-md-6 form-group">
                    <label for="balance_threshold" class="body-title mb-3">Alert Threshold (KES)</label>
                    <input type="number" wire:model="balance_threshold" class="form-control" id="balance_threshold" min="0">
                    @error('balance_threshold') <span class="error text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 form-group">
                    <label for="alert_frequency" class="body-title mb-3">Alert Frequency</label>
                    <select wire:model="alert_frequency" class="form-control alert-dropdown" id="alert_frequency">
                        <option value="hourly">Hourly</option>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                    </select>
                    @error('alert_frequency') <span class="error text-danger">{{ $message }}</span> @enderror
                </div>

                @if($alert_frequency !== 'hourly')
                <div class="col-md-6 form-group">
                    <label for="preferred_time" class="body-title mb-3">Preferred Time</label>
                    <input type="time" wire:model="preferred_time" class="form-control" id="preferred_time">
                    @error('preferred_time') <span class="error text-danger">{{ $message }}</span> @enderror
                </div>
                @endif
            </div>
        </div>

        <div class="flex mt-4">
            <button type="submit" class="button">Save Changes</button>
        </div>
    </form>
</div>