<?php

use Illuminate\Support\Facades\Route;

$id = (int)Route::getCurrentRoute()->parameter('id');
?>
@extends('layouts.app-master')
@section('content')
    @if(isset($book))
        Книга: {{ $book->name }}
        <hr>
        @if(auth()->user() && auth()->user()->id === $book->user_id)
            <label>
                Доступ по ссылке
                <input id="public-access" type="checkbox" name="public" data-book-id="{{ $book->id }}" @if($book->public) checked @endif />
            </label>
            <hr>
            <form method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input type="hidden" name="id" value="{{ $book->id }}" />
                    <p>
                        <a class="btn btn-success" data-bs-toggle="collapse" href="#collapseExample{{$book->id}}" role="button" >
                            Редактировать книгу
                        </a>
                    </p>
                <div class="collapse" id="collapseExample{{$book->id}}">
                    <div class="col-12">
                        <label class="visually-hidden" for="inlineFormInputGroupUsername">comment</label>
                        <input type="text" class="form-control" id="inlineFormInputGroupUsername" name="text" value="{{$book->text }}" placeholder="comment">
                    </div>
                    <div class="col-12">
                        <input type="submit" formaction="{{route('book.edit') }}" class="btn btn-success" data-bs-toggle="collapse" value="Сохранить">
                    </div>
                </div>
            </form>
            <hr>
        @endif
        {{$book->text }}
    @endif
@endsection
