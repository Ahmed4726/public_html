@extends('frontend.layout.main')
@section('content')

<div class="container">
    <div class="content-section">
        {{-- <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>

                        @if($discussion)
                        @if($discussion->faculty_id()->exists())
                        @if($discussion->department->faculty()->exists())
                        <li class="breadcrumb-item"><a
                                href="{{route('Frontend::faculty::view', ['slug' => $discussion->department->slug])}}">{{$discussion->department->name}}</a>
                        </li>
                        @else
                        <li class="breadcrumb-item"><a
                                href="{{route('Frontend::department::view', ['slug' => $discussion->department->slug])}}">{{$discussion->department->name}}</a>
                        </li>
                        @endif
                        @else
                        <li class="breadcrumb-item"><a
                                href="{{route('Frontend::event::list', ['event_id' => $discussion->event->id])}}">{{$discussion->event->name}}</a>
                        </li>
                        @endif
                        <li class="breadcrumb-item active" aria-current="page">{!! $discussion->title !!}</li>
                        @endif
                    </ol>
                </nav>
            </div>
        </div> --}}

        @if($discussion)
        <div class="row">
            <div class="col-md-8 topic-details">
                <div class="card">
                    <div class="card-header text-center">
                        {!! $discussion->title !!}
                        <br>
                        @if($discussion->publish_date)
                        <small>
                            <i class="fas fa-clock"></i> {{$discussion->publish_date}}
                        </small>
                        @endif
                    </div>

                    <div class="card-body">
                        {{-- <div class="row image mb-3">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                @if($discussion->image_url) <img class="lazy"
                                    data-src="{{$discussion->real_image_path}}" title="image" /> @endif
                            </div>
                            <div class="col-md-3"></div>
                        </div> --}}

                        <div class="row details">
                            <div class="col-md-12">
                                {!! $discussion->details !!}
                            </div>
                        </div>

                        
                        
                        <embed
                            src="{{ asset('storage/image/faculty_event/' . $discussion->file) }}"
                            width="100%" height="842px"
                            {{--                                       type="application/pdf"--}}
                            title="{{$discussion->title}}">
                       
                        

                       

                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header">
                        Recent Activities
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach($relatedDiscussion->take(10) as $recent)
                        @if ($recent->enabled=='on')
                        <li class="list-group-item"><a @if($recent->external_link) href="{{$recent->external_link}}"
                                @else href="{{route('Frontend::faculty::facultyEventview', ['discussion' => $recent->id])}}" @endif>
                                @if($recent->highlight)<i
                                    class="fa fa-asterisk no-padding no-margin animated infinite flash submenu"></i>@endif
                                {!! $recent->title !!}</a></li>
                        @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

@endsection