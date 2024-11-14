@extends('frontend.layout.main')
@section('content')

    <div class="container">
        <div class="content-section faculty-page">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>


                            @if($facility && $facility->center()->exists()) <li class="breadcrumb-item"><a href="{{route('Frontend::center::view', ['slug' => $facility->center->slug])}}">{{$facility->center->name}}</a></li>

                            @elseif($facility && $facility->department()->exists())
                                @if($facility->department->faculty()->exists())
                                    @if($facility->department->faculty->type == "FACULTY")
                                        <li class="breadcrumb-item"><a href="{{route('Frontend::department::view', ['slug' => $facility->department->slug])}}">{{$facility->department->name}}</a></li>
                                    @elseif($facility->department->faculty->type == "INSTITUTE")
                                        <li class="breadcrumb-item"><a href="{{route('Frontend::institute::view', ['slug' => $facility->department->slug])}}">{{$facility->department->name}}</a></li>
                                    @endif
                                @endif
                            @else <li class="breadcrumb-item"><a href="#">Campus Life</a></li> @endif

                            @if($facility)<li class="breadcrumb-item active" aria-current="page">{{$facility->name}}</li>@endif
                        </ol>
                    </nav>
                </div>
            </div>

            @if($facility)
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        @if($facility->image_url)<img class="card-img-top img-fluid lazy" alt="" src="{{asset("storage/image/facility/$facility->image_url")}}" style="">@endif
                        <div class="card-body">
                            <h4 class="card-title text-center">{{$facility->name}}</h4>

                            @if($facility && $facility->center()->exists())
                                <h4 class="card-title text-center">{{$facility->center->name}}</h4>

                            @elseif($facility && $facility->department()->exists())
                                <h4 class="card-title text-center">{{$facility->department->name}}</h4>
                            @endif

                            <p class="card-text">{!! $facility->description !!}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

@endsection