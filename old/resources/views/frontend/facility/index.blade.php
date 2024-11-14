@extends('frontend.layout.main')
@section('content')

<div class="container">
    <div class="content-section hall-page">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Facility</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @foreach($facilities as $facility)
                    <p> <a @if($facility->external_link) href="{{$facility->external_link}}"
                        @else href="{{route('Frontend::facility::view', ['facility' => $facility->id])}}" @endif>{{$facility->name}}</a> </p>
                @endforeach
                {{-- {{$programs->links()}} --}}
            </div>
        </div>

    </div>
</div>

@endsection