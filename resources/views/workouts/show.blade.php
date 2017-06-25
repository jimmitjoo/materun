@extends('layouts.mini')

@section('content')
    <show-workout :id="{{ $id }}"></show-workout>

    <div id="map" style="width: 100%; height: 300px"></div>
@endsection