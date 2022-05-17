<?php
$id = \Illuminate\Support\Facades\Route::getCurrentRoute()->parameter('id');
?>
<div class="comments-wrapper">
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
    <h2>Комментарии профиля</h2>
        @foreach($comments as $comment)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                @if ($comment->parent_id)
                    Сообщение от
                @endif
                <div>{{ $comment->username }}</div>
                {{ $comment->text }}
                <form method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="id" value="{{ $comment->id }}" />
                    @include('layouts.partials.messages')
                    @auth()
                    @if (auth()->user()->id === $comment->author_id)
                        <input type="submit" formaction="{{route('comment.delete',$id)}}" class="btn btn-danger" value="Delete"/>
                    @endif
                    <p>
                        <a class="btn btn-success" data-bs-toggle="collapse" href="#collapseExample{{$comment->id}}" role="button" >
                            Answer comment
                        </a>
                    </p>
                    @endauth
                    <div class="collapse" id="collapseExample{{$comment->id}}">
                        <div class="col-12">
                            <label class="visually-hidden" for="inlineFormInputGroupUsername">comment</label>
                            <input type="text" class="form-control" id="inlineFormInputGroupUsername" name="text" value="{{$comment->username}}," placeholder="comment">
                        </div>
                        <div class="col-12">
                            <input type="submit" formaction="{{route('comment.reply',$id) }}" class="btn btn-success" data-bs-toggle="collapse" value="Submit">
                        </div>
                    </div>
                </form>
            </li>
        @endforeach
    <div class="col-12">
        <input type="submit" class="btn btn-success" id="getAllComments" data-bs-toggle="collapse" value="Загрузить комментарии">
    </div>
</div>
@endsection

