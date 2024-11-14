@extends('frontend.layout.main')

@section('content')

    <div class="container">
        <div class="content-section officer-page">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('')}}">Home</a></li>
                            @if($page)
                            <li class="breadcrumb-item active" aria-current="page">{{$page->title}}</li>
                            @endif
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    @if($page) {!! $page->content !!} @endif
                </div>
            </div>

        </div>
    </div>

@endsection
