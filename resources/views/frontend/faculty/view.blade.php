@extends('frontend.layout.main')

@section('content')

<main role="main" class="container faculties">

    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Faculty</a></li>
                    @if(isset($data['faculty']))
                    <li class="breadcrumb-item active" aria-current="page">{{$data['faculty']->name}}
                        @endif
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    @if(isset($data['faculty']))

    <div class="banner-inner p-2" style="margin-bottom: 20px;" id="vue-app">

        @if($data['faculty']->dean()->exists())
        <img class="rounded lazy img-fluid lazy chairman-image float-left"
            data-src="{{$data['faculty']->dean->real_image_path}}" alt="{{$data['faculty']->dean->name}}">

        <h6 class="text-uppercase pt-2">Dean</h6>
        <p><a
                href="{{route("Frontend::teacher::view", ['slug' => ($data['faculty']->dean->slug) ? $data['faculty']->dean->slug : Laralum::getUserNameFromEmail($data['faculty']->dean->email)])}}">{{$data['faculty']->dean->name}}</a>
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
                        @if($data['faculty']->phone_number) Phone: {{$data['faculty']->phone_number}} @endif<br>
                        @if($data['faculty']->email) Email: {{$data['faculty']->email}} @endif
                    </p>
                </div>
            </div>

            <div class="col-xl-8 col-lg-8 col-md-7">

                <div class="p-3 bg-light-gray">
                  
                    <h2>About Faculty</h2>
                    <hr class="star-dark">
                    {!! $data['faculty']->description !!}
                    
                </div>

            </div>

        </div>

    </div>



    <div class="row mt-5 pl-3">

        <div class="col-lg border p-3 mt-md-3 pt-4 mb-3">
            <h4 class="text-xs-center text-sm-center text-md-left text-primary"> <i class="fas fa-graduation-cap"></i>
                Departments
            </h4>

            <ul class="list-group">

                @foreach($data['faculty']->departments as $department)

                
                <li class="list-group-item border-0">
                    <a @if($department->external_link) href="{{$department->external_link}}" @else
                        href="{{route("Frontend::department::view", ['slug' => $department->slug])}}" @endif
                        target="_blank">
                        <i class="fas fa-graduation-cap"></i> <span>{{$department->name}}</span>
                    </a>
                </li>
                @endforeach

               

            </ul>

        </div>

        <div class="col-lg border border-lg-left-0 p-3 mt-md-3 pt-4 mb-3">
            <h4 class="text-xs-center text-sm-center text-md-left text-primary"> <i class="far fa-newspaper"></i>
                Research
            </h4>
            <table>
                <tr>
                    <td>
                        @foreach($data['faculty']->journal as $journal)
                    <a href="{{route('Frontend::journal::list', ['faculty_id' => $data['faculty']->id])}}" target="_blank">
                        <i class="far fa-newspaper"></i> Faculty Journal: Repository
                    </a>
                   
                    @endforeach
                    </td>
                </tr>
                @foreach($data['events'] as $event)
                @if ($event->event_name == 'Research' && $event->enabled=='on')
            <tr>
                <td>
                    @if(!$event->enabled)
                    <i data-position="top center" data-content="Disabled" class="pop orange warning icon"></i>
                @else
                    <i data-position="top center" data-content="Enabled" class="pop green checkmark icon"></i>
                @endif
                <i class="far fa-newspaper"></i> <a @if($event->external_link) href="{{$event->external_link}}" target="_blank"
                    @else
                    href="{{route('Frontend::faculty::facultyEventview', ['discussion' => $event->id])}}" @endif> 
                    {{ $event->title }}
                </a>
                </td>
                
    
            </tr>
            @endif
            @endforeach
            </table>

        </div>

        <div class="col-lg mt-md-3 p-0 pt-0 mb-3">

            <ul class="nav nav-tabs" id="myTab" role="tablist">

                
                <li class="nav-item">
                    <a class="nav-link active " id="Notice-tab" data-toggle="tab"
                        href="#Notice" role="tab" aria-controls="Notice" aria-selected="false">
                        <i class="far fa-calendar-alt"></i>
                        Notice
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " id="News-tab" data-toggle="tab"
                        href="#News" role="tab" aria-controls="News" aria-selected="false">
                        <i class="far fa-calendar-alt"></i>
                        News
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " id="Event-tab" data-toggle="tab"
                        href="#Event" role="tab" aria-controls="Event" aria-selected="false">
                        <i class="far fa-calendar-alt"></i>
                        Event
                    </a>
                </li>
                

            </ul>

            <div class="tab-content" id="myTabContent">

                

                {{-- Event Item --}}

                <div class="tab-pane active show fade" id="Notice" role="tabpanel"
                    aria-labelledby="Notice-tab">

                    

                        
                   <table width="100%">
                    @php $count = 0; @endphp
                    @foreach($data['events'] as $event)
                    @if($count < 8 && $event->event_name == 'Notice' && $event->enabled == 'on')
                    
                <tr>
                    <td style="border-bottom: 1px solid #ccc; padding:10px 0;">
                        
                    <a @if($event->external_link) href="{{$event->external_link}}" target="_blank"
                        @else
                        href="{{route('Frontend::faculty::facultyEventview', ['discussion' => $event->id])}}" @endif>
                        <i class="fa fa-angle-double-right"></i> {{ $event->title }}
                    </a>
                    <br /> <small><i class="fas fa-clock"></i> {{$event->publish_date}}</small>
                    </td>
                    
        
                </tr>
                @php $count++; @endphp
                @endif
                @endforeach
                </table>
                {{-- @if(!empty($event->title))
                    <div class="row text-center" style="margin-right: 0px">
                        <div class="col-md-12 text-right">
                            <a href="#"
                                class=""><i class="fa fa-angle-double-right"></i> View All</a>
                        </div>
                    </div>
                    @endif  --}}

                                   

                </div>

                  {{-- Event Item End --}}

                   {{-- Event Item --}}

                   <div class="tab-pane  show fade" id="News" role="tabpanel"
                   aria-labelledby="News-tab">

                   <table width="100%">
                    @foreach($data['events'] as $event)
                    @if ($event->event_name == 'News' && $event->enabled=='on')
                <tr>
                    <td style="border-bottom: 1px solid #ccc; padding:10px 0;">
                        
                    <a @if($event->external_link) href="{{$event->external_link}}" target="_blank"
                        @else
                        href="{{route('Frontend::faculty::facultyEventview', ['discussion' => $event->id])}}" @endif>
                        <i class="fa fa-angle-double-right"></i> {{ $event->title }}
                    </a>
                    <br /> <small><i class="fas fa-clock"></i> {{$event->publish_date}}</small>
                    </td>
                    
        
                </tr>
                @endif
                @endforeach
                </table>
                {{-- @if(!empty($event->title))
                    <div class="row text-center" style="margin-right: 0px">
                        <div class="col-md-12 text-right">
                            <a href="#"
                                class=""><i class="fa fa-angle-double-right"></i> View All</a>
                        </div>
                    </div>
                    @endif  --}}

                   
               </div>

              {{-- Event Item End --}}

              <div class="tab-pane  show fade" id="Event" role="tabpanel"
                   aria-labelledby="Event-tab">
                   <table width="100%">
                    @foreach($data['events'] as $event)
                    @if ($event->event_name == 'Event' && $event->enabled=='on')
                <tr>
                    <td style="border-bottom: 1px solid #ccc; padding:10px 0;">
                        
                    <a @if($event->external_link) href="{{$event->external_link}}" target="_blank"
                        @else
                        href="{{route('Frontend::faculty::facultyEventview', ['discussion' => $event->id])}}" @endif>
                        <i class="fa fa-angle-double-right"></i> {{ $event->title }}
                    </a>
                    <br /> <small><i class="fas fa-clock"></i> {{$event->publish_date}}</small>
                    </td>
                    
        
                </tr>
                @endif
                @endforeach
                </table>
                {{-- @if(!empty($event->title))
                    <div class="row text-center" style="margin-right: 0px">
                        <div class="col-md-12 text-right">
                            <a href="#"
                                class=""><i class="fa fa-angle-double-right"></i> View All</a>
                        </div>
                    </div>
                    @endif  --}}

                  

               </div>
              

            </div>

            
                   

        </div>
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
                messageFromDean: `{!! $data['faculty']->message_from_dean !!}`,
                readMore : false
            }
        })
</script>

@endsection