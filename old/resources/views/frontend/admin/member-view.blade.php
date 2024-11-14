@extends('frontend.layout.main')
@section('content')

    <div class="container">
        <div class="content-section people-page">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">{{$member->roles->first()->name}}</a></li>
                            @if($member)<li class="breadcrumb-item active" aria-current="page">{{$member->name}}</li>@endif
                        </ol>
                    </nav>
                </div>
            </div>

            @if($member)
                <div class="row profile">

                    <div class="col-md-3 col-sm-12 image">
                        <div class="card">

                            <img class="card-img-top img-fluid rounded-circle mx-auto d-block lazy" data-src="{{asset("storage/image/administration/$member->image_url")}}" alt="{{$member->name}}">

                            <div class="card-body">
                                <h6>
                                    <a href="#">{{$member->name}}</a>
                                    <br>{{$member->roles->first()->name}}<br>
                                    <small>
                                        {{$member->designation}}@if($member->department), {{$member->department}}@endif
                                    </small>
                                </h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9 col-sm-12">
                        @if($member->message)<h6>Message</h6>{!! $member->message !!}@endif
                        @if($member->address)<h6>Address</h6>{!! $member->address !!}@endif
                    </div>

                </div>
            @endif

        </div>
    </div>

@endsection