@extends('layouts.mini')

@section('content')
    @if (Auth::check())
        <create-workout></create-workout>
    @endif
@endsection