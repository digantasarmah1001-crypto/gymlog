{{-- Laravel shows this view automatically for a 503 error. --}}
@extends('layouts.error')

@section('code', '503')

@section('title')
    Back soon
@endsection

@section('message')
    GymLog is down for maintenance. Rest day!
@endsection
