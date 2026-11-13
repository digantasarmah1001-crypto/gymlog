{{-- Laravel shows this view automatically for a 429 error. --}}
@extends('layouts.error')

@section('code', '429')

@section('title')
    Easy, champ
@endsection

@section('message')
    Too many attempts in a short time. Take a minute to breathe, then try again.
@endsection
