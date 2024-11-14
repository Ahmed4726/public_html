@extends('frontend.layout.main')

@section('content')

    <div class="container">
        <div class="content-section people-page">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            @if($department && $department->faculty()->exists())
                                @if($department->faculty->type == 'INSTITUTE')
                                    <li class="breadcrumb-item"><a
                                                href="{{route('Frontend::institute::view', ['slug' => $department->slug])}}">{{$department->name}}</a>
                                    </li>
                                @else
                                    <li class="breadcrumb-item"><a
                                                href="{{route('Frontend::department::view', ['slug' => $department->slug])}}">{{$department->name}}</a>
                                    </li>
                                @endif
                            @endif
                            <li class="breadcrumb-item active" aria-current="page">Message from Chairman</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @if($department)
                <div class="row profile">
                    <div class="col-md-12">

                        <div class="col-md-3 col-sm-12 image">
                            <div class="card">

                                @if($department->chairman()->exists())
                                    <img class="card-img-top img-fluid rounded-circle mx-auto d-block lazy"
                                         data-src="{{$department->chairman->real_image_path}}" alt="{{$department->chairman->name}}">

                                    <div class="card-body">
                                        <h6>
                                            <a href="#">{{$department->chairman->name}}</a>
                                            <small>@if($department->chairman->designationInfo()->exists()) {{$department->chairman->designationInfo->name}},@endif {{$department->name}}</small>
                                        </h6>
                                    </div>

                                @else
                                    <img class="card-img-top img-fluid rounded-circle mx-auto d-block lazy" data-src="{{asset('images/default-img-person.jpg')}}">

                                    <div class="card-body">
                                        <h6>
                                            <a href="#">Chairman</a>
                                            <small>Information will be updated soon!</small>
                                        </h6>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div style="padding: 10px;">
                            {!! $department->message_from_chairman !!}
                            <br>
                            @if($department->chairman()->exists()) <strong>{{$department->chairman->name}}</strong> @endif
                        </div>

                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection