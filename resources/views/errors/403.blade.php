{{-- Laravel shows this view automatically for a 403 error. --}}
@extends('layouts.error')

@section('code', '403')

@section('title')
    Not your workout
@endsection

@section('message')
    You don't have permission to see or change this. Workouts are private to the athlete who logged them.
@endsection
