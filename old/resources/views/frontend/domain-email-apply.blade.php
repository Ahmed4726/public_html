@extends('frontend.layout.main')
@section('content')

<div class="container" id="vue-app" v-cloak>

    <div class="content-section officer-page">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li aria-current="page" class="breadcrumb-item active">Student Student Email Apply</li>
                    </ol>
                </nav>
            </div>
        </div>


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
            <div class="col-md-3"></div>
            <div class="col-md-7">
                <h2>JU Student Email ID Apply Form</h2>
            </div>
        </div>
        <br />

        <div class="row">

            <div class="loading" v-if="loading"></div>

            <div class="col-md-3"></div>
            <form class="col-md-7 domain-apply" method="post" enctype="multipart/form-data">

                {{csrf_field()}}

                <div class="form-group row required">
                    <label for="admissionSession" class="col-sm-3 col-form-label">Admission Session</label>
                    <div class="col-sm-6">
                        <select class="form-control @error('admission_session_id') is-invalid @enderror"
                            id="admissionSession" name="admission_session_id" v-model="admission_session_id"
                            @change="generateUserName" required>
                            @foreach ($sessions as $session)
                            <option value="{{$session->id}}" @if(old('admission_session_id')==$session->id) selected
                                @endif>
                                {{$session->name}}
                            </option>
                            @endforeach
                        </select>
                        @error('admission_session_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row required">
                    <label for="programName" class="col-sm-3 col-form-label">Program Name</label>
                    <div class="col-sm-6">
                        <select class="form-control @error('program_id') is-invalid @enderror" id="programName"
                            name="program_id" v-model="program_id" @change="generateUserName" required>
                            @foreach ($programs as $program)
                            <option value="{{$program->id}}" @if(old('program_id')==$program->id) selected @endif>
                                {{$program->name}}
                            </option>
                            @endforeach
                        </select>
                        @error('program_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="facultyName" class="col-sm-3 col-form-label">Faculty Name</label>
                    <div class="col-sm-6">
                        <select class="form-control" id="facultyName" v-model="faculty_id"
                            @change="getDepartmentList()">
                            <option value="">Please Select a Faculty</option>
                            @foreach($faculties as $faculty)
                            <option value="{{$faculty->id}}">{{$faculty->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row required">
                    <label for="departmentName" class="col-sm-3 col-form-label">Department / Institute</label>
                    <div class="col-sm-6 ">
                        <select class="form-control @error('department_id') is-invalid @enderror" id="departmentName"
                            name="department_id" required>
                            <option value="">Please Select a department</option>
                            <option v-for="(department, index) in departments.data" v-cloak
                                :selected="department.id == '{{old('department_id')}}'" :value="department.id">
                                @{{department.name}}
                            </option>
                        </select>
                        @error('department_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row required">
                    <label for="hallName" class="col-sm-3 col-form-label">Hall Name</label>
                    <div class="col-sm-6">
                        <select class="form-control @error('hall_id') is-invalid @enderror" id="hallName" name="hall_id"
                            required>
                            <option value="">--- Please Select a Hall ---</option>
                            @foreach($halls as $hall)
                            <option value="{{$hall->id}}" @if(old('hall_id')==$hall->id) selected @endif>
                                {{$hall->name}}
                            </option>
                            @endforeach
                        </select>
                        @error('hall_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row required">
                    <label for="registrationNumber" class="col-sm-3 col-form-label">Registration Number</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control @error('registration_number') is-invalid @enderror"
                            id="registrationNumber" name="registration_number" required
                            placeholder="Registration Number ..." value="{{old('registration_number')}}">
                        @error('registration_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row required">
                    <label for="studentName" class="col-sm-3 col-form-label">Name</label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                            id="studentName" name="first_name" placeholder="First ..." v-model="firstName"
                            @blur="getUserName(firstName, 0)" required>
                        @error('first_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-sm-2">
                        <input type="text" class="form-control @error('middle_name') is-invalid @enderror"
                            name="middle_name" placeholder="Middle ..." v-model="middleName"
                            @blur="getUserName(middleName, 1)">
                    </div>
                    <div class="col-sm-2">
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                            name="last_name" placeholder="Last ..." v-model="lastName" @blur="getUserName(lastName, 2)"
                            required>
                    </div>
                </div>

                <div class="form-group row required" v-if="isAvailableUserName.length > 0">
                    <label class="col-sm-3 col-form-label">Choose UserName</label>
                    <div class="col-sm-9 mt-2">
                        <div v-for="(username, key) in usernames" class="form-check form-check-inline"
                            v-if="username.name">
                            <input class="form-check-input @error('username') is-invalid @enderror" type="radio"
                                name="username" :id="key" :value="username.name" required>
                            <label class="form-check-label" :for="key">@{{username.name}}</label>
                        </div>
                        <input class="form-check-input @error('username') is-invalid @enderror" type="radio"
                            style="display: none">
                        @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row required">
                    <label for="contactNumber" class="col-sm-3 col-form-label">Contact Number</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control @error('contact_phone') is-invalid @enderror"
                            id="contactNumber" name="contact_phone" required placeholder="Contact Number ..."
                            value="{{old('contact_phone')}}">
                        @error('contact_phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row required">
                    <label for="existingEmail" class="col-sm-3 col-form-label">Existing Email ID</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control @error('existing_email') is-invalid @enderror"
                            id="existingEmail" name="existing_email" required
                            placeholder="Existing Alternate Email ID ..." value="{{old('existing_email')}}">
                        @error('existing_email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row required">
                    <label for="existingEmail" class="col-sm-3 col-form-label">Front-Side of University ID Card Image
                        (Maximum Size {{ App\StudentEmailApply::$maxIDUploadSize }}KB)
                    </label>
                    <div class="col-sm-6 ">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="btn btn-outline-secondary btn-file">
                                    Browse… <input type="file" id="imgInp" name="id_card" required>
                                </span>
                            </div>
                            <input type="text" class="form-control @error('id_card') is-invalid @enderror" readonly
                                required>
                            @error('id_card')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <img id='img-upload' class="mt-2 image-preview"
                            src="{{ asset('storage/image/no-image.png') }}" />
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-3"></div>
                    <label for="existingEmail" class="col-sm-6 col-form-label">
                        <a target="_blank" href="{{asset('Terms-Conditions.pdf')}}"><i class="fa fa-angle-double-right"
                                aria-hidden="true"></i> Click here to read terms
                            & condition</a>
                    </label>
                </div>

                <div class="form-group row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="gridCheck1" v-model="agreed">
                            <label class="form-check-label" for="gridCheck1">
                                Yes, I have read & agreed.
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6 domain-apply-button">
                        <button type="submit" class="btn btn-primary" :disabled="!agreed">Apply</button>
                    </div>
                </div>
            </form>

        </div>

    </div>

</div>
@endsection

@section('headerStyle')
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

@section('footerScript')

@include('laralum.include.vue.vue-axios')

<script type="text/javascript">
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

    new Vue({
            el : "#vue-app",
            data : {
                departments : {},
                faculty_id : "",
                agreed: true,
                firstName : "{{old('first_name', '')}}",
                middleName : "{{old('middle_name', '')}}",
                lastName : "{{old('last_name', '')}}",
                loading : false,
                admission_session_id :"{{old('admission_session_id', $sessions->first()->id)}}",
                program_id :"{{old('program_id', $programs->first()->id)}}",
                usernames : [
                    {name : ''},
                    {name : ''},
                    {name : ''},
                ]
            },

            methods : {
                getDepartmentList() {
                    this.$http.get("{{ route('Frontend::department::search') }}", {
                        params: {
                            faculty_id : this.faculty_id,
                        }
                    }).
                    then(response => {
                        this.departments = response.data;
                        @if(old('department_id'))
                        this.faculty_id = this.departments.data.filter(department => department.id == {{old('department_id')}})[0].faculty_id;
                        @endif
                    });
                },

                generateUserName() {
                    this.getUserName(this.firstName, 0);
                    this.getUserName(this.middleName, 1);
                    this.getUserName(this.lastName, 2);
                },

                getUserName(name, number) {
                    if(!name) {
                        this.usernames[number].name = '';
                        return false;
                    }

                    this.loading = true;
                    this.$http.get("{{ route('Frontend::student::email::username:generate') }}", {
                        params: {
                            name : name,
                            admission_session_id : this.admission_session_id,
                            program_id : this.program_id,
                        }
                    }).
                    then(({data}) => {
                        this.loading = false;
                        this.usernames[number].name = data;
                    });
                },

                
            },

            computed: {
                isAvailableUserName(){
                return this.usernames.filter(username => username.name != '');
                }
            },

            created () {
                this.getDepartmentList();
                this.generateUserName();
            }
        });

</script>

@endsection