@extends('frontend.layout.main')
@section('content')

<div class="container">
    <div class="content-section hall-page">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Program</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @foreach($programs as $program)
                    <p> <a href="{{\Laralum::responseProgramUrl($program)}}">{{$program->name}}</a> </p>
                @endforeach
                {{-- {{$programs->links()}} --}}
            </div>
        </div>

    </div>
</div>

@endsection