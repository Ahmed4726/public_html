@extends('frontend.layout.main')
@section('content')

<div class="container">
    <div class="content-section people-page teacher-details-page">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('Frontend::teacher::list')}}">People</a></li>
                        @if($teacher)<li class="breadcrumb-item active" aria-current="page">{{$teacher->name}}</li>
                        @endif
                    </ol>
                </nav>
            </div>
        </div>

        @if($teacher)

        <div class="row">

            <div class="col-md-3">
                <div class="card">
                    <img class="card-img-top img-fluid mx-auto d-block lazy"
                        data-src="{{$teacher->real_image_path}}" alt="{{$teacher->name}}">
                    @if($teacher->status == 4 && $teacher->statusInfo()->exists())<span class="btn btn-info"
                        role="button"
                        style="font-size: 12px;margin-top: -30px;">{{$teacher->statusInfo->name}}</span>@endif
                    @if($teacher->status == 3 && $teacher->statusInfo()->exists())<span class="btn btn-secondary"
                        role="button"
                        style="font-size: 12px;margin-top: -30px;">{{$teacher->statusInfo->name}}</span>@endif
                    @if($teacher->status == 2 && $teacher->statusInfo()->exists())<span class="btn btn-warning"
                        role="button"
                        style="font-size: 12px;margin-top: -30px;">{{$teacher->statusInfo->name}}</span>@endif
                    <div class="card-body">
                        <h6>
                            <a href="#">{{$teacher->name}}</a>
                            <small>
                                @if($teacher->designationInfo()->exists()) {{$teacher->designationInfo->name}}, @endif
                                @if($teacher->department()->exists()) {{$teacher->department->name}} @endif</small>
                        </h6>
                        <div>
                            <div>
                                @if ($teacher->google_scholar || $teacher->research_gate || $teacher->orcid)
                                <b>Research</b>
                                @endif

                                @if ($teacher->google_scholar)
                                <a href="{{$teacher->google_scholar}}" target="_blank" class="no-dec">
                                    <i class="fab fa-google fa-lg" title="Google Scholar"></i>
                                </a>
                                @endif


                                @if ($teacher->research_gate)
                                <a href="{{$teacher->research_gate}}" target="_blank" class="no-dec">
                                    <i class="fab fa-researchgate fa-lg" title="Research Gate"></i>
                                </a>
                                @endif

                                @if ($teacher->orcid)
                                <a href="{{$teacher->orcid}}" target="_blank" class="no-dec">
                                    <i class="fab fa-orcid fa-lg" title="ORCID"></i>
                                </a>
                                @endif

                            </div>
                            <div>
                                @if ($teacher->facebook || $teacher->twitter || $teacher->linkedin)
                                <b>Social Media</b>
                                @endif

                                @if ($teacher->facebook)
                                <a href="{{$teacher->facebook}}" target="_blank" class="no-dec">
                                    <i class="fab fa-facebook-square fa-lg" title="Faebook"></i>
                                </a>
                                @endif

                                @if ($teacher->twitter)
                                <a href="{{$teacher->twitter}}" target="_blank" class="no-dec">
                                    <i class="fab fa-twitter-square fa-lg" title="Twitter"></i>
                                </a>
                                @endif

                                @if ($teacher->linkedin)
                                <a href="{{$teacher->linkedin}}" target="_blank" class="no-dec">
                                    <i class="fab fa-linkedin fa-lg" title="Linkedin"></i>
                                </a>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <h3 id="profile">PROFILE</h3>

                @if ($teacher->biography)
                <h6>SHORT BIOGRAPHY</h6>
                {!! $teacher->biography !!}
                @endif

                <div>
                    <div>
                        @if ($teacher->google_scholar || $teacher->research_gate || $teacher->orcid)
                        <b>Research</b>
                        @endif

                        @if ($teacher->google_scholar)
                        <a href="{{$teacher->google_scholar}}" target="_blank" class="no-dec">
                            <i class="fab fa-google fa-lg" title="Google Scholar"></i>
                        </a>
                        @endif


                        @if ($teacher->research_gate)
                        <a href="{{$teacher->research_gate}}" target="_blank" class="no-dec">
                            <i class="fab fa-researchgate fa-lg" title="Research Gate"></i>
                        </a>
                        @endif

                        @if ($teacher->orcid)
                        <a href="{{$teacher->orcid}}" target="_blank" class="no-dec">
                            <i class="fab fa-orcid fa-lg" title="ORCID"></i>
                        </a>
                        @endif

                    </div>
                    <div>
                        @if ($teacher->facebook || $teacher->twitter || $teacher->linkedin)
                        <b>Social Media</b>
                        @endif

                        @if ($teacher->facebook)
                        <a href="{{$teacher->facebook}}" target="_blank" class="no-dec">
                            <i class="fab fa-facebook-square fa-lg" title="Faebook"></i>
                        </a>
                        @endif

                        @if ($teacher->twitter)
                        <a href="{{$teacher->twitter}}" target="_blank" class="no-dec">
                            <i class="fab fa-twitter-square fa-lg" title="Twitter"></i>
                        </a>
                        @endif

                        @if ($teacher->linkedin)
                        <a href="{{$teacher->linkedin}}" target="_blank" class="no-dec">
                            <i class="fab fa-linkedin fa-lg" title="Linkedin"></i>
                        </a>
                        @endif

                    </div>
                </div>

                @if ($teacher->research_interest)
                <h6 class="mt-3">RESEARCH INTEREST</h6>
                {!! $teacher->research_interest !!}
                @endif
            </div>

        </div>

        <!-- Nav tabs -->
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs" role="tablist">
                    @if($publicationsType->isNotEmpty())
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#researchAndPublication" role="tab">RESEARCH
                            &
                            PUBLICATION</a>
                    </li>
                    @endif

                    @if($teacher->teachings->isNotEmpty())
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#teachingInfo" role="tab">TEACHING</a>
                    </li>
                    @endif

                    @if($teacher->educations->isNotEmpty())
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#academicInfo" role="tab">ACADEMIC INFO</a>
                    </li>
                    @endif

                    @if($teacher->experiences->isNotEmpty())
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#experience" role="tab">EXPERIENCE</a>
                    </li>
                    @endif

                    @if($teacher->activities->isNotEmpty())
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#activity" role="tab">ACTIVITY</a>
                    </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#contact" role="tab">CONTACT</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="researchAndPublication" role="tabpanel">
                @if($publicationsType->isNotEmpty())
                <div class="row">
                    <div class="col-md-12">

                        @foreach($publicationsType as $type)
                        <h4 class="text-center">{{$type->name}}</h4>
                        @foreach($type->publications as $publication)

                        @if ($publication->author_name)
                        {{$publication->author_name}},
                        @endif
                        @if ($publication->name)
                        <a href="@if($publication->url) 
                            {{$publication->url}} 
                            @else 
                            {{route('Frontend::teacher::view', ['slug' => ($teacher->slug) ? $teacher->slug : Laralum::getUserNameFromEmail($teacher->email)])}} 
                            @endif">
                            {!!$publication->name!!},
                        </a>
                        @endif

                        @if ($publication->journal_name)
                        {{$publication->journal_name}},
                        @endif

                        @if ($publication->volume)
                        {{$publication->volume}},
                        @endif

                        @if ($publication->issue)
                        {{$publication->issue}},
                        @endif

                        @if ($publication->page)
                        pp.{{$publication->page}},
                        @endif

                        @if ($publication->conference_location)
                        {{$publication->conference_location}},
                        @endif

                        @if ($publication->publication_year)
                        {{$publication->publication_year}}.
                        @endif

                        @if ($publication->url2)
                        doi: {{$publication->url2}}
                        @endif

                        {!! $publication->iframe !!}

                        {!! $publication->description !!}

                        @if (!$publication->description)
                        <br />
                        @endif

                        @endforeach
                        <hr />
                        @endforeach

                    </div>
                </div>
                @endif
            </div>

            <div class="tab-pane" id="teachingInfo" role="tabpanel">
                @if($teacher->teachings->isNotEmpty())
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="text-left">Teaching</h4>

                        <div class="row">
                            <div class="col-md-12">


                                @if ($teacher->teachings()->exists())
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="d-flex">
                                            <th scope="col" class="col-md-2">Course Code</th>
                                            <th scope="col" class="col-md-4">Course Title</th>
                                            <th scope="col" class="col-md-6">Semester/Year</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($teacher->teachings as $key => $teaching)
                                        <tr class="d-flex">
                                            <td class="col-md-2">{{$teaching->course_code}}</td>
                                            <td class="col-md-4">{{$teaching->course_title}}</td>
                                            <td class="col-md-6">{{$teaching->semester}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="tab-pane" id="academicInfo" role="tabpanel">
                @if($teacher->educations->isNotEmpty())
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="text-left">Academic Info</h4>
                        @foreach($teacher->educations as $education)
                        <div class="row">
                            <div class="col-md-12">
                                @if ($education->institute)
                                <strong>Institute: </strong> {{$education->institute}}<br />
                                @endif
                                @if ($education->period)
                                <strong>Period: </strong> {{$education->period}}<br />
                                @endif
                                {!! $education->description !!}
                                @if (!$education->description)
                                <br />
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="tab-pane" id="experience" role="tabpanel">
                @if($teacher->experiences->isNotEmpty())
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="text-left">Experience</h4>
                        @foreach($teacher->experiences as $experience)
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-12">
                                @if ($experience->organization)
                                <strong>Organization: </strong> {{$experience->organization}}<br />
                                @endif
                                @if($experience->position)
                                <strong>Position: </strong> {{$experience->position}}<br />
                                @endif
                                @if($experience->period)
                                <strong>Period: </strong> {{$experience->period}}<br />
                                @endif
                                {!! $experience->description !!}

                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="tab-pane" id="activity" role="tabpanel">
                @if($teacher->activities->isNotEmpty())
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="text-left">Activity</h4>
                        @foreach($teacher->activities as $activity)
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-12">
                                @if ($activity->organization)
                                <strong>Organization: </strong> {{$activity->organization}}<br />
                                @endif
                                @if($activity->position)
                                <strong>Position: </strong> {{$activity->position}} <br />
                                @endif
                                @if($activity->period)
                                <strong>Period: </strong> {{$activity->period}}<br />
                                @endif
                                {!! $activity->description !!}

                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="tab-pane" id="contact" role="tabpanel">
                <div class="row">
                    <div class="col-md-12">

                        <h4 class="text-left">Contact</h4>

                        <h6>{{$teacher->name}}</h6>
                        <p>
                            @if($teacher->designationInfo()->exists()){{$teacher->designationInfo->name}}@endif<br />
                            @if($teacher->department()->exists()) {{$teacher->department->name}} @endif<br />
                            {{\App\Setting::first()->address}}<br>
                            @if ($teacher->cell_phone)
                            Cell Phone: <a href="tel:{{$teacher->cell_phone}}">{{$teacher->cell_phone}}</a><br>
                            @endif
                            @if ($teacher->work_phone)
                            Work Phone: <a href="tel:{{$teacher->work_phone}}">{{$teacher->work_phone}}</a><br>
                            @endif

                            Email: <a href="mailto:{{$teacher->email}}" target="_top">{{$teacher->email}}</a>
                            @if($teacher->additional_emails){{', '.$teacher->additional_emails}}@endif
                            <br>
                        </p>

                    </div>
                </div>
            </div>

        </div>

        @endif

    </div>
</div>

@endsection