<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

$id = (int)Route::getCurrentRoute()->parameter('id') ?? Auth::user()->id;
?>
@extends('layouts.app-master')
@section('content')
    <h1>Библиотека</h1>
    @if (auth()->user()->id !== $id)
        @if ($access)
            <a class="link-primary link-danger" href="{{ route('access.disable', ['id' => $id]) }}">
                Забрать доступ
            </a>
        @else
            <a class="link-primary link-success" href="{{ route('access.enable', ['id' => $id]) }}">
                Дать доступ
            </a>
        @endif
    @endif
    @if (auth()->user()->id === $id)
        <form method="post" action="{{ route('library.add', ['id' => $id]) }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            @include('layouts.partials.messages')
            <label for="name">Название</label>
            <div class="form-group form-floating mb-3">
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required="required">
                @if ($errors->has('name'))
                    <span class="text-danger text-left">{{ $errors->first('name') }}</span>
                @endif
            </div>
            <label for="text">Текст</label>
            <div class="form-group form-floating mb-3">
                <input type="text" class="form-control" name="text" value="{{ old('text') }}" required="required">
                @if ($errors->has('text'))
                    <span class="text-danger text-left">{{ $errors->first('text') }}</span>
                @endif
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">Добавить книгу</button>
        </form>
    @endif

    <hr>

    @if(isset($books))
        @foreach($books as $book)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Книга: <a href="{{ route('book.read', ['id' => $book->id]) }}">{{ $book->name }}</a>

                @if (auth()->user()->id === $id)
                    <form method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="submit" formaction="{{route('book.delete',$book->id)}}" class="btn btn-danger" value="Delete"/>
                    </form>
                    <label>
                        Доступ по ссылке
                        <input class="public-access" type="checkbox" name="public" data-book-id="{{ $book->id }}" @if($book->public) checked @endif />
                    </label>
                @endif
            </li>
        @endforeach
    @else
        Доступ запрещен
    @endif
@endsection
