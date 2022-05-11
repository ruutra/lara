@extends('layouts.app-master')

@section('content')

    <div class="bg-light p-5 rounded">
        @auth
            <h1>My profile</h1>
            <p class="lead">Comments</p>
        @endauth

        @guest
            <h1>Homepage</h1>
            <p class="lead">Your viewing the home page. Please login to view the restricted data.</p>
        @endguest
    </div>
@endsection

