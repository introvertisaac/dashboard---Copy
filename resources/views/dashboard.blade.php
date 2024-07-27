@extends('layout')
@section('title','Dashboard')
@section('content')

    <style>
        .chevron-icon {
            width: 16px;
            height: 16px;
            margin-left: 10px;
            position: absolute;
        }


    </style>

    @if ($customer_uuid)
        <div class="mb-20">
            <h4>You're viewing dashboard for <span class="tf-color"> {{user('name')}}</span>,</h4>
            <p>Here is an overview of the system</p>
        </div>

        @else
        <div class="mb-20">
            <h4>Welcome<span class="tf-color"> {{user('name')}}</span>,</h4>
            <p>Here is an overview of the system</p>
        </div>
    @endif



    <div class="tf-section-4 mb-30 mt-20">

        @can('view_balance')
            <div class="wg-chart-default tf-border">
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
                        <div>
                            <div class="mb-2 fs-4">Credit Balance</div>
                            <h4>{{$balance}}</h4>
                        </div>
                    </div>

                </div>

            </div>
        @endcan


        <div class="wg-chart-default tf-border">
            <div class="flex">
                <div class="items-center gap14">
                    <div class="image">
                        <svg width="46" height="47" viewBox="0 0 46 47" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect y="0.5" width="46" height="46" rx="23" fill="#FFFBF8"/>
                            <path
                                d="M18.5001 29.7343C18.2617 29.7346 18.0314 29.6479 17.8523 29.4906L11.8523 24.2406C11.7468 24.1482 11.6622 24.0343 11.6043 23.9066C11.5463 23.7789 11.5163 23.6402 11.5163 23.5C11.5163 23.3597 11.5463 23.2211 11.6043 23.0933C11.6622 22.9656 11.7468 22.8517 11.8523 22.7593L17.8523 17.5093C18.0488 17.3374 18.3055 17.2506 18.566 17.2679C18.8265 17.2853 19.0695 17.4054 19.2414 17.6019C19.4134 17.7984 19.5002 18.0551 19.4828 18.3157C19.4655 18.5762 19.3453 18.8191 19.1489 18.9911L13.9949 23.5L19.1484 28.0089C19.2992 28.1407 19.4062 28.3155 19.4551 28.5098C19.504 28.7041 19.4924 28.9086 19.422 29.0962C19.3515 29.2837 19.2255 29.4453 19.0608 29.5593C18.896 29.6734 18.7004 29.7344 18.5001 29.7343Z"
                                fill="#E76C21"/>
                            <path
                                d="M27.4999 29.7343C27.2996 29.7345 27.1039 29.6735 26.9391 29.5595C26.7743 29.4455 26.6482 29.284 26.5777 29.0964C26.5072 28.9088 26.4956 28.7042 26.5444 28.5099C26.5933 28.3155 26.7003 28.1408 26.8512 28.0089L32.0051 23.5L26.8517 18.9911C26.6552 18.8191 26.535 18.5762 26.5177 18.3157C26.5003 18.0551 26.5871 17.7984 26.7591 17.6019C26.931 17.4054 27.174 17.2853 27.4345 17.2679C27.695 17.2506 27.9517 17.3374 28.1482 17.5093L34.1482 22.7593C34.2538 22.8517 34.3383 22.9656 34.3963 23.0933C34.4542 23.2211 34.4842 23.3597 34.4842 23.5C34.4842 23.6402 34.4542 23.7789 34.3963 23.9066C34.3383 24.0343 34.2538 24.1482 34.1482 24.2406L28.1482 29.4906C27.969 29.6481 27.7385 29.7348 27.4999 29.7343Z"
                                fill="#E76C21"/>
                            <path
                                d="M20.75 31.9846C20.5967 31.9846 20.4455 31.9487 20.3085 31.8799C20.1715 31.8112 20.0525 31.7113 19.9609 31.5884C19.8693 31.4655 19.8076 31.3229 19.7809 31.1719C19.7542 31.021 19.763 30.8659 19.8068 30.719L24.3068 15.719C24.3407 15.5917 24.3998 15.4725 24.4806 15.3685C24.5614 15.2644 24.6623 15.1777 24.7772 15.1133C24.8922 15.049 25.0189 15.0084 25.1498 14.9939C25.2807 14.9794 25.4132 14.9913 25.5394 15.029C25.6657 15.0666 25.7831 15.1292 25.8846 15.2131C25.9862 15.2969 26.07 15.4003 26.1309 15.5171C26.1918 15.6339 26.2286 15.7617 26.2392 15.893C26.2499 16.0243 26.234 16.1564 26.1926 16.2815L21.6926 31.2815C21.6321 31.4845 21.5077 31.6626 21.3378 31.7893C21.168 31.916 20.9618 31.9845 20.75 31.9846Z"
                                fill="#E76C21"/>
                        </svg>


                    </div>

                    <br>


                    <div>
                        <div class="mb-2 fs-4"> API Calls</div>
                        <h4>{{number_format($searches_count)}} <span class="" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight1"
                                      aria-controls="offcanvasRight1">

                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M21.75 12C21.75 6.61547 17.3845 2.25 12 2.25C6.61547 2.25 2.25 6.61547 2.25 12C2.25 17.3845 6.61547 21.75 12 21.75C17.3845 21.75 21.75 17.3845 21.75 12ZM16.0514 11.167L12.5766 15.3431C12.5062 15.4277 12.418 15.4958 12.3184 15.5425C12.2188 15.5892 12.1101 15.6135 12 15.6135C11.8899 15.6135 11.7812 15.5892 11.6816 15.5425C11.582 15.4958 11.4938 15.4277 11.4234 15.3431L7.94859 11.167C7.85753 11.0575 7.79952 10.9243 7.78138 10.7831C7.76323 10.6418 7.78569 10.4983 7.84612 10.3693C7.90655 10.2403 8.00246 10.1312 8.12263 10.0547C8.2428 9.97828 8.38225 9.93761 8.52469 9.9375H15.4753C15.6177 9.93761 15.7572 9.97828 15.8774 10.0547C15.9975 10.1312 16.0934 10.2403 16.1539 10.3693C16.2143 10.4983 16.2368 10.6418 16.2186 10.7831C16.2005 10.9243 16.1425 11.0575 16.0514 11.167Z"
                                fill="#242424"/>
                        </svg>
                    </span></h4>


                    </div>


                </div>

            </div>

        </div>


        <div class="offcanvas offcanvas-end tf-bg-light" tabindex="-1" id="offcanvasRight1">
            <div class="offcanvas-header ">
                <h6>API CALLS REPORT</h6>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">


                <table class="table table-hover table-borderless">
                    <tbody class="body-text">
                    <tr>
                        <td>ID API:</td>
                        <td>14</td>
                    </tr>
                    <tr>
                        <td>Alien ID API:</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td>BRS API:</td>
                        <td>3</td>
                    </tr>
                    <tr>
                        <td>KRA Pin API:</td>
                        <td>10</td>
                    </tr>
                    <tr>
                        <td>Number Plate API:</td>
                        <td>7</td>
                    </tr>
                    <tr>
                        <td>Bank Check API:</td>
                        <td>9</td>
                    </tr>
                    </tbody>
                </table>


            </div>
        </div>

        <div class="wg-chart-default tf-border">
            <div class="flex">
                <div class="items-center gap14">
                    <div class="image">
                        <svg width="46" height="47" viewBox="0 0 46 47" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect y="0.5" width="46" height="46" rx="23" fill="#FFFBF8"/>
                            <path
                                d="M26.5925 14.5272C25.6803 13.5423 24.4063 13 23 13C21.5863 13 20.308 13.5391 19.4 14.5178C18.4822 15.5073 18.035 16.8522 18.14 18.3044C18.3481 21.1694 20.5283 23.5 23 23.5C25.4717 23.5 27.6482 21.1698 27.8596 18.3053C27.966 16.8662 27.516 15.5242 26.5925 14.5272Z"
                                fill="#E76C21"/>
                            <path
                                d="M31.25 34H14.75C14.534 34.0028 14.3202 33.9574 14.1239 33.8672C13.9277 33.7769 13.7541 33.6441 13.6156 33.4783C13.3109 33.1141 13.1881 32.6167 13.2791 32.1137C13.6747 29.9191 14.9094 28.0755 16.85 26.7812C18.5741 25.6323 20.758 25 23 25C25.242 25 27.4259 25.6328 29.15 26.7812C31.0906 28.075 32.3253 29.9186 32.7209 32.1133C32.8119 32.6162 32.6891 33.1136 32.3844 33.4778C32.246 33.6437 32.0724 33.7766 31.8762 33.867C31.6799 33.9573 31.466 34.0028 31.25 34Z"
                                fill="#E76C21"/>
                        </svg>


                    </div>

                    <br>
                    <div>
                        <div class="mb-2 fs-4">Users</div>
                        <h4>{{$customer_users_count}}</h4>
                    </div>
                </div>

            </div>

        </div>

        <div class="wg-chart-default tf-border">
            <div class="flex">
                <div class="items-center gap14">
                    <div class="image">
                        <svg width="46" height="47" viewBox="0 0 46 47" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect y="0.5" width="46" height="46" rx="23" fill="#FFFBF8"/>
                            <path
                                d="M26.5925 14.5272C25.6803 13.5423 24.4063 13 23 13C21.5863 13 20.308 13.5391 19.4 14.5178C18.4822 15.5073 18.035 16.8522 18.14 18.3044C18.3481 21.1694 20.5283 23.5 23 23.5C25.4717 23.5 27.6482 21.1698 27.8596 18.3053C27.966 16.8662 27.516 15.5242 26.5925 14.5272Z"
                                fill="#E76C21"/>
                            <path
                                d="M31.25 34H14.75C14.534 34.0028 14.3202 33.9574 14.1239 33.8672C13.9277 33.7769 13.7541 33.6441 13.6156 33.4783C13.3109 33.1141 13.1881 32.6167 13.2791 32.1137C13.6747 29.9191 14.9094 28.0755 16.85 26.7812C18.5741 25.6323 20.758 25 23 25C25.242 25 27.4259 25.6328 29.15 26.7812C31.0906 28.075 32.3253 29.9186 32.7209 32.1133C32.8119 32.6162 32.6891 33.1136 32.3844 33.4778C32.246 33.6437 32.0724 33.7766 31.8762 33.867C31.6799 33.9573 31.466 34.0028 31.25 34Z"
                                fill="#E76C21"/>
                        </svg>


                    </div>

                    <br>
                    <div>
                        <div class="mb-2 fs-4">Verification Services</div>
                        <h4>{{$service_count}}</h4>
                    </div>
                </div>

            </div>

        </div>


    </div>
    <div class="tf-section-7 mb-30">
        <!-- chart -->
        <div class="wg-box">
            <div id="line-chart-5"></div>

            <div class="row gap10">


                <div class="col tf-bg-light-2 p-4">
                    <div class="flex items-center justify-between ">
                        <div class="flex items-center">
                            <div>
                                <div class="body-text mb-2 bold fs-4">API calls this year</div>
                                <h4>{{number_format($searches_count_year)}}</h4>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col tf-bg-light-2  p-4">
                    <div class="flex items-center justify-between ">
                        <div class="flex items-center">
                            <div>
                                <div class="body-text mb-2 bold fs-4">API calls this month</div>
                                <h4>{{number_format($searches_count_month)}}</h4>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col tf-bg-light-2  p-4">
                    <div class="flex items-center justify-between ">
                        <div class="flex items-center">
                            <div>
                                <div class="body-text mb-2 bold fs-4">API calls today</div>
                                <h4>{{number_format($searches_count_today)}}</h4>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>


        @if (!$customer_uuid)

        <div class="wg-box">
            <div class="flex items-center justify-between">
                <h5 class="fs-4">Recent Activity</h5>
            </div>
            <div class="wg-table">
                <ul class="flex flex-column gap14">


                    @foreach($activities as $activity)
                    <li class="product-item p-3">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="#E76C21" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-activity">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                            </svg>

                        </div>
                        <div class="flex items-center justify-between flex-grow">

                            <div>
                                <a href="" class="body-title-2">{{$activity->description}} {{(optional($activity->subject)->name) ? ' - '.$activity->subject->name : ''}}</a>
                                <br>
                                <div title="{{$activity->created_at}}" class="text-tiny mt-3">{{$activity->created_at->diffForHumans()}}</div>

                            </div>


                        </div>
                    </li>

                    @endforeach

                </ul>
            </div>
        </div>

        @endif

    </div>

@endsection



@section('scripts')
    <script>
        $('.menu_dashboard').addClass('active');
    </script>
@endsection
