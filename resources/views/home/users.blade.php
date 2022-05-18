@extends('layouts.app-master')
@section('content')
        <h2>Пользователи</h2>
        @if (isset($users))
                @foreach($users as $user)
                    <li>
                        {{$user->username}}
                        <a class="link-primary" href="{{ route('comment.get', ['id' => $user->id]) }}">
                            Комментарии
                        </a>
                        |
                        <a class="link-primary" href="{{ route('library.get', ['id' => $user->id]) }}">
                            Книги
                        </a>
                    </li>
                @endforeach
        @endif
@endsection
