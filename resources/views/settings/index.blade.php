@extends('layout')
@section('title','Account Settings')

@section('content')

    <div class="flex items-center flex-wrap justify-between gap20 mb-5">
        <div class="mb-20">
            <h4>Settings</h4>
            <p>Manage APIs, user and notifications here</p>
        </div>
    </div>



    <div>
        <div class="widget-tabs">
            @include('settings._nav')
            <div class="widget-content-tab">
                <div class="widget-content-inner active">
                    <p>
                        @livewire('api-settings')
                    </p>
                </div>

                <div class="widget-content-inner">
                    <p>

                      @livewire('account-settings')

                    </p>
                </div>

                <div class="widget-content-inner">
                    <p>
                        @livewire('password-settings')
                    </p>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $('.menu_settings').addClass('active');
    </script>
@endsection
