@extends('layouts.builder')

@section('content')
    <livewire:builder.visual-page-builder :product-id="$productId" :page-id="$pageId" />
@endsection
