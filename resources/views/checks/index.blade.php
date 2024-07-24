@extends('layout')
@section('title','Government Checks')

@section('content')

    <div class="flex items-center flex-wrap justify-between gap20 mb-5">
        <div class="mb-20">
            <h4>Government Checks</h4>
            <p>You can easily verify identification documents here</p>
        </div>
    </div>

    <div>
        <div class="widget-tabs">
            @include('checks._nav')
            <div class="widget-content-tab">
                <div class="widget-content-inner active">
                    <p>

                        @livewire('kyc')

                    </p>
                </div>
               {{-- <div class="widget-content-inner">
                    <p>
                        @livewire('api-settings')
                    </p>
                </div>
--}}
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $('.menu_checks').addClass('active');
    </script>
@endsection
