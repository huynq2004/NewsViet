@extends('layouts.reader')

@section('content')
    <h2>{{ $article->title }}</h2>
    <p>{{ $article->content }}</p>
@endsection

