<div>


    <div class="flex items-center flex-wrap justify-between gap20 mb-5">
        <div class="mb-20">
            <h4>Customer Transactions</h4>
            <p>Here is a list of filterable customer transactions</p>
        </div>
    </div>



    <div class="wg-box">
        <form wire:submit.prevent="filterTransactions">

            <div class="row mb-4">

                <div class="col-md-4 form-group">

                    <fieldset class="selectedService">
                        <div class="body-title mb-3">Service:</div>
                        <div class="select">
                            <select id="selectedService" wire:model="selectedService">
                                <option value="">All Services</option>
                                @foreach($services as $service_name => $service_detail)
                                    <option value="{{ $service_name }}">{{ $service_detail['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>


                </div>
                <div class="col-md-4">

                    @if(count($customers))
                    <fieldset class="customerFilter">
                        <div class="body-title mb-3">Customer:</div>
                        <div class="select">
                            <select id="customerFilter" wire:model="selectedCustomerId">
                                <option value="">All Customers</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>
                        @endif

                </div>


            </div>
            <br>

            <div class="row">

                <div class="col-md-4">
                    <fieldset class="startDate">
                        <div class="body-title mb-3">Start Date:</div>
                        <input type="date" id="startDate" wire:model="startDate" class="form-control">
                    </fieldset>
                </div>

                <div class="col-md-4">
                    <fieldset class="endDate">
                        <div class="body-title mb-3">End Date:</div>
                        <input type="date" id="endDate" wire:model="endDate" class="form-control">
                    </fieldset>
                </div>

            </div>


            <br>
            <div class="mt-5x">
                <button type="submit" class="button">Filter Transactions</button>
            </div>


        </form>



        <div class="row mb-4 card-body">
            <div class="col-md-4">
                <div class="card mb-3" style="background-color: #E6F3FF;">
                    <div class="card-header">Total Revenue (based on Selling Price)</div>
                    <div class="card-body">
                        <h5 class="card-title">KSh {{ number_format($totalRevenue, 2) }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-3" style="background-color: #FFEBE6;">
                    <div class="card-header">Total Expense (based on Buying Price)</div>
                    <div class="card-body">
                        <h5 class="card-title">KSh {{ number_format($totalExpense, 2) }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-3" style="background-color: #E8FFE6;">
                    <div class="card-header">Total Profit</div>
                    <div class="card-body">
                        <h5 class="card-title">KSh {{ number_format($profit, 2) }}</h5>
                    </div>
                </div>
            </div>
        </div>


        @if($transactions->isEmpty())
            <p>No transactions found for the filter parameters</p>
        @else

                <div class="table-wrapper">
                    <table class="table table-responsive">
                        <thead>
                        <tr>
                            <th>Service</th>
                            <th>Customer</th>
                            <th>Buying Price</th>
                            <th>Selling Price</th>
                            <th>Margin</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($transactions as $transaction)
                            <tr title="{{$transaction->id}}">
                                <td>{{ service_label($transaction->service) }}

                                  {{--  <br>
                                    <small class="d-block">{{'LedgerID: '.$transaction->id}}</small>
                                    <br>
                                    <small class="d-block">{{'SearchID: '.$transaction->search_id}}</small>--}}
                                    <small
                                        class="d-block text-muted mt-1 p-1">{{ucwords($transaction->channel)}}</small>
                                </td>
                                <td>{{ $transaction->customer->name }}
                                    <small
                                        class="d-block text-muted mt-1 p-1">{{ucwords($transaction->user->name)}}</small>
                                </td>
                                <td>{{ $transaction->buying_price }}</td>
                                <td>{{ $transaction->selling_price }}</td>
                                <td>{{ $transaction->margin }}</td>
                                <td>{{ $transaction->dated }}</td>
                            </tr>
                        @endforeach
                        </tbody>


                    </table>

                    {{ $transactions->links() }}
                </div>

            </div>

        @endif


</div>
