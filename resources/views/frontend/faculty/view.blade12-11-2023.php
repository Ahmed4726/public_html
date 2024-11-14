@extends('frontend.layout.main')

@section('content')

<main role="main" class="container faculties">

    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Faculty</a></li>
                    @if($faculty)
                    <li class="breadcrumb-item active" aria-current="page">{{$faculty->name}}
                        @endif
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    @if($faculty)

    <div class="banner-inner p-2" style="margin-bottom: 20px;" id="vue-app">

        @if($faculty->dean()->exists())
        <img class="rounded lazy img-fluid lazy chairman-image float-left"
            data-src="{{$faculty->dean->real_image_path}}" alt="{{$faculty->dean->name}}">

        <h6 class="text-uppercase pt-2">Dean</h6>
        <p><a
                href="{{route("Frontend::teacher::view", ['slug' => ($faculty->dean->slug) ? $faculty->dean->slug : Laralum::getUserNameFromEmail($faculty->dean->email)])}}">{{$faculty->dean->name}}</a>
        </p>

        @else
        <img class="rounded lazy img-fluid lazy chairman-image float-left"
            data-src="{{asset('images/default-img-person.jpg')}}">

        <h6 class="text-uppercase pt-2"> Dean </h6>
        <p>Information will be updated soon!</p>
        @endif


        <h6>Message from Dean</h6>

        <span v-if="messageFromDean.length > 500 && !readMore">
            <p :inner-html.prop="messageFromDean | str_limit(500)"></p>
            <a @click.prevent="readMore=true" href="#">
                Read more
            </a>
        </span>
        <span v-if="readMore || messageFromDean.length < 500" v-html="messageFromDean"></span>

    </div>


    <div class="section-summary mt-5">

        <div class="row">

            <div class="col-xl-4 col-lg-4 col-md-5">
                <div class="contact-info">
                    <h5>Contact Information</h5>
                    <p>
                        @if($faculty->phone_number) Phone: {{$faculty->phone_number}} @endif<br>
                        @if($faculty->email) Email: {{$faculty->email}} @endif
                    </p>
                </div>
            </div>

            <div class="col-xl-8 col-lg-8 col-md-7">

                <div class="p-3 bg-light-gray">
                  
                    <h2>About Faculty</h2>
                    <hr class="star-dark">
                    {!! $faculty->description !!}
                    @foreach($faculty->journal as $journal)
                    <a href="{{route('Frontend::journal::list', ['faculty_id' => $faculty->id])}}" target="_blank">
                        <p><i class="far fa-newspaper"></i> <strong> Faculty Journal: Repository</strong></p>
                    </a>
                    <hr class="star-dark">
                    @endforeach
                </div>

            </div>

        </div>

    </div>


    <div class="item-card-list mt-4">
        <h2 class="text-center">Departments</h2>
        <hr class="star-dark mb-5" />

        @foreach($faculty->departments as $department)

        <div class="row">
            <div class="col-md-4 department">
                <a @if($department->external_link) href="{{$department->external_link}}" @else
                    href="{{route("Frontend::department::view", ['slug' => $department->slug])}}" @endif
                    target="_blank">
                    @if($department->image_url)
                    <img style="width: 100%" data-src="{{asset("storage/image/department/$department->image_url")}}"
                        class="img-thumbnail lazy" />
                    @endif
                </a>
            </div>
            <div class="col-md-8">
                <h5>
                    <a @if($department->external_link) href="{{$department->external_link}}" @else
                        href="{{route("Frontend::department::view", ['slug' => $department->slug])}}" @endif
                        target="_blank">
                        <span>{{$department->name}}</span>
                    </a>
                </h5>
                {!! \Illuminate\Support\Str::limit($department->description, 1000) !!}
            </div>
        </div>
        <hr>

        @endforeach

    </div>

    @endif
</main>

@endsection

@section('footerScript')
@include('laralum.include.vue.vue-axios')

<script type="text/javascript">
    new Vue({
            el: "#vue-app",
            data: {
                messageFromDean: `{!! $faculty->message_from_dean !!}`,
                readMore : false
            }
        })
</script>

@endsection