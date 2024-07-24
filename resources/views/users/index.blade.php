@extends('layout')
@section('title','Users')

@section('content')
    @livewire('users')
@endsection

@section('scripts')
    <script>
        $('.menu_users').addClass('active');
    </script>
@endsection
