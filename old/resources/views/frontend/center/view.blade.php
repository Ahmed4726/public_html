@extends('frontend.layout.main')
@section('content')

<div class="container">
    <div class="content-section facility-page">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('')}}">Home</a></li>
                        <li class="breadcrumb-item"><a
                                href="#">@if($center){{ucfirst(strtolower($center->type))}}@endif</a></li>
                        @if($center) <li class="breadcrumb-item active" aria-current="page">{{$center->name}}</li>
                        @endif
                    </ol>
                </nav>
            </div>
        </div>

        @if($center)
        <div class="row">
            <div class="col-md-12">
                <h4>{{$center->name}}</h4>
                <hr />
            </div>
        </div>

        <div class="row people-page">
            @if($center->teacher()->exists())
            <div class="col-md-3">
                <div class="card">
                    <img class="card-img-top img-fluid rounded-circle mx-auto d-block lazy"
                        data-src="{{$center->teacher->real_image_path}}" alt="{{$center->teacher->name}}">
                    <div class="card-body">
                        <h6>
                            <a
                                href="{{route("Frontend::teacher::view", ['slug' => ($center->teacher->slug) ? $center->teacher->slug : Laralum::getUserNameFromEmail($center->teacher->email)])}}">{{$center->teacher->name}}</a>
                            @if($center->teacher->department()->exists())<small>{{$center->teacher->department->name}}</small>@endif
                        </h6>
                    </div>
                </div>
            </div>
            @elseif($center->director_name)
            <div class="col-md-3">
                <div class="card">
                    <img class="card-img-top img-fluid rounded-circle mx-auto d-block lazy"
                        @if($center->director_image_url)
                    data-src="{{asset("storage/image/center/$center->director_image_url")}}"
                    @else data-src="{{asset('images/default-img-person.jpg')}}" @endif
                    alt="{{$center->director_name}}">
                    <div class="card-body">
                        <h6>
                            <a href="#">{{$center->director_name}}</a>
                        </h6>
                    </div>
                </div>
            </div>
            @else
            <div class="col-md-3">
                <div class="card">
                    <img class="card-img-top img-fluid rounded-circle mx-auto d-block lazy"
                        data-src="{{asset('images/default-img-person.jpg')}}">
                </div>
            </div>
            @endif

            <div class="col-md-9">
                @if($center->teacher()->exists())
                <h6>{{$center->director_label}}</h6>
                <p>{{$center->teacher->name}}</p> @endif
                @if($center->message_from_director)
                @if($center->director_msg_label)
                <h6>{{$center->director_msg_label}}</h6>
                @else
                <h6>Message from {{$center->director_label}}</h6>
                @endif
                @endif
                {!! $center->message_from_director !!}
            </div>
        </div>
        <hr />

        <div class="row">
            <div class="col-md-12">
                {!! $center->description !!}
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
            </div>
        </div>

        <div class="row mb-3">
            @if($center->programs()->exists())
            <div class="col-md-4">
                <div class="card text-white bg-primary center-card">
                    <div class="card-header">{{$center->config['service'] ?? 'Services / Facilities'}}</div>
                    <ul class="list-group list-group-flush">
                        @foreach($center->programs as $program)
                        <li class="list-group-item">
                            <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            <a @if($program->external_link) href="{{$program->external_link}}"
                                @else
                                href="{{route("Frontend::".strtolower($center->type)."::program::view", ['center' =>$center->slug, 'program' => ($program->slug)? $program->slug : $program->id])}}"
                                @endif>{{$program->name}}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @if($center->files->isNotEmpty())
            <div class="col-md-4">
                <div class="card text-white bg-success center-card">
                    <div class="card-header">{{$center->config['download'] ?? 'Download'}}</div>
                    <ul class="list-group list-group-flush">
                        @foreach($center->files->take(5) as $file)
                        <li class="list-group-item">
                            <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            <a href="@if(!$file->external_link){{route('center::file::view', ['center' => $center->id, 'file' => $file->id])}}
                                            @else {{$file->external_link}} @endif
                                            ">{{$file->name}}</a>
                        </li>
                        @endforeach

                    </ul>
                    @if($center->files->count() > 5)
                    <div class="card-footer bg-white text-right">
                        <a class="card-link"
                            href="{{route("Frontend::download::list", ['center_id' => $center->id])}}"><i
                                class="fa fa-angle-double-right"></i> Click Here to View All</a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            @if($center->officers()->exists())
            <div class="col-md-4">
                <div class="card text-white bg-info center-card">
                    <div class="card-header">{{$center->config['employee'] ?? 'Employee'}}</div>
                    <ul class="list-group list-group-flush">

                        @if($center->officers()->where('type_id', 1)->exists())
                        <li class="list-group-item">
                            <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            <a
                                href="{{route('Frontend::officer::list', ['center_id' => $center->id, 'type_id' => 1])}}">Officer
                                List </a>
                        </li>
                        @endif

                        @if($center->officers()->where('type_id', 2)->exists())
                        <li class="list-group-item">
                            <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            <a
                                href="{{route('Frontend::officer::list', ['center_id' => $center->id, 'type_id' => 2])}}">Staff
                                List</a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
            @endif

        </div>
        @endif
    </div>
</div>

@endsection