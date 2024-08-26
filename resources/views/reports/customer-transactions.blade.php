@extends('layout')
@section('title','Customer Transactions')

@section('content')

        @livewire('customer-transactions')

@endsection

@section('scripts')
    <script>
        $('.menu_reports').addClass('active');
    </script>
@endsection
