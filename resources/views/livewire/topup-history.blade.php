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


        <div class="row">


                <div class="col-md-6 ">
                    <div class="wg-chart-default tf-border summary-card">
                        <div class="flex">
                            <div class="items-center gap14">
                                <div class="image">
                                    <svg width="46" height="47" viewBox="0 0 46 47" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect y="0.5" width="46" height="46" rx="23" fill="#FFFBF8"/>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M26.75 18.25C26.75 17.8358 27.0858 17.5 27.5 17.5H32.75C33.1642 17.5 33.5 17.8358 33.5 18.25V23.5C33.5 23.9142 33.1642 24.25 32.75 24.25C32.3358 24.25 32 23.9142 32 23.5V19H27.5C27.0858 19 26.75 18.6642 26.75 18.25Z" fill="#E76C21"/>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M32.5303 18.4697C32.8232 18.7626 32.8232 19.2374 32.5303 19.5303L26.0912 25.9695C26.0912 25.9695 26.0911 25.9695 26.0911 25.9695C25.8822 26.1785 25.6341 26.3443 25.3612 26.4574C25.0881 26.5705 24.7955 26.6287 24.5 26.6287C24.2045 26.6287 23.9119 26.5705 23.6388 26.4574C23.3658 26.3443 23.1178 26.1785 22.9088 25.9695L20.5305 23.5911L20.5304 23.5911C20.4607 23.5214 20.3781 23.4661 20.2871 23.4284C20.196 23.3907 20.0985 23.3713 20 23.3713C19.9015 23.3713 19.804 23.3907 19.7129 23.4284C19.6219 23.4661 19.5393 23.5214 19.4696 23.5911L19.4695 23.5911L13.7803 29.2803C13.4874 29.5732 13.0126 29.5732 12.7197 29.2803C12.4268 28.9874 12.4268 28.5126 12.7197 28.2197L18.4088 22.5305C18.6178 22.3215 18.8658 22.1557 19.1388 22.0426C19.4119 21.9295 19.7045 21.8713 20 21.8713C20.2955 21.8713 20.5881 21.9295 20.8612 22.0426C21.1342 22.1557 21.3822 22.3215 21.5912 22.5305L23.9695 24.9089L23.9696 24.9089C24.0393 24.9786 24.1219 25.0339 24.2129 25.0716C24.304 25.1093 24.4015 25.1287 24.5 25.1287C24.5985 25.1287 24.696 25.1093 24.7871 25.0716C24.8781 25.0339 24.9607 24.9786 25.0304 24.9089L25.0305 24.9089L31.4697 18.4697C31.7626 18.1768 32.2374 18.1768 32.5303 18.4697Z" fill="#E76C21"/>
                                    </svg>



                                </div>

                                <br>
                                <div class="mt-2">
                                    <div class="mb-4 fs-16">Total Deposits</div>
                                    <h4 class="revenue">KES {{ number_format($totalDeposits, 2) }}  </h4>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>




                <div class="col-md-6 ">
                    <div class="wg-chart-default tf-border summary-card">
                        <div class="flex">
                            <div class="items-center gap14">
                                <div class="image">

                                    <svg width="46" height="47" viewBox="0 0 46 47" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <rect y="0.5" width="46" height="46" rx="23" fill="#FFFBF8"/>
                                        <path
                                            d="M15.4766 16.3757H30.4766C30.6518 16.3756 30.8268 16.3867 31.0006 16.409C30.9417 15.9954 30.7997 15.5981 30.5831 15.2409C30.3664 14.8838 30.0797 14.5742 29.7402 14.3308C29.4007 14.0875 29.0154 13.9154 28.6076 13.8249C28.1998 13.7345 27.7779 13.7276 27.3674 13.8046L15.0313 15.9107H15.0172C14.2429 16.0588 13.5543 16.4969 13.092 17.1355C13.7884 16.6402 14.622 16.3746 15.4766 16.3757Z"
                                            fill="#E76C21"/>
                                        <path
                                            d="M30.4766 17.5H15.4766C14.6812 17.5009 13.9186 17.8172 13.3562 18.3796C12.7938 18.9421 12.4774 19.7046 12.4766 20.5V29.5C12.4774 30.2954 12.7938 31.0579 13.3562 31.6204C13.9186 32.1828 14.6812 32.4991 15.4766 32.5H30.4766C31.2719 32.4991 32.0345 32.1828 32.5969 31.6204C33.1593 31.0579 33.4757 30.2954 33.4766 29.5V20.5C33.4757 19.7046 33.1593 18.9421 32.5969 18.3796C32.0345 17.8172 31.2719 17.5009 30.4766 17.5ZM28.25 26.5C27.9533 26.5 27.6633 26.412 27.4166 26.2472C27.17 26.0824 26.9777 25.8481 26.8642 25.574C26.7506 25.2999 26.7209 24.9983 26.7788 24.7074C26.8367 24.4164 26.9796 24.1491 27.1893 23.9393C27.3991 23.7296 27.6664 23.5867 27.9574 23.5288C28.2483 23.4709 28.5499 23.5006 28.824 23.6142C29.0981 23.7277 29.3324 23.92 29.4972 24.1666C29.662 24.4133 29.75 24.7033 29.75 25C29.75 25.3978 29.592 25.7794 29.3107 26.0607C29.0294 26.342 28.6478 26.5 28.25 26.5Z"
                                            fill="#E76C21"/>
                                        <path
                                            d="M12.5 23.6641V19C12.5 17.9842 13.0625 16.2812 15.0148 15.9123C16.6719 15.6016 18.3125 15.6016 18.3125 15.6016C18.3125 15.6016 19.3906 16.3516 18.5 16.3516C17.6094 16.3516 17.6328 17.5 18.5 17.5C19.3672 17.5 18.5 18.6016 18.5 18.6016L15.0078 22.5625L12.5 23.6641Z"
                                            fill="#E76C21"/>
                                    </svg>



                                </div>

                                <br>
                                <div class="mt-2">
                                    <div class="mb-4 fs-16">Current Balance</div>
                                    <h4 class="profit">KES {{ number_format($currentBalance, 2) }}  </h4>
                                </div>
                            </div>

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