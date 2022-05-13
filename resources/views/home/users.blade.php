@extends('layouts.app-master')
@section('content')
    @auth
        <h2>Пользователи</h2>
        @if (isset($users))
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                @foreach($users as $user)
                    <li><a class="dropdown-item" href="{{$user->id}}">{{$user->username}}</a></li>
                @endforeach
            </ul>
        @endif
    @endauth
@endsection
