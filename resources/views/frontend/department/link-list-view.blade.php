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
                            <li class="breadcrumb-item active" aria-current="page">Important Links</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @if($department)
                <div class="row">
                    <div class="col-md-12">

                        @foreach($links as $type)
                            <h5>{{$type->name}}</h5>
                            @foreach($type->links as $link)
                                <p>
                                    <a href="{{$link->link_url}}">{{$link->label}}</a>
                                </p>
                            @endforeach
                        @endforeach

                        <h5>Officers & Staffs List</h5>
                        <p>
                            <a href="{{route('Frontend::officer::list', ['departmentId' => $department->id, 'type' => 'OFFICER'])}}">Officers List</a>
                        </p>
                        <p>
                            <a href="{{route('Frontend::officer::list', ['departmentId' => $department->id, 'type' => 'STAFF'])}}">Staffs List</a>
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection