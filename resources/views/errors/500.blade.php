{{-- Laravel shows this view automatically for a 500 error. --}}
@extends('layouts.error')

@section('code', '500')

@section('title')
    Something broke
@endsection

@section('message')
    An unexpected error happened on our side. It has been logged, please try again later.
@endsection
