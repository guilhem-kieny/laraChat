@extends('layouts.app')

@section('content')
    <v-card-title class="header-container">
        <v-icon class="robot-icon">mdi-robot</v-icon>
        <h1 class="app-title">Larachat</h1>
        <v-btn class="btn-transparent"><v-icon>mdi-menu</v-icon></v-btn>
    </v-card-title>
    <list-conversations :conversations="{{ $conversations }}" :user="{{ $user }}"></list-conversations>

@endsection
