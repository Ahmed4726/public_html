@extends('frontend.layout.main')

@section('content')

<div class="container">
    <div class="content-section officer-page">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li aria-current="page" class="breadcrumb-item active">Internet Complain Form</li>
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
                <h2>Internet Complain Form</h2>

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

                    <div id="internet-complain">
                        <div class="form-group row required">
                            <label for="faculty" class="col-form-label">User Type</label>
                            <select id="faculty" class="form-control jufbs" name="user_type_id" v-model="user_type_id"
                                required>
                                <option value="">Please Select a User Type</option>
                                @foreach ($userTypes as $type)
                                <option value="{{$type->id}}">{{$type->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group row required" v-if="user_type_id!={{App\InternetComplain::studentID}}">
                            <label for="employee_id" class="col-form-label">Employee ID
                                <x-employee-id-tooltip /> &nbsp;</label>
                            <input type="text" id="employee_id" name="employee_id" placeholder="Employee ID ..."
                                class="form-control @error('employee_id') is-invalid @enderror"
                                value="{{old('employee_id')}}" required>
                            @error('employee_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <x-faculty-or-office :faculties="$faculties" />

                    {{-- <div class="form-group row">
                        <label for="faculty" class="col-form-label">Faculty</label>
                        <select id="faculty" class="form-control jufbs" @change="getDepartmentList()"
                            v-model="faculty_id" name="faculty_id">
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
                <label for="complainCategory" class="col-form-label">Complain Category</label>
                <select id="complainCategory"
                    class="form-control jufbs @error('internet_complain_category_id') is-invalid @enderror"
                    name="internet_complain_category_id" required>
                    <option value="">Please Select a Complain Category</option>
                    @foreach ($categories as $category)
                    <option value="{{$category->id}}" @if (old('internet_complain_category_id')==$category->id)
                        selected
                        @endif
                        >{{$category->name}}</option>
                    @endforeach
                </select>
                @error('internet_complain_category_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group row">
                <label for="details" class="col-form-label">Complain Details</label>
                <textarea class="form-control @error('details') is-invalid @enderror" name="details"
                    id="details">{{old('details')}}</textarea>
                @error('details')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group row required">
                <label for="phone_no" class="col-form-label">Phone Number</label>
                <input type="text" id="phone_no" name="phone_no" required="required" placeholder="Phone Number ..."
                    class="form-control @error('phone_no') is-invalid @enderror" value="{{old('phone_no')}}">
                @error('phone_no')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group row required">
                <label for="email" class="col-form-label">Email</label>
                <input type="email" id="email" name="email" placeholder="Email ..."
                    class="form-control @error('email') is-invalid @enderror" value="{{old('email')}}">
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group row ju-button-root">
                <div class="domain-apply-button">
                    <button type="submit" class="btn btn-primary">Complain</button>
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

@push('footerScript')

<script>
    new Vue({
        el : "#internet-complain",
        data : {
            user_type_id : "{{old('user_type_id')}}"
        },
    });
</script>

@endpush
