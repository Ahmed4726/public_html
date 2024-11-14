@extends('frontend.layout.main')

@section('content')

<div class="container">
    <div class="content-section officer-page">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li aria-current="page" class="breadcrumb-item active">Internet Connection Application</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container ju-form">
    <div class="container ju-form-bg">

        @if (session('success'))
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session('success') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-sm-3"> </div>
            <div class="col-sm-6">
                <h2>Internet Connection Application Form</h2>

                <form method="post" class="mt-4" enctype="multipart/form-data">
                    {{csrf_field()}}

                    <div class="form-group row required">
                        <label for="name" class="col-form-label">Name</label>
                        <input type="text" id="name" name="name" placeholder="Name ..."
                            class="form-control @error('name') is-invalid @enderror" value="{{old('name')}}" required>
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <x-faculty-or-office :faculties="$faculties" />
                    <x-designation />

                    {{-- <div class="form-group row required">
                        <label for="designtion" class="col-form-label">Designation </label>
                        <select id="designtion" name="employee_type_id"
                            class="form-control jufbs @error('employee_type_id') is-invalid @enderror">
                            <option value="">Please Select a Designation</option>
                            @foreach ($types as $type)
                            <option value="{{$type->id}}" @if (old('employee_type_id')==$type->id)
                    selected
                    @endif
                    >{{$type->name}}</option>
                    @endforeach
                    </select>
                    @error('employee_type_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
            </div>

            <div class="form-group row">
                <label for="faculty" class="col-form-label">Faculty</label>
                <select id="faculty" class="form-control jufbs" @change="getDepartmentList()" v-model="faculty_id"
                    name="faculty_id">
                    <option value="">Please Select a Faculty</option>
                    @foreach ($faculties as $faculty)
                    <option value="{{$faculty->id}}">{{$faculty->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group row required">
                <label for="department" class="col-form-label">Department</label>
                <select id="department" name="department_id" v-model="department_id"
                    class="form-control jufbs @error('department_id') is-invalid @enderror">
                    <option value="">Please Select a department</option>
                    <option v-for="(department, index) in departments.data" v-cloak :value="department.id">
                        @{{department.name}}
                    </option>
                    <option value="other">Other</option>
                </select>
                @error('department_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group row required" v-if="department_id == 'other'">
                <label for="other" class="col-form-label">Other</label>
                <input type="text" id="other" name="other" placeholder="Other ..."
                    class="form-control @error('other') is-invalid @enderror" value="{{old('other')}}">
                @error('other')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div> --}}

            <div class="form-group row required">
                <label for="address" class="col-form-label">Address</label>
                <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="address"
                    required>{{old('address')}}</textarea>
                @error('address')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group row required">
                <label for="email" class="col-form-label">Email</label>
                <input type="text" id="email" name="email" placeholder="Email ..."
                    class="form-control @error('email') is-invalid @enderror" value="{{old('email')}}" required>
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group row required">
                <label for="phone_no" class="col-form-label">Phone Number</label>
                <input type="text" id="phone_no" name="phone_no" placeholder="Phone Number ..."
                    class="form-control @error('phone_no') is-invalid @enderror" value="{{old('phone_no')}}" required>
                @error('phone_no')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group row">
                <label for="preferable_time" class="col-form-label">Preferable Time</label>
                <input type="text" id="preferable_time" preferable_time" placeholder="Preferable Time ..."
                    class="form-control @error('preferable_time') is-invalid @enderror"
                    value="{{old('preferable_time')}}">
                @error('preferable_time')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group row">
                <label for="comment" class="col-form-label">Comment</label>
                <textarea class="form-control @error('comment') is-invalid @enderror" name="comment"
                    id="comment">{{old('comment')}}</textarea>
            </div>

            <div class="form-group row ju-button-root">
                <div class="domain-apply-button">
                    <button type="submit" class="btn btn-primary">Apply</button>
                </div>
            </div>

            </form>

        </div>
    </div>

</div>
</div>

<script>
    flatpickr("#preferable_time", {minDate: "today", enableTime: true, dateFormat: "Y-m-d H:i" });
</script>

@endsection

@section('headerStyle')
<link rel="stylesheet" href="{{asset('css/frontend-form.css')}}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


@endsection