@extends('frontend.layout.main')

@section('content')

<div class="container department-home" id="vue-app">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            @if($department && $department->faculty()->exists() && $department->faculty->type == "FACULTY")
            <li class="breadcrumb-item"><a
                    href="{{route('Frontend::faculty::view', ['slug' => $department->faculty->slug])}}">{{$department->faculty->name}}</a>
            </li>
            @else
            <li class="breadcrumb-item"><a href="#">Institute</a></li>
            @endif
            @if($department)
            <li class="breadcrumb-item active" aria-current="page">{{$department->name}}</li>
            @endif
        </ol>
    </nav>

    @if($department)

    <div class="banner-inner p-2" style="min-height: 175px">

        @if($department->chairman()->exists())
        <img class="rounded lazy img-fluid lazy chairman-image float-left"
            data-src="{{$department->chairman->real_image_path}}" alt="{{$department->chairman->name}}">

        <h6 class="text-uppercase pt-2">
            @if($department && $department->faculty()->exists() && $department->faculty->type == "FACULTY") Chairman
            @else Director @endif </h6>
        <p><a
                href="{{route("Frontend::teacher::view", ['slug' => ($department->chairman->slug) ? $department->chairman->slug : Laralum::getUserNameFromEmail($department->chairman->email)])}}">{{$department->chairman->name}}</a>
        </p>

        @else
        <img class="rounded lazy img-fluid lazy chairman-image float-left"
            data-src="{{asset('images/default-img-person.jpg')}}">

        <h6 class="text-uppercase pt-2"> @if($department && $department->faculty()->exists() &&
            $department->faculty->type == "FACULTY") Chairman @else Director @endif </h6>
        <p>Information will be updated soon!</p>
        @endif

        <h6>Message from @if($department && $department->faculty()->exists() && $department->faculty->type == "FACULTY")
            Chairman @else Director @endif </h6>

        <div class="text-justify">

            {!!\Illuminate\Support\Str::limit(preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/si",'<$1$2>',
                    $department->message_from_chairman), 400)!!}

                    @if (\Illuminate\Support\Str::length(
                    preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/si",'<$1$2>', $department->message_from_chairman))
                            > 400)
                            <a class=""
                                href=" {{route('Frontend::department::chairman-message::view', ['department' => $department->id])}}">
                                Read More</a>
                            @endif

        </div>

    </div>


    <div class="section-summary mt-5">
        <h2 class=" pt-3">About {{$department->name}}</h2>
        <hr class="star-dark mb-5">
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-5">
                @if($department->image_url)
                <img src="{{asset("storage/image/department/$department->image_url")}}" alt="Department Banner Image"
                    class="img-fluid" />
                @endif

                <div class="contact-info">
                    <h5>Contact Information</h5>
                    <p>
                        @if($department->contact_phone_number) Phone: {{$department->contact_phone_number}}<br> @endif
                        @if($department->contact_email) Email: {{$department->contact_email}} @endif
                    </p>
                </div>
            </div>
            <div class="col-xl-8 col-lg-8 col-md-7">
                <div v-if="description.length > 1500">
                    <span v-if="!readMore" :inner-html.prop="description | str_limit(1500)"></span>
                    <a class="" v-if="!readMore" @click.prevent="readMore=true" href="#">
                        Read more
                    </a>
                </div>
                <span v-if="readMore || description.length < 1500" v-html="description"></span>
            </div>
        </div>
    </div>

    <div class="row mt-5 pl-3">

        <div class="col-lg border p-3 mt-md-3 pt-4 mb-3">
            <h4 class="text-xs-center text-sm-center text-md-left text-primary"> <i class="fas fa-graduation-cap"></i>
                {{$department->config['program'] ?? 'Academic Programs'}}
            </h4>

            <ul class="list-group">

                @foreach($department->programs->take(5) as $program)
                <li class="list-group-item border-0">
                    <a href="{{\Laralum::responseProgramUrl($program)}}">
                        <i class="fas fa-graduation-cap"></i> {!! $program->name !!}</a>
                </li>
                @endforeach

                @if($department->programs->count() > 5)
                <li class="list-group-item border-0"><a
                        href="{{route('Frontend::program::index', ['department_id' => $department->id])}}" class="">
                        <i class="fa fa-angle-double-right"></i>
                        Click Here to View All</a>
                </li>
                @endif

            </ul>

        </div>

        <div class="col-lg border border-lg-left-0 p-3 mt-md-3 pt-4 mb-3">
            <h4 class="text-xs-center text-sm-center text-md-left text-primary"> <i class="far fa-newspaper"></i>
                {{$department->config['research'] ?? 'Research'}}
            </h4>

            <ul class="list-group">

                @if($department->journal()->exists())
                <li class="list-group-item border-0">
                    <a href="{{route('Frontend::journal::list', ['department_id' => $department->id])}}"> <i
                            class="far fa-newspaper"></i> Click here to view our journal</a>
                </li>
                @endif

                @foreach($department->researches->take(5) as $research)

                <li class="list-group-item border-0">
                    <a @if($research->website_link) href="{{$research->website_link}}" @else
                        href="{{route('Frontend::research::view', ['research' => $research->id])}}" @endif>
                        <i class="far fa-newspaper"></i> {{$research->name}}</a>
                </li>

                @endforeach

                @if($department->researches->count() > 5)
                <li class="list-group-item border-0"><a
                        href="{{route('Frontend::research::index', ['department_id' => $department->id])}}" class=""><i
                            class="fa fa-angle-double-right"></i> Click
                        Here to View All</a></li>
                @endif

            </ul>
        </div>

        <div class="col-lg mt-md-3 p-0 pt-0 mb-3">

            <ul class="nav nav-tabs" id="myTab" role="tablist">

                @foreach($events as $key => $event)
                <li class="nav-item">
                    <a class="nav-link @if($key == 0) active @endif" id="{{$event->name}}-tab" data-toggle="tab"
                        href="#{{$event->name}}" role="tab" aria-controls="{{$event->name}}" aria-selected="false">
                        <i class="far fa-calendar-alt"></i>
                        {{$event->name}}
                    </a>
                </li>
                @endforeach

            </ul>

            <div class="tab-content" id="myTabContent">

                @foreach($events as $key => $event)

                <div class="tab-pane @if($key == 0) active show @else fade @endif" id="{{$event->name}}" role="tabpanel"
                    aria-labelledby="{{$event->name}}-tab">

                    <ul class="list-unstyled item-lists">

                        @foreach($event->topics as $topic)
                        <li>
                            <p class="mb-1">
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
                    <div class="row text-center" style="margin-right: 0px">
                        <div class="col-md-12 text-right">
                            <a href="{{route('Frontend::event::list', ['department_id' => $department->id, 'event_id' => $event->id])}}"
                                class=""><i class="fa fa-angle-double-right"></i> View All</a>
                        </div>
                    </div>
                    @endif

                </div>

                @endforeach

            </div>

        </div>
    </div>

    <div class="mt-4 pl-3">
        <h4 class="text-center mb-4 text-primary"> <i class="fas fa-chalkboard-teacher"></i> Faculty Members</h4>
        <div class="row">

            @foreach($department->teacher as $teacher)
            <div class="col-xl-4 col-lg-4 col-md-6 border p-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <img @if($teacher->image_url)
                            data-src="{{asset("storage/image/teacher/$teacher->image_url")}}"
                            @else data-src="{{asset('images/default-img-person.jpg')}}" @endif class="rounded float-left
                            lazy img-fluid" alt="{{$teacher->name}}">
                        </div>
                        <div class="col-8">
                            <h5 class="card-title"><a
                                    href="{{route("Frontend::teacher::view", ['slug' => $teacher->slug])}}">{{$teacher->name}}</a>
                            </h5>
                            <p class="card-text">
                                <span class="text-muted">
                                    @if($teacher->designationInfo()->exists()) {{$teacher->designationInfo->name}},
                                    @endif
                                    {{$department->name}}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="col-xl-4 col-lg-4 col-md-6 border p-3 ">
                <div class="card-body text-primary">
                    <h5 class="card-title">View All Faculty Members</h5>
                    <p class="card-text">{{$department->name}} have total
                        {{$department->facultyMembersByStatus([1, 2])->count()}} faculty members.</p>
                    <p class="card-text"><span class="text-muted"><a
                                href="{{route("Frontend::teacher::list", ['department_id' => $department->id])}}"
                                class="card-link"><i class="fa fa-angle-double-right"></i> Click Here to View
                                All</a></span></p>
                </div>
            </div>

        </div>
    </div>

    <div class="row mt-5 mb-4 pl-3">

        <div class="col-lg border p-3 mt-md-3 pt-4">
            <h4 class="text-xs-center text-sm-center text-md-left text-primary"> <i class="fas fa-shield-alt"></i>
                {{$department->config['facility'] ?? 'Facility'}}
            </h4>
            <ul class="list-group">

                @foreach($department->facilities->take(5) as $facility)
                <li class="list-group-item border-0">
                    <a @if($facility->external_link) href="{{$facility->external_link}}"
                        @else href="{{route('Frontend::facility::view', ['facility' => $facility->id])}}" @endif>
                        <i class="fas fa-shield-alt"></i> {{$facility->name}}</a>
                </li>
                @endforeach

                @if($department->facilities->count() > 5)
                <li class="list-group-item border-0"><a
                        href="{{route('Frontend::facility::index', ['department_id' => $department->id])}}" class=""><i
                            class="fa fa-angle-double-right"></i> Click Here to View All</a></li>
                @endif

            </ul>
        </div>

        <div class="col-lg border border-lg-left-0 p-3 mt-md-3 pt-4">
            <h4 class="text-xs-center text-sm-center text-md-left text-primary"> <span class="fa fa fa-link"></span>
                {{$department->config['link'] ?? 'Important Links'}}
            </h4>
            <ul class="list-group">

                @if($department->teacher->isNotEmpty())
                <li class="list-group-item border-0"><a
                        href="{{route("Frontend::teacher::list", ['department_id' => $department->id])}}">
                        <span class="fa fa fa-link"></span> Faculty Members</a></li>
                @endif

                @foreach($department->links->take(5) as $link)
                <li class="list-group-item border-0"><a href="{{$link->link_url}}" target="{{$link->target}}">
                        <span class="fa fa fa-link"></span> {{$link->label}}</a></li>
                @endforeach

                @if($department->officers()->where('type_id', 1)->exists())
                <li class="list-group-item border-0"><a
                        href="{{route('Frontend::officer::list', ['department_id' => $department->id, 'type_id' => 1])}}">
                        <span class="fa fa fa-link"></span> Officer List</a></li>
                @endif

                @if($department->officers()->where('type_id', 2)->exists())
                <li class="list-group-item border-0"><a
                        href="{{route('Frontend::officer::list', ['department_id' => $department->id, 'type_id' => 2])}}">
                        <span class="fa fa fa-link"></span> Staff List</a></li>
                @endif

                @if($department->links->count() > 5)
                <li class="list-group-item border-0"><a
                        href="{{route('Frontend::department::link::list', ['department' => $department->id])}}"
                        class=""><i class="fa fa-angle-double-right"></i> Click Here to View All</a></li>
                @endif
            </ul>
        </div>

        <div class="col-lg border border-lg-left-0 p-3 mt-md-3 pt-4">
            <h4 class="text-xs-center text-sm-center text-md-left text-primary"> <span class="fa fa fa-download"></span>
                {{$department->config['file'] ?? 'Download Files'}}
            </h4>
            <ul class="list-group">

                @foreach($department->files->take(5) as $file)
                <li class="list-group-item border-0"><a
                        href="{{route('department::file::view', ['department' => $department->id, 'file' => $file->id])}}"><span
                            class="fa fa fa-download"></span> {{$file->name}}</a></li>
                @endforeach
                @if($department->files->count() > 5)
                <li class="list-group-item border-0"><a
                        href="{{route('Frontend::download::list', ['department_id' => $department->id])}}" class=""><i
                            class="fa fa-angle-double-right"></i> Click Here to View All</a></li>
                @endif

            </ul>
        </div>
    </div>

    @endif
</div>

@endsection


@section('footerScript')
@include('laralum.include.vue.vue-axios')

<script type="text/javascript">
    new Vue({
            el: "#vue-app",
            data: {
                description: `{!! $department->description ?? '' !!}`,
                readMore : false
            }
        })
</script>

@endsection