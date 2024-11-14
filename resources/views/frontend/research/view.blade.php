@extends('frontend.layout.main')
@section('content')

    <div class="container">
        <div class="content-section">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            @if($research)
                                @if($research->department()->exists() && $research->department->faculty()->exists() && $research->department->faculty->name == 'FACULTY')
                                    <li class="breadcrumb-item"><a href="{{route('Frontend::department::view', ['slug' => $research->department->slug])}}">{{$research->department->name}}</a></li>
                                @else
                                    <li class="breadcrumb-item"><a href="{{route('Frontend::institute::view', ['slug' => $research->department->slug])}}">{{$research->department->name}}</a></li>
                                @endif
                                <li class="breadcrumb-item active" aria-current="page">{{$research->name}}</li>
                            @endif
                        </ol>
                    </nav>
                </div>
            </div>

            @if($research)
                <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title text-center">{{$research->name}}</h4>
                            <h4 class="card-title text-center">{{$research->department->name}}</h4>

                            <p class="card-text">
                            {!! $research->description !!}
                            </p>
                            <br/><br/>

                            @if($research->message_from_editor)
                            <div>
                                <h6>Message from editor</h6>
                                {!! $research->message_from_editor !!}
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

@endsection