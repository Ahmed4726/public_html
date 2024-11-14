@extends('frontend.layout.main')

@section('content')

<div class="container mb-4">
    <div class="row lg-links-left">

        <div class="col-lg-2">
            <ul class="list-unstyled">
                <li class="d-inline">
                    <a href="{{url('/custom/slug/about-ju')}}" class="d-inline">
                        <div class="link-div">
                            <i class="far fa-lightbulb link-icon"></i>
                            <span>ABOUT JU</span>
                        </div>
                    </a>
                </li>
                <li class="d-inline">
                    <a href="{{url('/page/vision-mission')}}" class="d-inline">
                        <div class="link-div">
                            <i class="fa fa-users link-icon"></i>
                            <span>VISION & MISSION</span>
                        </div>
                    </a>
                </li>
                <li class="d-inline">
                    <a href="{{url('/academic-calendar')}}" class="d-inline">
                        <div class="link-div">
                            <i class="far fa-calendar-alt link-icon"></i>
                            <span>CALENDAR</span>
                        </div>
                    </a>
                </li>

                <li class="d-block">
                    <a href="{{url('/gallery')}}" class="d-inline">
                        <div class="link-div">
                            <i class="fas fa-images link-icon"></i>
                            <span>GALLERY</span>
                        </div>
                    </a>
                </li>

            </ul>
        </div>

        <div class="col-lg-7 carousel-container">

            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    @foreach($images as $key => $image)
                    <li data-target="#myCarousel" data-slide-to="{{$key}}" @if($key=='0' ) class="active" @endif></li>
                    @endforeach
                </ol>

                <div class="carousel-inner">

                    @foreach($images as $key => $image)

                    <div class="carousel-item @if($key == '0') active @endif">
                        @if($image->external_link) <a href="http://www.juniv.edu/discussion/10732" target="_blank">
                            @endif
                            <img class="first-slide lazy" src="{{ asset("storage/image/gallery/$image->path") }}"
                                alt="Banner Image">

                            @if($image->title)
                            <div class="carousel-caption">
                                <h5>{!! $image->title !!}</h5>
                            </div>
                            @endif
                            @if($image->external_link) </a> @endif
                    </div>

                    @endforeach

                </div>
                <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>

        <div class="col-lg-3 vice-chancellor-container">
            <a class="" href="{{url('/administration?roleType=VICE_CHANCELLOR&roleName=Vice Chancellor')}}">
                <div class="vice-chancellor">

                    <img src="{{asset("storage/image/administration/{$viceChancellor->member->image_url}")}}"
                        alt="{{$viceChancellor->role->name}}">
                    <div>
                        <h5 class="text-center">{{$viceChancellor->member->name}}
                            <br />
                            <small>{{$viceChancellor->role->name}}</small>
                        </h5>
                        {!! \Illuminate\Support\Str::limit($viceChancellor->member->message, 150) !!}
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

@if($spotlights->count() > 0)
<div class="container mb-4">
    <div class="breakingNews simple-marquee-container">
        <div class="marquee-sibling bn-title">
            <h2>JU Spotlights</h2><span></span>
        </div>
        <div class="marquee">
            <ul class="marquee-content-items">
                @foreach($spotlights as $spotlight)
                <li><a @if($spotlight->external_link) href="{{$spotlight->external_link}}" target="_blank" @else
                        href="{{route('Frontend::event::view', ['discussion' => $spotlight->id])}}" @endif><i
                            class="fa fa-angle-right"></i> {!! $spotlight->title !!}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif




