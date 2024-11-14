@extends('frontend.layout.main')
@section('content')

    <div class="container">

        <div class="content-section hall-page">

            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            @if($department)
                                <li class="breadcrumb-item"><a
                                            href="{{route('Frontend::institute::view', ['slug' => $department->slug])}}">{{$department->name}}</a>
                                </li> @endif
                            <li class="breadcrumb-item active" aria-current="page">Downloads</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @if($department)
                <div class="row">
                    <div class="col-md-12">

                        @foreach($department->files as $file)
                            <h5>{{$file->type}}</h5>

                            <p>
                                <a href="{{route('department::file::view', ['department' => $department->id, 'file' => $file->id])}}">{{$file->name}}</a>
                            </p>
                        @endforeach

                    </div>
                </div>
            @endif

        </div>

    </div>

@endsection