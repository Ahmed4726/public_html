@extends('frontend.layout.main')

@section('content')

<div class="container">
    <div class="content-section officer-page">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li aria-current="page" class="breadcrumb-item active">Teacher Profile Application</li>
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
                <h2>Teacher Profile Application Form</h2>

                <form method="post" class="mt-4" id="teacher-profile" v-cloak enctype="multipart/form-data">
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
                            <x-employee-id-tooltip /> &nbsp;
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

                    <div class="form-group row">
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
                        <select id="department" required="required" name="department_id" v-model="department_id"
                            class="form-control jufbs @error('department_id') is-invalid @enderror">
                            <option value="">Please Select a department</option>
                            <option v-for="(department, index) in departments.data" v-cloak :value="department.id">
                                @{{department.name}}
                            </option>
                        </select>
                        @error('department_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group row required">
                        <label for="designtion" class="col-form-label">Designation</label>
                        <select id="designtion" name="designation_id" required="required"
                            class="form-control jufbs @error('designation_id') is-invalid @enderror">
                            <option value="">Please Select a Designation</option>
                            @foreach ($designations as $designation)
                            <option value="{{$designation->id}}" @if (old('designation_id')==$designation->id)
                                selected
                                @endif
                                >{{$designation->name}}</option>
                            @endforeach
                        </select>
                        @error('designation_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group row required">
                        <label for="phone_no" class="col-form-label">Phone Number</label>
                        <input type="text" id="phone_no" name="cell_phone" required="required"
                            placeholder="Phone Number ..."
                            class="form-control @error('cell_phone') is-invalid @enderror"
                            value="{{old('cell_phone')}}">
                        @error('cell_phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group row required">
                        <label for="research" class="col-form-label">Research Interest</label>
                        <textarea class="form-control @error('research_interest') is-invalid @enderror"
                            name="research_interest" id="research"
                            required="required">{{old('research_interest')}}</textarea>
                        @error('research_interest')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group row required" v-if="!rquiredEmailApply">
                        <label for="email" class="col-form-label">Email</label>
                        <div class="input-group mb-3">
                            <input type="text" id="email" name="email" required="required"
                                placeholder="Write Only Username ..."
                                class="form-control @error('email') is-invalid @enderror" value="{{old('email')}}">
                            <div class="input-group-append">
                                <span class="input-group-text">{{$domain}}</span>
                            </div>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row ju-tac">
                        <div class="form-check">
                            <input type="checkbox" id="gridCheck1" class="form-check-input" name="official_email"
                                v-model="rquiredEmailApply">
                            <label for="gridCheck1" class="form-check-label"> Sorry, I don't have official email.
                            </label>
                        </div>
                    </div>

                    <div class="form-group row required" v-if="rquiredEmailApply">
                        <label for="current_email" class="col-form-label">Current Email</label>
                        <input type="email" id="current_email" name="additional_emails" required="required"
                            placeholder="Current Email ..."
                            class="form-control @error('additional_emails') is-invalid @enderror"
                            value="{{old('additional_emails')}}">
                        @error('additional_emails')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group row required" v-if="rquiredEmailApply">
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

                    <div class="form-group row" v-if="rquiredEmailApply">
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

                    <div class="form-group row" v-if="rquiredEmailApply">
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

                    <div class="form-group row required">
                        <label for="imgInp" class="col-form-label">Upload Image
                            (Maximum Size {{ App\Teacher::$imageMaxSize }}KB) </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="ju-f-u @error('image_url') dashed-error @enderror">
                                    Browse…
                                    <input type="file" id="imgInp" name="image_url">
                                </span>
                            </div>
                            @error('image_url')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <p><img id="img-upload" src="{{ asset('storage/image/no-image.png') }}"
                                class="mt-2 image-preview"></p>
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

@section('footerScript')
@include('laralum.include.vue.vue-axios')

<script>
    new Vue({
        el : "#teacher-profile",
        data : {
            rquiredEmailApply : "{{old('official_email', false)}}",
            departments : [],
            department_id : "{{old('department_id')}}",
            faculty_id : "{{old('faculty_id')}}",
        },

        methods : {
            getDepartmentList() {
                this.$http.get("{{ route('Frontend::department::search') }}", {
                    params: {
                        faculty_id : this.faculty_id,
                    }
                }).
                then(response => {
                    this.departments = response.data
                });
            }
        },

        created () {
            this.getDepartmentList();
        }
    });

    $(document).ready( function() {
        $(document).on('change', '.btn-file :file', function() {
            var input = $(this),
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [label]);
        });

        $('.btn-file :file').on('fileselect', function(event, label) {
            var input = $(this).parents('.input-group').find(':text'),
            log = label;

            if( input.length ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#img-upload').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imgInp").change(function(){
            readURL(this);
        });
    });
</script>

@endsection

@section('headerStyle')
<link rel="stylesheet" href="{{asset('css/frontend-form.css')}}">
<style>
    .btn-file {
        position: relative;
        overflow: hidden;
    }

    .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        background: white;
        cursor: inherit;
        display: block;
    }

    #img-upload {
        width: 200px;
        height: 200px;
    }
</style>
@endsection
