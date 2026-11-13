{{-- Laravel shows this view automatically for a 404 error. --}}
@extends('layouts.error')

@section('code', '404')

@section('title')
    Missed the rep
@endsection

@section('message')
    We couldn't find that page. Maybe it was deleted, or the link is wrong.
@endsection
