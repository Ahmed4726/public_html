@extends('frontend.layout.main')
@section('content')

    <div class="container">
        <div class="content-section hall-page">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Research</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @if($researches)
                <div class="row">
                    <div class="col-md-12">
                        @foreach($researches as $research)
                            <p>
                                <a @if($research->website_link) href="{{$research->website_link}}" @else
                                    href="{{route('Frontend::research::view', ['research' => $research->id])}}" @endif>{{$research->name}}</a>
                            </p>
                        @endforeach
                        {{-- {{$researches->links()}} --}}
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection