<div>
    <div class="flex items-center flex-wrap justify-between gap20 mb-5 d-none">
        <div class="mb-20">
            <h4>KYC Verification</h4>
            <p>Select a method below</p>
        </div>
    </div>


    <div class="verification-options w-100">
        @foreach ($methods as $key => $method)
            <button wire:click="init_check('{{$key}}')" {{--data-bs-toggle="modal"
                    data-bs-target="#canvas_{{$key}}"
                    aria-controls="canvas_{{$key}}"--}}
            class="verification-method">
                <svg width="46" height="46" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="46" height="46" rx="23" fill="#FFFBF8"/>
                    <path
                        d="M31.0625 21.5H24.5C23.9033 21.5 23.331 21.2629 22.909 20.841C22.4871 20.419 22.25 19.8467 22.25 19.25V12.6875C22.25 12.6378 22.2302 12.5901 22.1951 12.5549C22.1599 12.5198 22.1122 12.5 22.0625 12.5H17.75C16.9544 12.5 16.1913 12.8161 15.6287 13.3787C15.0661 13.9413 14.75 14.7044 14.75 15.5V30.5C14.75 31.2956 15.0661 32.0587 15.6287 32.6213C16.1913 33.1839 16.9544 33.5 17.75 33.5H28.25C29.0456 33.5 29.8087 33.1839 30.3713 32.6213C30.9339 32.0587 31.25 31.2956 31.25 30.5V21.6875C31.25 21.6378 31.2302 21.5901 31.1951 21.5549C31.1599 21.5198 31.1122 21.5 31.0625 21.5Z"
                        fill="#E76C21"/>
                    <path
                        d="M30.6509 19.8403L23.9098 13.0992C23.8967 13.0861 23.8801 13.0773 23.8619 13.0737C23.8438 13.0701 23.825 13.072 23.8079 13.079C23.7908 13.0861 23.7762 13.098 23.7659 13.1134C23.7556 13.1287 23.7501 13.1468 23.75 13.1653V19.2501C23.75 19.449 23.829 19.6398 23.9697 19.7804C24.1103 19.9211 24.3011 20.0001 24.5 20.0001H30.5848C30.6033 20 30.6214 19.9945 30.6367 19.9842C30.6521 19.9739 30.664 19.9593 30.6711 19.9422C30.6781 19.9251 30.68 19.9063 30.6764 19.8882C30.6728 19.87 30.664 19.8534 30.6509 19.8403Z"
                        fill="#E76C21"/>
                </svg>
                {{$method['label']}}
            </button>



            <div class="modal fade" tabindex="-1" id="canvas_{{$key}}">

                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h6>{{$method['label']}} Verification</h6>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body">


                            <form wire:submit.prevent="kyc_check">


                                @if ($key=='bank')

                                    <div class="form-group">
                                        <label class="body-title mb-3 "
                                               for="bank_{{$key}}">Bank</label>
                                        <select wire:key="bank_{{$key}}" required wire:model="bank"
                                                id="in_{{$key}}">
                                            <option value="">---Select Bank---</option>

                                            @php
                                                $banks = config('billing.banks');
                                                asort($banks);
                                            @endphp

                                            @foreach($banks as $bank_id => $bank_name)

                                                <option value="{{$bank_id}}">{{$bank_name}}</option>

                                            @endforeach

                                        </select>
                                        @error('bank') <span class="error text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <br>
                                @endif

                                <div class="form-group">
                                    <label class="body-title mb-3"
                                           for="input_{{$key}}"> {{$method['input_label']}}</label>
                                    <input wire:key="input_{{$key}}" required type="text" wire:model="check_number"
                                           id="input_{{$key}}"
                                           placeholder="Please enter the {{$method['input_label']}}">
                                    @error('name') <span class="error text-danger">{{ $message }}</span> @enderror
                                </div>


                                <div class="flex gap10 my-4">
                                    <input checked required class="" type="checkbox" id="c{{$key}}">
                                    <label class="body-text" for="c{{$key}}">By checking this box, you are agreeing to
                                        the call fee</label>
                                </div>


                                <div class="d-flex justify-center">

                                    <button wire:loading.attr="disabled" type="submit" class="button fw-light">
                                        Verify Identity
                                        <span wire:loading>...<svg width="24" height="24" viewBox="0 0 100 100"
                                                                   xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                   stroke="#fff" stroke-width="2"
                                                                   style="display: inline-block; vertical-align: middle; margin-left: 10px;">
                                            <circle cx="50" cy="50" r="48" style="opacity:0.2;"/>
                                            <circle cx="50" cy="50" r="48"
                                                    style="stroke-dasharray:300; stroke-dashoffset:300;">
                                                <animateTransform attributeName="transform" type="rotate" from="0 50 50"
                                                                  to="360 50 50" dur="2s" repeatCount="indefinite"/>
                                                <animate attributeName="stroke-dashoffset" from="300" to="0" dur="1s"
                                                         repeatCount="indefinite"/>
                                            </circle>
                                        </svg></span>
                                    </button>


                                </div>


                            </form>

                        </div>
                    </div>
                </div>
            </div>

        @endforeach


    </div>


    <div class="modal fade" tabindex="-1" id="result_check">

        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h6>Verification Result - <span wire:model.live="check_type_label">{{$check_type_label}}</span></h6>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <style>
                        .section {
                            margin-bottom: 20px;
                        }

                        .section h2 {
                            margin-bottom: 10px;
                        }

                        .section p {
                            margin: 5px 0;
                        }
                    </style>
                    <div class="container">

                        <h6 class="mb-3">
                            <svg width="46" height="46" viewBox="0 0 46 46" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <rect width="46" height="46" rx="23" fill="#FFFBF8"></rect>
                                <path
                                    d="M31.0625 21.5H24.5C23.9033 21.5 23.331 21.2629 22.909 20.841C22.4871 20.419 22.25 19.8467 22.25 19.25V12.6875C22.25 12.6378 22.2302 12.5901 22.1951 12.5549C22.1599 12.5198 22.1122 12.5 22.0625 12.5H17.75C16.9544 12.5 16.1913 12.8161 15.6287 13.3787C15.0661 13.9413 14.75 14.7044 14.75 15.5V30.5C14.75 31.2956 15.0661 32.0587 15.6287 32.6213C16.1913 33.1839 16.9544 33.5 17.75 33.5H28.25C29.0456 33.5 29.8087 33.1839 30.3713 32.6213C30.9339 32.0587 31.25 31.2956 31.25 30.5V21.6875C31.25 21.6378 31.2302 21.5901 31.1951 21.5549C31.1599 21.5198 31.1122 21.5 31.0625 21.5Z"
                                    fill="#E76C21"></path>
                                <path
                                    d="M30.6509 19.8403L23.9098 13.0992C23.8967 13.0861 23.8801 13.0773 23.8619 13.0737C23.8438 13.0701 23.825 13.072 23.8079 13.079C23.7908 13.0861 23.7762 13.098 23.7659 13.1134C23.7556 13.1287 23.7501 13.1468 23.75 13.1653V19.2501C23.75 19.449 23.829 19.6398 23.9697 19.7804C24.1103 19.9211 24.3011 20.0001 24.5 20.0001H30.5848C30.6033 20 30.6214 19.9945 30.6367 19.9842C30.6521 19.9739 30.664 19.9593 30.6711 19.9422C30.6781 19.9251 30.68 19.9063 30.6764 19.8882C30.6728 19.87 30.664 19.8534 30.6509 19.8403Z"
                                    fill="#E76C21"></path>
                            </svg> {{$check_number}}</h6>
                        <hr>

                        @if(is_null($check_result))

                        <h6>No data found</h6>

                        @endif


                        @if(is_array($check_result))

                            @foreach($check_result as $key => $value)
                                @if(is_array($value))
                                    <div class="section">

                                        @if(count($value))
                                            <h6 class="mb-3 pb-3">{{ ucfirst(str_replace('_', ' ', $key)) }}</h6>
                                            @foreach($value as $subKey => $subValue)
                                                @if(is_array($subValue))
                                                    <div class="section">
                                                        @foreach($subValue as $subSubKey => $subSubValue)

                                                            @if(is_array($subSubValue))
                                                                <div class="section">

                                                                    @if(count($subSubValue))
                                                                        {{--  <h6 class="mb-3">{{ ucfirst(str_replace('_', ' ', $key)) }}</h6>--}}
                                                                        @foreach($subSubValue as $subKey => $subValue)
                                                                            @if(is_array($subValue))
                                                                                <div class="section">
                                                                                    @foreach($subValue as $subSubKey => $subSubValue)

                                                                                        @if(is_array($subValue))
                                                                                            <div class="section">
                                                                                                @foreach($subValue as $subSubKey_ => $subSubValue_)

                                                                                                    @if(is_array($subSubValue_))
                                                                                                        <div
                                                                                                            class="section">
                                                                                                            @foreach($subSubValue_ as $subSubKey__ => $subSubValue__)

                                                                                                                <p class="data_point">
                                                                                                                    <strong>{{ ucfirst(str_replace('_', ' ', $subSubKey__)) }}
                                                                                                                        :</strong> @if(is_array($subSubValue__))
                                                                                                                        {{print_r($subSubValue__)}}
                                                                                                                    @else
                                                                                                                        {{$subSubValue__}}
                                                                                                                    @endif
                                                                                                                </p>
                                                                                                            @endforeach
                                                                                                        </div>
                                                                                                    @else

                                                                                                        <p class="data_point">
                                                                                                            <strong>{{ ucfirst(str_replace('_', ' ', $subSubKey_)) }}
                                                                                                                :</strong> {{ $subSubValue_ }}
                                                                                                        </p>
                                                                                                    @endif
                                                                                                @endforeach

                                                                                            </div>
                                                                                        @else

                                                                                            <p class="data_point">
                                                                                                <strong>{{ ucfirst(str_replace('_', ' ', $subSubKey)) }}
                                                                                                    :</strong> {{ $subSubValue }}
                                                                                            </p>
                                                                                        @endif

                                                                                    @endforeach
                                                                                </div>
                                                                            @else
                                                                                <p class="data_point">
                                                                                    <strong>{{ ucfirst(str_replace('_', ' ', $subKey)) }}
                                                                                        :</strong> {{ $subSubValue }}
                                                                                </p>
                                                                            @endif
                                                                        @endforeach

                                                                    @else
                                                                        <p class="data_point">
                                                                            <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}
                                                                                :</strong> - </p>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <div class="section">
                                                                    <p class="data_point">
                                                                        <strong>{{ ucfirst(str_replace('_', ' ', $subSubKey)) }}
                                                                            :</strong> {{ $subSubValue }}</p>
                                                                </div>
                                                            @endif

                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p class="data_point">
                                                        <strong>{{ ucfirst(str_replace('_', ' ', $subKey)) }}
                                                            :</strong> {{ $subValue }}</p>
                                                @endif
                                            @endforeach

                                        @else
                                            <p class="data_point"><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}
                                                    :</strong> - </p>
                                        @endif
                                    </div>
                                    <hr>
                                @else
                                    <div class="section">
                                        <p class="data_point"><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}
                                                :</strong> {{ $value }}</p>
                                    </div>
                                @endif
                            @endforeach

                        @endif
                    </div>


                    @if($balance_impact)
                        <div class="tf-bg-light p-3 d-flex justify-content-between">
                            @foreach($balance_impact as $item => $item_value)

                                <p class="flex-column mx-x3"><strong>{{$item}}:</strong> {{ $item_value }}</p>

                            @endforeach
                        </div>
                    @endif


                </div>


            </div>
        </div>
    </div>


    <div class="flex items-center flex-wrap justify-between mb-5 mt-5">
        <div class="mb-20">
            <h4>Results</h4>
            <p>A list of all results searched before</p>
        </div>
    </div>

    {{--<div class="flex items-center justify-between gap10 flex-wrap mb-20">
        <div class="wg-filter flex-grow">
            <form class="form-search bg-white">
                <fieldset class="name">
                    <input spellcheck="false" type="text" wire:model.live="search" id="search"
                           placeholder="Search" class="bg-white"
                           name="name" tabindex="2" value="" aria-required="true" required="">
                </fieldset>
                <div class="button-submit">
                    <button class="" type="submit"><i class="icon-search"></i></button>
                </div>
            </form>
        </div>
    </div>--}}


    @if(count($listing))
        <div class="wg-box">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                    <tr>
                        <th>KYC Type</th>
                        <th>Search</th>
                        <th>Channel</th>
                        <th>Timestamp</th>
                        <th>View</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($listing as $list_item)
                        <tr>
                            <td title="TrackingID: {{$list_item->search_uuid}}">{{strtoupper($list_item->search_type)}}</td>
                            <td>{{$list_item->search_param}}</td>
                            <td>{{ucwords($list_item->channel)}}
                                <small class="d-block">{{$list_item->user->name}}</small>
                            </td>
                            <td>{{$list_item->created_at_human}}</td>
                            <td>
                                @if ($list_item->is_older_than_24_hours)

                                @else
                                    <a class="" wire:click="view('{{$list_item->search_uuid}}')">View</a>
                                @endif



                            </td>

                        </tr>
                    @endforeach


                    </tbody>
                </table>

                {{ $listing->links('vendor.pagination.bootstrap-5') }}
            </div>


        </div>

    @else

        <div class="block-warning type-custom w-full fs-4 my-3">
            No records found
        </div>

    @endif


    <script>
        window.addEventListener('openCheckInputModal', event => {
            close_all_modals();
            let check_type = event.detail.check;

            let modal = new bootstrap.Modal(document.getElementById('canvas_' + check_type));
            modal.show();
        })


        window.addEventListener('openResultModal', event => {
            close_all_modals();

            var modal = new bootstrap.Modal(document.getElementById('result_check'));
            modal.show();
        })

        function close_all_modals() {

            var openModals = document.querySelectorAll(".modal");
            if (openModals) {
                for (let i = 0; i < openModals.length; i++) {
                    var modalHeader = openModals[i].getElementsByClassName("modal-header");
                    if (modalHeader && modalHeader.length > 0) {
                        var closeButton = modalHeader[0].getElementsByTagName("button");
                        if (closeButton && closeButton.length > 0) {
                            closeButton[0].click();
                        }
                    }
                }
            }
        }
    </script>

</div>
