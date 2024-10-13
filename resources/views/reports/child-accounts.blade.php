@extends('layout')
@section('title','Child Accounts')

@section('content')

        @livewire('child-accounts')

@endsection

@section('scripts')
    <script>
        $('.menu_reports').addClass('active');
    </script>
@endsection