<div class="news-section">

    <div class="container">

        <div class="row">

            <div class="col-lg-4">

                <ul class="nav nav-tabs" id="myTab" role="tablist">

                    @foreach($firstEventSection as $key => $event)
                    <li class="nav-item">
                        <a class="nav-link @if($key == 0) active @endif" id="{{$event->name}}-tab" data-toggle="tab"
                            href="#{{$event->name}}" role="tab" aria-controls="{{$event->name}}"
                            aria-selected="false"><i class="far fa-newspaper"></i> {{$event->name}}</a>
                    </li>
                    @endforeach

                </ul>

                <div class="tab-content" id="myTabContent">

                    @foreach($firstEventSection as $key => $event)

                    <div class="tab-pane @if($key == 0) active show @else fade @endif" id="{{$event->name}}"
                        role="tabpanel" aria-labelledby="{{$event->name}}-tab">

                        <ul class="list-unstyled item-lists">

                            @foreach($event->topics as $topic)
                            <li>
                                <p class="mb-1 bangla-font">
                                    <a @if($topic->external_link) href="{{$topic->external_link}}" target="_blank" @else
                                        href="{{route('Frontend::event::view', ['discussion' => $topic->id])}}" @endif>
                                        @if($topic->highlight)<i
                                            class="fa fa-asterisk no-padding no-margin animated infinite flash submenu"></i>@endif
                                        {!! $topic->title !!}</a>
                                    @if($topic->show_publish_date) <br /> <small><i class="fas fa-clock"></i>
                                        {{$topic->publish_date}}</small> @endif
                                </p>

                            </li>
                            @endforeach

                        </ul>

                        @if($event->topics->isNotEmpty())
                        <div class="row text-center">
                            <div class="col-md-12 text-right">
                                <a href="{{route('Frontend::event::list', ['event_id' => $event->id])}}" class=""><i
                                        class="fa fa-angle-double-right"></i> View All</a>
                            </div>
                        </div>
                        @endif

                    </div>

                    @endforeach

                </div>


            </div>

            <div class="col-lg-4">

                <ul class="nav nav-tabs" id="myTab" role="tablist">

                    @foreach($secondEventSection as $key => $event)
                    <li class="nav-item">
                        <a class="nav-link @if($key == 0) active @endif" id="{{$event->name}}-tab" data-toggle="tab"
                            href="#{{$event->name}}" role="tab" aria-controls="{{$event->name}}"
                            aria-selected="false"><i class="far fa-newspaper"></i> {{$event->name}}</a>
                    </li>
                    @endforeach

                </ul>

                <div class="tab-content" id="newsTabContent">

                    @foreach($secondEventSection as $key => $event)

                    <div class="tab-pane @if($key == 0) active show @else fade @endif" id="{{$event->name}}"
                        role="tabpanel" aria-labelledby="{{$event->name}}-tab">

                        <ul class="list-unstyled item-lists">

                            @foreach($event->topics as $topic)
                            <li>
                                <p class="mb-1 bangla-font">
                                    <a @if($topic->external_link) href="{{$topic->external_link}}" target="_blank" @else
                                        href="{{route('Frontend::event::view', ['discussion' => $topic->id])}}" @endif>
                                        @if($topic->highlight)<i
                                            class="fa fa-asterisk no-padding no-margin animated infinite flash submenu"></i>@endif
                                        {!! $topic->title !!}</a>
                                    @if($topic->show_publish_date) <br /><small><i class="fas fa-clock"></i>
                                        {{$topic->publish_date}}</small> @endif
                                </p>

                            </li>
                            @endforeach

                        </ul>

                        @if($event->topics->isNotEmpty())
                        <div class="row text-center">
                            <div class="col-md-12 text-right">
                                <a href="{{route('Frontend::event::list', ['event_id' => $event->id])}}" class=""><i
                                        class="fa fa-angle-double-right"></i> View All</a>
                            </div>
                        </div>
                        @endif

                    </div>

                    @endforeach

                </div>

            </div>

            <div class="col-lg-4">
                <ul class="nav nav-tabs" id="myTab" role="tablist">

                    @foreach($thirdEventSection as $key => $event)
                    <li class="nav-item">
                        <a class="nav-link @if($key == 0) active @endif" id="{{$event->name}}-tab" data-toggle="tab"
                            href="#{{$event->name}}" role="tab" aria-controls="{{$event->name}}"
                            aria-selected="false"><i class="far fa-calendar-alt"></i> {{$event->name}}</a>
                    </li>
                    @endforeach

                </ul>

                <div class="tab-content" id="newsTabContent">

                    @foreach($thirdEventSection as $key => $event)

                    <div class="tab-pane @if($key == 0) active show @else fade @endif" id="{{$event->name}}"
                        role="tabpanel" aria-labelledby="{{$event->name}}-tab">

                        <ul class="list-unstyled item-lists">

                            @foreach($event->topics as $topic)
                            <li>
                                <p class="mb-1 bangla-font">
                                    <a @if($topic->external_link) href="{{$topic->external_link}}" target="_blank" @else
                                        href="{{route('Frontend::event::view', ['discussion' => $topic->id])}}" @endif>
                                        @if($topic->highlight)<i
                                            class="fa fa-asterisk no-padding no-margin animated infinite flash submenu"></i>@endif
                                        {!! $topic->title !!}</a>
                                    @if($topic->show_publish_date) <br /> <small><i class="fas fa-clock"></i>
                                        {{$topic->publish_date}}</small> @endif
                                </p>

                            </li>
                            @endforeach

                        </ul>

                        @if($event->topics->isNotEmpty())
                        <div class="row text-center">
                            <div class="col-md-12 text-right">
                                <a href="{{route('Frontend::event::list', ['event_id' => $event->id])}}" class=""><i
                                        class="fa fa-angle-double-right"></i> View All</a>
                            </div>
                        </div>
                        @endif

                    </div>

                    @endforeach

                </div>

            </div>

        </div>

    </div>

