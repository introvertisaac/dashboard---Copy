<form wire:submit.prevent="{{$action}}" class="tf-section-1 form-add-product">
    <input type="hidden" wire:model="customer_uuid">
    <div class="wg-box">


        <div class="row">
            <div class="col-md-6 form-group">
                <label class="body-title mb-3" for="fullName">Name <span class="text-danger">*</span></label>
                <input type="text" wire:model="name" class="form-control" id="fullName"
                       placeholder="Please enter the name of the customer">
                @error('name') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-6 form-group">
                <label class="body-title mb-3" for="fullName">Phone Number <span
                        class="text-danger">*</span></label>
                <input type="text" wire:model="phone" class="form-control" id="phoneNumber"
                       placeholder="Please enter the phone number">
                @error('phone') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
        </div>


        <div class="row">
            <div class="col-md-6 form-group">
                <label for="email" class="body-title mb-3">Primary Email Address<span
                        class="text-danger">*</span></label>
                <input type="email" wire:model="primary_email" class="form-control" id="email"
                       placeholder="Please enter the primary email address">
                @error('primary_email') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-6 form-group">
                <label for="website" class="body-title mb-3">Website</label>
                <input wire:model="website" type="text" class="form-control" id="website"
                       placeholder="Please enter the website">
            </div>
        </div>


        <div class="row">
            <div class="divider"></div>
            <br>
            <h6 class="fs-5 fw-light text-muted">ACCOUNT CONFIGURATIONS</h6>

            <div class="row mt-3">

                @if(customer()->is_reseller)
                <div class="col-md-6 form-group">
                    <label class="body-title mb-3" for="account_type">Account Type <span
                            class="text-danger">*</span></label>
                    <select wire:model="is_reseller" id="is_reseller">
                        <option value="">Select Type</option>
                        <option  {{ intval($customer->is_reseller) === 1 ? 'selected' : '' }} value="1">Reseller</option>
                        <option {{ intval($customer->is_reseller) === 0 ? 'selected' : '' }} value="0">Normal Customer</option>
                    </select>
                    @error('account_type') <span class="error text-danger">{{ $message }}</span> @enderror
                </div>
                @endif


                <div class="col-md-6 form-group">
                    <label class="body-title mb-3" for="status">Account Status</label>
                    <select id="status" class="mb-2" wire:model="status">
                        <option value="">Select Status</option>
                        <option value="active" {{ $customer->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ $customer->status == 'suspended' ? 'selected' : '' }}>Suspended
                        </option>
                    </select>
                    @error('status') <span class="error">{{ $message }}</span> @enderror

                    @if($customer->children->count())
                    @if($customer->status=='active')
                        <span class="">Changing the status to suspended will also change the status of child accounts ({{$customer->children->count()}}) related to this account</span>
                    @endif

                    @if($customer->status=='suspended')
                        <span class="">Changing the status to active will only change status of this account, suspended child accounts ({{$customer->children->count()}}) will need to be activated manually</span>
                    @endif

                        @endif

                </div>


            </div>


        </div>


        <div class="row">
            <div class="divider"></div>
            <br>
            <h6 class="fs-5 fw-light text-muted">SERVICE CHARGE CONFIGURATIONS</h6>
            @foreach ($services as $service_key => $service)
                <div class="col-md-6 form-group mt-1 pt-4">
                    <label for="{{$service_key}}ApiCharge" class="body-title mb-3">{{$service['label']}} API
                        Charge</label>
                    <input wire:key="charges.{{$service_key}}" wire:model="charges.{{$service_key}}" type="number"
                           class="form-control mb-3"
                           placeholder="Please enter the amount">
                    <span class="mt-4">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10 2.1875C5.69219 2.1875 2.1875 5.69219 2.1875 10C2.1875 14.3078 5.69219 17.8125 10 17.8125C14.3078 17.8125 17.8125 14.3078 17.8125 10C17.8125 5.69219 14.3078 2.1875 10 2.1875ZM10 5.39062C10.2009 5.39062 10.3972 5.45019 10.5643 5.56179C10.7313 5.67339 10.8614 5.83201 10.9383 6.01759C11.0152 6.20317 11.0353 6.40738 10.9961 6.60439C10.9569 6.8014 10.8602 6.98237 10.7182 7.12441C10.5761 7.26644 10.3952 7.36317 10.1981 7.40236C10.0011 7.44155 9.79692 7.42144 9.61134 7.34457C9.42576 7.26769 9.26714 7.13752 9.15554 6.9705C9.04394 6.80348 8.98438 6.60712 8.98438 6.40625C8.98438 6.13689 9.09138 5.87856 9.28184 5.68809C9.47231 5.49763 9.73064 5.39062 10 5.39062ZM11.875 14.2188H8.4375C8.27174 14.2188 8.11277 14.1529 7.99556 14.0357C7.87835 13.9185 7.8125 13.7595 7.8125 13.5938C7.8125 13.428 7.87835 13.269 7.99556 13.1518C8.11277 13.0346 8.27174 12.9688 8.4375 12.9688H9.53125V9.53125H8.90625C8.74049 9.53125 8.58152 9.4654 8.46431 9.34819C8.3471 9.23098 8.28125 9.07201 8.28125 8.90625C8.28125 8.74049 8.3471 8.58152 8.46431 8.46431C8.58152 8.3471 8.74049 8.28125 8.90625 8.28125H10.1562C10.322 8.28125 10.481 8.3471 10.5982 8.46431C10.7154 8.58152 10.7812 8.74049 10.7812 8.90625V12.9688H11.875C12.0408 12.9688 12.1997 13.0346 12.3169 13.1518C12.4342 13.269 12.5 13.428 12.5 13.5938C12.5 13.7595 12.4342 13.9185 12.3169 14.0357C12.1997 14.1529 12.0408 14.2188 11.875 14.2188Z"
                                fill="#242424"/>
                        </svg>
                        Input amount (KES) to be deducted per call.
                            @if(optional($customer_charges)->$service_key)
                            Buying: KES {{$customer_charges->$service_key}}
                        @endif

                        </span>
                </div>
            @endforeach

        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="button fw-light">Submit</button>
        </div>

    </div>


</form>
