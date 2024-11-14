@extends('frontend.layout.main')
@section('content')

<div class="container student-email-apply" id="vue-app" v-cloak>

    <div class="content-section officer-page">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li aria-current="page" class="breadcrumb-item active">Student Email Apply</li>
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
                    <label for="programName" class="col-sm-3 col-form-label">Program Name</label>
                    <div class="col-sm-6">
                        <select class="form-control @error('program_id') is-invalid @enderror" id="programName"
                            name="program_id" v-model="program_id" @change="generateUserName(); verify()" required>
                            <option value="">Please Select a Program</option>
                            <option v-for="program in programs" :value="program.id">
                                @{{program.name}}
                            </option>
                        </select>
                        @error('program_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row" v-if="semester">
                    <label for="semester" class="col-sm-3 col-form-label">Semester</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control @error('semester') is-invalid @enderror" id="semester"
                            name="semester" placeholder="Semester ..." value="{{old('semester')}}">
                        @error('semester')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row required">
                    <label for="admissionSession" class="col-sm-3 col-form-label">Admission Session</label>
                    <div class="col-sm-6">
                        <select class="form-control @error('admission_session_id') is-invalid @enderror"
                            id="admissionSession" name="admission_session_id" v-model="admission_session_id"
                            @change="generateUserName" required>
                            <option value="">Please Select a Session</option>
                            <option v-for="session in admissionSessions" :value="session.id">
                                @{{session.name}}
                            </option>
                        </select>
                        @error('admission_session_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row required">
                    <label for="registrationNumber" class="col-sm-3 col-form-label">Registration Number</label>
                    <div class="col-sm-6">
                        <input type="text" :class="{ 'is-invalid': error }"
                            class="form-control @error('registration_number') is-invalid @enderror"
                            id="registrationNumber" name="registration_number" required
                            placeholder="Registration Number ..." @blur="verify" :readonly="readonly"
                            v-model="registrationNumber">
                        @error('registration_number')
                        <span v-if="!error" class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <span v-if="error" v-for="message in errorMessages" class="invalid-feedback" role="alert">
                            <strong>@{{message}}</strong>
                        </span>
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
                            name="department_id" required v-model="department_id">
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
                </div>

                <div class="form-group row required">
                    <label for="hallName" class="col-sm-3 col-form-label">Hall Name</label>
                    <div class="col-sm-6">
                        <select class="form-control @error('hall_id') is-invalid @enderror" id="hallName" name="hall_id"
                            required v-model="hall_id">
                            <option value="">--- Please Select a Hall ---</option>
                            @foreach($halls as $hall)
                            <option value="{{$hall->id}}">
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
                    <label for="studentName" class="col-sm-3 col-form-label">Name</label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                            id="studentName" name="first_name" placeholder="First ..." v-model="firstName"
                            @blur="getUserName(firstName, 0)" :readonly="fNameReadOnly" required>
                        @error('first_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-sm-2">
                        <input type="text" class="form-control @error('middle_name') is-invalid @enderror"
                            name="middle_name" placeholder="Middle ..." v-model="middleName"
                            @blur="getUserName(middleName, 1)" :readonly="mNameReadOnly">
                        @error('middle_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-sm-2">
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                            name="last_name" placeholder="Last ..." v-model="lastName" @blur="getUserName(lastName, 2)"
                            :readonly="lNameReadOnly" required>
                        @error('last_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
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

                        <div v-if="render" class="progress mt-2" style="width: 200px">
                            <div :style="{ width: render + '%' }" class="progress-bar" role="progressbar"
                                :aria-valuenow="render" aria-valuemin="0" aria-valuemax="100">
                                @{{render}}%</div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="render_reg_num" v-model="renderRegNum">

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
                        <button type="submit" class="btn btn-primary" :disabled="!agreed || !applyAble">Apply</button>
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
<script src='https://unpkg.com/tesseract.js@v2.1.0/dist/tesseract.min.js'></script>

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
                vue.renderIDCard(input.files[0]);
            }
        }

        $("#imgInp").change(function(){
            readURL(this);
        });
    });

    var vue = new Vue({
            el : "#vue-app",
            data : {
                admission_session_id :"{{old('admission_session_id')}}",
                program_id :"{{old('program_id')}}",
                department_id :"{{old('department_id')}}",
                hall_id :"{{old('hall_id')}}",
                departments : {},
                admissionSessions : {!!$sessions->toJson()!!},
                programs : @json($programs),
                faculty_id : "",
                agreed: true,
                applyAble: true,
                registrationNumber : "{{old('registration_number', '')}}",
                renderRegNum : false,
                firstName : "{{old('first_name', '')}}",
                middleName : "{{old('middle_name', '')}}",
                lastName : "{{old('last_name', '')}}",
                loading : false,
                error: false,
                errorMessages: [],
                usernames : [
                    {name : ''},
                    {name : ''},
                    {name : ''},
                ],
                readonly : false,
                lNameReadOnly : false,
                fNameReadOnly : false,
                mNameReadOnly : false,
                render : 0,
                semester : false
            },

            methods : {
                searchRegNumFromNextLine (lines, search = 'student id number') {
                    let searchLineIndex = lines.findIndex(({text}) => text.toLowerCase().includes(search));

                    if(searchLineIndex !== -1){
                        searchLineIndex++;
                        let regNumIndex = lines[searchLineIndex].words.findIndex(({text}) => text.length > 3);
                        if(regNumIndex !== -1){
                            return lines[searchLineIndex].words[regNumIndex].text;
                        }
                    }

                    return false;
                },

                searchRegNumFromLine (lines, lineSearch, wordSearch) {
                    let expectedLine = lines.filter(({text}) => text.toLowerCase().includes(lineSearch));
                    
                    if(expectedLine.length > 0){
                        expectedLine = expectedLine[0];
                        let searchIndex = expectedLine.words.findIndex( ({text}) => text.toLowerCase().includes(wordSearch));

                        if(searchIndex !== -1){
                            for(searchIndex++; searchIndex < expectedLine.words.length; searchIndex++){
                                if(expectedLine.words[searchIndex].text.length> 3){
                                    return expectedLine.words[searchIndex].text;
                                }
                            }
                        }
                    }

                    return false;
                },

                renderIDCard(image) {
                    this.applyAble = false;
                    Tesseract.recognize( image,'eng', { 
                        logger: m => {
                            this.render = 1;
                            if(m.status == "recognizing text" && m.progress){
                                this.render = Math.round(m.progress*100);
                            }
                        }
                    })
                    .then(({data}) => {
                        let regNum;
                        this.text = data.text;
                        if(!(regNum = this.searchRegNumFromNextLine(data.lines))){
                            if(!(regNum = this.searchRegNumFromLine(data.lines, 'reg. number', 'number'))){
                                if(!(regNum = this.searchRegNumFromLine(data.lines, 'reg. no', 'no'))){
                                    if(!(regNum = this.searchRegNumFromLine(data.lines, 'student id', 'id'))){
                                        if(!(regNum = this.searchRegNumFromLine(data.lines, 'id no', 'no'))){
                                            if(!(regNum = this.searchRegNumFromLine(data.lines, 'no.', 'no'))){
                                                if(!(regNum = this.searchRegNumFromLine(data.lines, 'reg', 'reg'))){
                                                    regNum = false;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if(regNum){
                            this.renderRegNum = regNum.replace(/[:]/g,'');
                        }
                        this.applyAble = true;
                    })
                    .catch(err => this.applyAble = true);
                },

                verify() {
                    let verifyRequired = this.programs.find(program => (program.id == this.program_id) && program.verifyable);
                    if(this.registrationNumber){
                        this.applyAble = false;
                        this.loading = true;
                        this.$http.get("{{ route('Frontend::student::verify') }}", {
                            params: {
                                registration_number : this.registrationNumber,
                            }
                        }).
                        then(({data}) => {
                            this.loading = false;
                            this.applyAble = true;
                            this.error = false;

                            let student = data.data;
                            console.log(student);
                            this.readonly = true;
                            this.admission_session_id = student.admission_session_id;
                            
                            if(student.department_id){
                                this.department_id = student.department_id;
                            }

                            if(student.department && student.department.faculty_id){
                                this.faculty_id = student.department.faculty_id;
                            }

                            if(student.hall_id){
                                this.hall_id = student.hall_id;
                            }
                            
                            this.processName(student.name);

                            if(data.count > 1){
                                this.fNameReadOnly = false;
                                this.mNameReadOnly = false;
                                this.lNameReadOnly = false;
                            }
                        })
                        .catch(({response}) => {
                            if(verifyRequired){
                                this.error = true;
                                this.errorMessages = Object.entries(response.data.errors)[0][1];
                            }else{
                                this.applyAble = true;
                                this.error = false;
                            }

                            this.loading = false;
                            this.readonly = false;
                            this.lNameReadOnly = false;
                            this.mNameReadOnly = false;
                            this.fNameReadOnly = false;
                        });
                    }
                },

                reset() {
                    [
                        'admission_session_id',
                        'program_id',
                        'department_id',
                        'faculty_id',
                        'hall_id',
                        'firstName',
                        'middleName',
                        'lastName'
                    ].forEach(element => this[element] = '');
                    this.usernames[0].name = '';
                    this.usernames[1].name = '';
                    this.usernames[2].name = '';
                },
                
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
                    this.semester = this.programs.find((program) => program.id == this.program_id && program.name == 'Weekend')
                                    ? true : false;
                        
                    this.getUserName(this.firstName, 0);
                    this.getUserName(this.middleName, 1);
                    this.getUserName(this.lastName, 2);
                },

                getUserName(name, number) {
                    if(!name) {
                        this.usernames[number].name = '';
                        return;
                    }

                    if(!this.admission_session_id || !this.program_id){
                        return;
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

                processName(name){
                    if(name){
                        let splittedName = name.trim().split(' ');
                        this.mNameReadOnly = true;

                        this.firstName = splittedName[0];
                        this.fNameReadOnly = true;

                        if(splittedName.length == 2){
                            this.lastName = splittedName[1];
                            this.lNameReadOnly = true;
                        }else if(splittedName.length > 2) {
                            this.middleName = splittedName[1];
                            this.lastName = splittedName.splice(2, 10).join(' ');
                            this.lNameReadOnly = true;
                        }
                        this.generateUserName();
                    }
                }
                
            },

            computed: {
                isAvailableUserName(){
                return this.usernames.filter(username => username.name != '');
                }
            },

            created () {
                this.getDepartmentList();
                this.generateUserName();
                this.verify();
            }
        });

</script>

@endsection