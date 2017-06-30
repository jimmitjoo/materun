@extends('layouts.mini')

@section('body_class', 'test')

@section('content')
    @if (Auth::check())
        <create-workout></create-workout>
    @endif
@endsection