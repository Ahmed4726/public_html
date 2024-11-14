@extends('frontend.layout.main')
@section('content')

    <div class="container">
        <div class="content-section administration-page">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pro Vice Chancellor</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">

                @foreach($members as $member)
                    <div class="col-md-3" style="height: 400px;">
                        <div class="card">
                            <img class="card-img-top img-fluid mx-auto d-block lazy" data-src="{{asset("storage/image/administration/$member->image_url")}}" alt="{{$member->name}}">
                            <div class="card-body">
                                <h6>
                                    <a href="{{route("Frontend::administration::profile", ['admin' => $member->id])}}">{{$member->name}}</a>
                                    <small>{{$member->designation}}</small>

                                    <small>
                                        {{$member->department}}
                                    </small>
                                </h6>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

@endsection