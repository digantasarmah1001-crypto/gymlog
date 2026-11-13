{{-- Laravel shows this view automatically for a 419 error. --}}
@extends('layouts.error')

@section('code', '419')

@section('title')
    Session expired
@endsection

@section('message')
    The page was open too long, so your form expired for security reasons. Go back, refresh and try again.
@endsection
