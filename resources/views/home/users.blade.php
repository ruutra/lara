@extends('layouts.app-master')
@section('content')
        <h2>Пользователи</h2>
        @if (isset($users))
                @foreach($users as $user)
                    <li><a class="link-primary" href="{{$user->id}}">{{$user->username}}</a></li>
                @endforeach
        @endif
@endsection
