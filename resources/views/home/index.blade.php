@extends('layouts.app-master')
@section('content')
    <div class="bg-light p-5 rounded">
        @guest
            <h1>Привет!</h1>
            <p class="lead">Перед тем как оставлять комментарии,тебе нужно пройти регистрацию)</p>
        @endguest
    </div>
@endsection

