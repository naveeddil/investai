@extends('layouts.public')

@section('content')
    @foreach ($page->blocks ?? [] as $block)
        @include('pages.blocks.section', ['block' => $block])
    @endforeach
@endsection
