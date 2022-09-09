@extends('layouts.app')

@section('title-block')Страница главная@endsection

@section('content')
    <h1>Главная</h1>
@endsection

@section('aside')
    @parent
    <p>Доп. текст</p>
@endsection