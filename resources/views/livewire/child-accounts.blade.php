<div>
    @include('partials.alerts')

    <div class="flex items-center flex-wrap justify-between gap20 mb-5">
        <div class="mb-20">
            <h4>Child Accounts</h4>
            <p>Here is a list of all child accounts</p>
        </div>
    </div>

    <div class="flex items-center justify-between gap10 flex-wrap mb-20">
        <div class="wg-filter flex-grow">
            <form class="form-search bg-white">
                <fieldset class="name">
                    <input spellcheck="false" type="text" wire:model.live="search" id="search" placeholder="Search" class="bg-white" name="name" tabindex="2" value="" aria-required="true" required="">
                </fieldset>
                <div class="button-submit">
                    <button class="" type="submit"><i class="icon-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    <div class="wg-box">
        <div class="table-wrapper">
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Balance</th>
                    <th>Number of APIs</th>
                    <th>Child Accounts Count</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($customers as $customer)
                    @include('partials.customer-row', ['customer' => $customer, 'level' => 0])
                @endforeach
                </tbody>
            </table>

            {{ $customers->links() }}
        </div>
    </div>
</div>