</div>

<div class="f-departments-section" id="academicFaculties">

    <div class="container text-center">

        <h3 class="home-header faculty-dept">FACULTIES & DEPARTMENTS</h3>

        <div class="row mb-3" id="departments">

            @foreach($faculties as $faculty)

            <div class="col-lg-4">
                <div class="card card-1">
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="{{url("faculty/$faculty->slug")}}">{{$faculty->name}}</a>
                        </h4>
                        <ul>
                            @foreach($faculty->departments as $department)
                            <li>
                                <a href="{{url("department/$department->slug")}}"><i class="fas fa-graduation-cap"></i>
                                    {{$department->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            @endforeach

        </div>

        <h3 class="home-header institute-header">INSTITUTES</h3>
        <div class="row" id="institutes">

            @foreach($instituteDepartment->departments as $department)

            <div class="col-lg-3">
                <div class="card card-2">
                    <div class="card-body">
                        <h6 class="card-title">
                            <a @if($department->external_link) href="{{$department->external_link}}" target="_blank"
                                @else href="{{url("institute/$department->slug")}}" @endif>
                                {{$department->name}}
                            </a>
                        </h6>
                    </div>
                </div>
            </div>

            @endforeach

        </div>

    </div>

</div>

<div class="featured-news mb-3">

    <div class="container text-center">
        <h3 class="home-header feature-header">FEATURE ARTICLE</h3>

        <div class="row" id="feature">

            @foreach($featured as $topic)

            <div class="col-lg-3">
                <div class="card card-2">
                    <div class="card-img">
                        <img class="card-img-top img-fluid lazy" data-src="{{$topic->real_image_path}}"
                            alt="Card image cap">
                    </div>

                    <div class="card-body">
                        <h2 class="card-title">
                            <a @if($topic->external_link) href="{{$topic->external_link}}" target="_blank" @else
                                href="{{route('Frontend::event::view', ['discussion' => $topic->id])}}" @endif >
                                {!! $topic->title !!}
                            </a>
                        </h2>
                    </div>
                </div>

            </div>

            @endforeach

        </div>

        <div class="row text-center mb-4 mt-2">
            <div class="col-md-12">
                <a href="{{route('Frontend::event::list', ['event_id' => 'featured'])}}"
                    class="btn btn-outline-secondary show-more"><i class="fa fa-angle-double-right"></i> VIEW ALL</a>
            </div>
        </div>

    </div>

</div>

@endsection

@section('headerStyle')
{{--    <link rel="stylesheet" href="//tevratgundogdu.com/works/breakingnewsticker/css/breakingNews.css">--}}
@endsection

@section('footerScript')
<script src="{{asset('js/marquee.js')}}"></script>

<script type="text/javascript">
    $(function (){
            $('.simple-marquee-container').SimpleMarquee({
                duration:150000
            });
        });

        $(document).ready(function () {
            if (window.innerWidth >= 992) {
                var minHeight = $("#noticeTabContent").innerHeight();
                $(".news-section").find(".tab-content").each(function () {
                    if ($(this).innerHeight() > minHeight) {
                        minHeight = $(this).innerHeight();
                    }
                });
                $(".news-section").find(".tab-content").css("min-height", minHeight);
            }
        });
</script>
@endsection