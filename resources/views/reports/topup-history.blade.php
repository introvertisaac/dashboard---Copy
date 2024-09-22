@extends('layout')
@section('title','Topup History')

@section('content')

        @livewire('topup-history')

@endsection

@section('scripts')
    <script>
        $('.menu_reports').addClass('active');
    </script>
@endsection
