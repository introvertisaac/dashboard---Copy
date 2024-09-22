<div>

    <div class="flex items-center flex-wrap justify-between gap20 mb-5">
        <div class="mb-20">
            <h4>Topup History</h4>
            <p>Here is a list of topups to your account</p>
        </div>
    </div>

    <style>

        .fs-16 {
            font-size: 16px;
        }

        .summary-card {
            background-color: #FBFBFB;
            border-radius: 10px;
            border: 1px solid #EEEEEE;
            padding: 20px !important;
        }

        .mt-15 {
            margin-top: 15px !important;
        }

        .revenue {
            color: #000;
        }

        .expense {
            color: #ff6600;
        }

        .profit {
            color: #28a745;
        }

        .col-md-4 {
            padding-left: 15px;
            padding-right: 15px;
        }
    </style>


    <div class="wg-box">
        <form wire:submit.prevent="filterTransactions">

            <div class="row mb-4">

                <div class="col-md-6">

                    @if(count($customers))
                        <fieldset class="customerFilter">
                            <div class="body-title mb-3">Customer:</div>
                            <div class="select">
                                <select id="customerFilter" wire:model="selectedCustomerId">
                                    <option value="{{customer()->id}}">{{customer()->name}} (Self)</option>
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

                <div class="col-md-6">
                    <fieldset class="startDate">
                        <div class="body-title mb-3">Start Date:</div>
                        <input placeholder="Please select the start date" type="date" id="startDate"
                               wire:model="startDate" class="form-control">
                    </fieldset>
                </div>

                <div class="col-md-6">
                    <fieldset class="endDate">
                        <div class="body-title mb-3">End Date:</div>
                        <input placeholder="Please select the end date" type="date" id="endDate" wire:model="endDate"
                               class="form-control">
                    </fieldset>
                </div>

            </div>



            <div class="d-flex justify-content-center mt-15">
                <button type="submit" class="button fs-16 font-extrabold">Filter Transactions</button>
            </div>


        </form>


        <div class="row"></div>



        @if($transactions->isEmpty())
            <p>No transactions found for the filter parameters</p>
        @else

            <div class="table-wrapper">
                <table class="table table-responsive">
                    <thead>
                    <tr>
                        <th>Topup Date</th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transactions as $transaction)
                        <tr title="{{optional($transaction->meta)->narration}}">
                            <td title="{{$transaction->created_at}}">{{carbon($transaction->created_at)->format('M j, Y g:i A')}}</td>
                            <td>KES {{number_format($transaction->amount)}}</td>
                        </tr>
                    @endforeach
                    </tbody>


                </table>

                {{ $transactions->links() }}
            </div>

    </div>

    @endif


</div>
