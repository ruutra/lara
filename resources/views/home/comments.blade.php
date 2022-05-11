<?php
$id = \Illuminate\Support\Facades\Route::getCurrentRoute()->parameter('id');
?>
@extends('layouts.app-master')
@section('content')
    @auth
        <div class="bg-light p-5 rounded">
            <form method="post" action="{{ route('comment.add', ['id' => $id]) }}">

                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                @include('layouts.partials.messages')

                <div class="form-group form-floating mb-3">
                    <input type="text" class="form-control" name="text" value="{{ old('text') }}" required="required">
                    @if ($errors->has('text'))
                        <span class="text-danger text-left">{{ $errors->first('text') }}</span>
                    @endif
                </div>

                <button class="w-100 btn btn-lg btn-primary" type="submit">Добавить комментарий</button>
            </form>
        </div>
    @endauth
    <h2>Коментарии профиля</h2>
        @foreach($comments as $comment)
            <a>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $comment['text'] }}
                    <form method="post" href="{{route('comment.delete',$comment->id)}}">
                        {{method_field('DELETE')}}
                        @csrf
                        <input type="submit" class="btn btn-danger" value="Delete"/>
                    </form>
                </li>
        @endforeach
@endsection

