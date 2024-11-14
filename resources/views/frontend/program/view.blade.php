@extends('frontend.layout.main')
@section('content')

    <div class="container">
        <div class="content-section">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            @if($program && $program->center_id && $program->center()->exists()) <li class="breadcrumb-item">{{$program->center->name}}</li>

                            @elseif($program && $program->department_id && $program->department()->exists())
                                @if($program->department->faculty()->exists())
                                    @if($program->department->faculty->type == "FACULTY")
                                        <li class="breadcrumb-item"><a href="{{route('Frontend::department::view', ['slug' => $program->department->slug])}}">{{$program->department->name}}</a></li>
                                    @elseif($program->department->faculty->type == "INSTITUTE")
                                        <li class="breadcrumb-item"><a href="{{route('Frontend::institute::view', ['slug' => $program->department->slug])}}">{{$program->department->name}}</a></li>
                                    @endif
                                @endif

                            @elseif($program && $program->hall_id && $program->hall()->exists()) <li class="breadcrumb-item"><a href="{{route('Frontend::hall::view', ['slug' => $program->hall->slug])}}">{{$program->hall->name}}</a></li>
                            @else <li class="breadcrumb-item"><a href="#">Admission</a></li> @endif
                            @if($program)<li class="breadcrumb-item active" aria-current="page">{{$program->name}}</li>@endif
                        </ol>
                    </nav>
                </div>
            </div>

            @if($program)
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-center">{{$program->name}}</h4>
                                {{--@if($program->center_id && $program->center()->exists()) <h4 class="card-title text-center">{{$program->center->name}}</h4>@endif
                                @if($program->department_id && $program->department()->exists()) <h4 class="card-title text-center">{{$program->department->name}}</h4>@endif
                                @if($program->hall_id && $program->hall()->exists()) <h4 class="card-title text-center">{{$program->hall->name}}</h4>@endif--}}

                                <p class="card-text">
                                    {!! $program->description !!}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection