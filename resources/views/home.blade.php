@extends('layouts.app')

@section('content')

    <list-conversations :conversations="{{ $conversations }}" :user="{{ $user }}"></list-conversations>

@endsection
