@extends('frontend.layout.main')
@section('content')

    <div class="container hall-page">
        <div class="content-section facility-page">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Accommodations</a></li>
                            @if($hall)<li class="breadcrumb-item active" aria-current="page">{{$hall->name}}</li>@endif
                        </ol>
                    </nav>
                </div>
            </div>

            @if($hall)
                <div class="row">
                    <div class="col-md-12">
                        <h4>{{$hall->name}}</h4>
                    </div>
                    <div class="col-md-12">
                        <hr/>
                    </div>
                </div>


                <div class="row hall-page">

                    <div class="col-md-3">
                        <div class="card">
                            @if($hall->teacher()->exists())
                            <img class="card-img-top img-fluid rounded-circle mx-auto d-block lazy" data-src="{{$hall->teacher->real_image_path}}" alt="{{$hall->teacher->name}}">
                            <div class="card-body text-center">
                                <p>
                                    Provost
                                    <br/>
                                    <a href="{{route("Frontend::teacher::view", ['slug' => ($hall->teacher->slug) ? $hall->teacher->slug : Laralum::getUserNameFromEmail($hall->teacher->email)])}}">{{$hall->teacher->name}}</a><br/>
                                    @if($hall->teacher->department()->exists())<small>{{$hall->teacher->department->name}}</small>@endif
                                </p>
                            </div>
                            @else
                                <img class="card-img-top img-fluid rounded-circle mx-auto d-block lazy" data-src="{{asset('images/default-img-person.jpg')}}">
                            @endif
                        </div>
                    </div>


                    <div class="col-md-9" id="hall-image">
                        <img data-src="{{$hall->real_image_path}}" class="img-fluid lazy" alt="{{$hall->name}}">

                        @if((!$hall->image_url))
                        <div class="caption text-center " style="background-color: transparent">
                            <h4 style="color: #FFFFFF;">{{$hall->name}}</h4>
                        </div>
                        @endif

                    </div>
                </div>
                <hr/>
                <div class="row">
                    @if($hall->message_from_provost)
                    <div class="col-md-12">
                        <h6>Message from Provost</h6>
                        {!! $hall->message_from_provost !!}
                    </div>
                    @endif
                </div>
                <hr/>

                <div class="row">
                    <div class="col-md-12">
                        {!! $hall->description !!}
                    </div>
                </div>

            @endif


            <div class="row">
                <div class="col-md-12">
                </div>
            </div>
        </div>
    </div>

@endsection

@section('headerStyle')
    <style type="text/css">
        .thumbnail {
            position: relative;
        }

        .caption {
            position: absolute;
            top: 45%;
            left: 0;
            width: 100%;
        }
    </style>
@endsection