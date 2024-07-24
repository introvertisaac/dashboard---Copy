@extends('layout')
@section('title','Customers')

@section('content')
    @livewire('customers')
@endsection

@section('scripts')
    <script>
        $('.menu_customers').addClass('active');
    </script>
@endsection
