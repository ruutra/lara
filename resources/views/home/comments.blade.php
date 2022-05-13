<?php
$id = \Illuminate\Support\Facades\Route::getCurrentRoute()->parameter('id');
//dd($comments);
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
    <h2>Комментарии профиля</h2>
        @foreach($comments as $comment)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>{{ $comment->username }}</div>
                    {{ $comment->text }}
                    <form method="post" action="{{route('comment.delete',$id) }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="id" value="{{ $comment->id }}" />
                        @include('layouts.partials.messages')
                        @if (auth()->user()->id === $comment->author_id)
                            @method('DELETE')
                            <input type="submit" class="btn btn-danger" value="Delete"/>
                        @endif
                        <form method="post" action="{{route('comment.reply',$id) }}">
                            @if (auth()->user()->id != $comment->author_id)
                            <p>
                                <a class="btn btn-success" data-bs-toggle="collapse" href="#collapseExample" role="button" >
                                    Answer comment
                                </a>
                            </p>
                            <div class="collapse" id="collapseExample">
                                <div class="col-12">
                                    <label class="visually-hidden" for="inlineFormInputGroupUsername">comment</label>
                                    <input type="text" class="form-control" id="inlineFormInputGroupUsername" value="{{$comment->text}}," placeholder="comment">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                            @endif
                        </form>
                    </form>
                </li>
        @endforeach
@endsection

