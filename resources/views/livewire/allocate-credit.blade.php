<div>

    <div class="flex items-center flex-wrap justify-between gap20 mb-5">
        <div class="mb-20">


            <div class="d-flex justify-content-between align-items-center mb-4">
                <a wire:click="list()" class="back-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-chevron-left">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </a>
                <div class="ml-6">
                    <h4>Allocate Credit to <span class="tf-color">{{$customer->name}}</span></h4>
                    <p>Fill in the form below to allocate credit. </p>
                </div>
            </div>


        </div>

    </div>


    <form wire:submit.prevent="credit_topup" class="tf-section-1 form-add-product">
        <input type="hidden" wire:model="customer_uuid">
        <div class="wg-box">

            <div class="block-warning type-main w-full">
                <i class="icon-alert-octagon"></i>
                <div class="body-title-2">Current Balance for <span
                        class="text-dark">{{$customer->name}}</span>: {{$customer->balance_label}}</div>
            </div>

            <div class="row">
                <div class="col-md-5 form-group">
                    <label class="body-title mb-3" for="amount">Amount <span class="text-danger">*</span></label>
                    <input type="number" min="1" wire:model="amount" class="form-control" id="amount"
                           placeholder="Amount to topup">
                    @error('amount') <span class="error text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-5 form-group">
                    <label class="body-title mb-3" for="narration">Narration <span
                            class="text-danger">*</span></label>
                    <input type="text" wire:model="narration" placeholder="Input narration or transaction reference" class="form-control" id="narration">
                    @error('narration') <span class="error text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="d-flex justify-content-start">
                <button type="submit" class="button fw-light">Submit</button>
            </div>

        </div>


    </form>


</div>
