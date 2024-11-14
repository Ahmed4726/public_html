@extends('frontend.layout.main')

@section('content')

<div class="container">
    <div class="content-section officer-page">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li aria-current="page" class="breadcrumb-item active">Employee Email Application</li>
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
                <h2>Employee Email ID Application Form</h2>

                <form method="post" class="mt-4">
                    {{csrf_field()}}

                    <div class="form-group row required">
                        <label for="name" class="col-form-label">Name</label>
                        <input type="text" id="name" name="name" required="required" placeholder="Name ..."
                            class="form-control @error('name') is-invalid @enderror" value="{{old('name')}}">
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group row required">
                        <label for="employeeID" class="col-form-label"> Employee ID
                            <x-employee-id-tooltip />
                        </label>
                        <input type="text" id="employeeID" name="employee_id" required="required"
                            placeholder="Employee ID ..."
                            class="form-control @error('employee_id') is-invalid @enderror"
                            value="{{old('employee_id')}}">
                        @error('employee_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <x-faculty-or-office :faculties="$faculties" />
                    <x-designation />

                    <div class="form-group row required">
                        <label for="phone_no" class="col-form-label">Phone Number</label>
                        <input type="text" id="phone_no" name="phone_no" required="required"
                            placeholder="Phone Number ..." class="form-control @error('phone_no') is-invalid @enderror"
                            value="{{old('phone_no')}}">
                        @error('phone_no')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group row required">
                        <label for="current_email" class="col-form-label">Current Email</label>
                        <input type="email" id="current_email" name="current_email" required="required"
                            placeholder="Current Email ..."
                            class="form-control @error('current_email') is-invalid @enderror"
                            value="{{old('current_email')}}">
                        @error('current_email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group row required">
                        <label for="expected_email_1" class="col-form-label">Expected Email Account 1</label>
                        <div class="input-group mb-3">
                            <input type="text" id="expected_email_1" name="expected_email_1" required="required"
                                placeholder="Write Only Username ..."
                                class="form-control @error('expected_email_1') is-invalid @enderror"
                                value="{{old('expected_email_1')}}">
                            <div class="input-group-append">
                                <span class="input-group-text">{{$domain}}</span>
                            </div>
                            @error('expected_email_1')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="expected_email_2" class="col-form-label">Expected Email Account 2</label>
                        <div class="input-group mb-3">
                            <input type="text" id="expected_email_2" name="expected_email_2"
                                placeholder="Write Only Username ..."
                                class="form-control @error('expected_email_2') is-invalid @enderror"
                                value="{{old('expected_email_2')}}">
                            <div class="input-group-append">
                                <span class="input-group-text">{{$domain}}</span>
                            </div>
                            @error('expected_email_2')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="expected_email_3" class="col-form-label">Expected Email Account 3</label>
                        <div class="input-group mb-3">
                            <input type="text" id="expected_email_3" name="expected_email_3"
                                placeholder="Write Only Username ..."
                                class="form-control @error('expected_email_3') is-invalid @enderror"
                                value="{{old('expected_email_3')}}">
                            <div class="input-group-append">
                                <span class="input-group-text">{{$domain}}</span>
                            </div>
                            @error('expected_email_3')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
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

@endsection

@section('headerStyle')
<link rel="stylesheet" href="{{asset('css/frontend-form.css')}}">
@endsection
