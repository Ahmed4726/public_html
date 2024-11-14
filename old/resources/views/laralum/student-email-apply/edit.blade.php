@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <a class="section" href="{{ route('Laralum::student-email-apply::list') }}">Student Email Apply List</a>
    <i class="right angle icon divider"></i>
    <div class="active section">Email Apply Edit</div>
</div>
@endsection
@section('title', 'Email Apply Edit')
@section('icon', "edit")
@section('subtitle', $email->name)
@section('content')

<div class="ui doubling stackable grid container">
    <div class="row">
        <div class="two wide column"></div>
        <div class="twelve wide column" id="vue-app">
            <form class="ui form" method="POST"
                action="{{ route('Laralum::student-email-apply::update', ['email' => $email->id]) }}"
                enctype="multipart/form-data">
                <div class="ui very padded segment">
                    {{ csrf_field() }}

                    <div class="field required">
                        <label>Admission Session</label>
                        <select name="admission_session_id" required>
                            @foreach($sessions as $session)
                            <option value="{{ $session->id }}" @if((old('admission_session_id', $email->
                                admission_session_id)) == $session->id)
                                selected
                                @endif>
                                {{ $session->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field required">
                        <label>Registration Number</label>
                        <input type="text" name="registration_number" placeholder="Registration Number..."
                            value="{{old('registration_number', $email->registration_number)}}" required>
                    </div>

                    <div class="field required">
                        <label>Student Name</label>
                        <div class="three fields">
                            <div class="field">
                                <input type="text" name="first_name" placeholder="First Name..."
                                    value="{{old('first_name', $email->first_name)}}" required>
                            </div>
                            <div class="field">
                                <input type="text" name="middle_name" placeholder="Middle Name..."
                                    value="{{old('middle_name', $email->middle_name)}}">
                            </div>
                            <div class="field">
                                <input type="text" name="last_name" placeholder="Last Name..."
                                    value="{{old('last_name', $email->last_name)}}" required>
                            </div>
                        </div>
                    </div>

                    <div class="field required">
                        <label>Email</label>
                        <div class="ui right labeled input">
                            <input type="text" name="username" placeholder="Username..."
                                value="{{old('username', $email->username)}}" required>
                            <div class="ui basic label" style="padding: .45em 1em;">
                                @juniv.edu
                            </div>
                        </div>
                    </div>

                    <div class="field required">
                        <label>Password</label>
                        <input type="text" name="password" placeholder="Password..."
                            value="{{old('password', $email->password)}}" required>
                    </div>

                    <div class="field">
                        <label>Faculty</label>
                        <select @change="getDepartmentList()" v-model="faculty_id">
                            <option value="">--- Select a Faculty ---</option>
                            @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field required">
                        <label>Department</label>
                        <select required name="department_id">
                            <option value="">--- Select a Department ---</option>
                            <option v-for="(department, index) in departments.data" v-cloak
                                :selected="department.id == '{{old('department_id', $email->department_id)}}'"
                                :value="department.id">
                                @{{department.name}}
                            </option>
                        </select>
                    </div>

                    <div class="field required">
                        <label>Program Name</label>
                        <select name="program_id" required>
                            @foreach($programs as $program)
                            <option value="{{ $program->id }}" @if((old('program_id', $email->program_id)) ==
                                $program->id)
                                selected
                                @endif>
                                {{ $program->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label>Hall Name</label>
                        <select name="hall_id">
                            <option value="">--- Please Seletct a Hall ---</option>
                            @foreach($halls as $hall)
                            <option value="{{ $hall->id }}" @if((old('hall_id', $email->hall_id)) == $hall->id) selected
                                @endif>
                                {{ $hall->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field required">
                        <label>Status</label>
                        <select name="global_status_id" required>
                            @foreach($statuses as $status)
                            <option value="{{ $status->id }}" @if((old('global_status_id', $email->global_status_id)) ==
                                $status->id) selected @endif>
                                {{ $status->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field required">
                        <label>Contact Number</label>
                        <input type="text" name="contact_phone" placeholder="Contact Number..."
                            value="{{old('contact_phone', $email->contact_phone)}}" required>
                    </div>

                    <div class="field required">
                        <label>Existing Email ID</label>
                        <input type="text" name="existing_email" placeholder="Existing Email ID..."
                            value="{{old('existing_email', $email->existing_email)}}" required>
                    </div>

                    <div class="inline field">
                        Max Upload Size : <label>{{ App\StudentEmailApply::$maxIDUploadSize }}</label> kb
                    </div>

                    <div class="field">
                        <label>Front-Side of ID Card Image</label>
                        <div class="input-group">
                            <span class="input-group-btn">
                                <span class="btn btn-default btn-file" id="btn-file">
                                    Browse… <input type="file" id="imgInp" name="id_card">
                                </span>
                            </span>
                            <input type="text" class="form-control" name="path" value="{{$email->image}}" readonly>
                        </div>
                        <br />
                        <img id="img-upload" class="image-preview"
                            src='{{asset("storage/image/student-email-apply/$email->image")}}' />
                    </div>

                    <br>
                    <button type="submit" class="ui blue submit button">Update</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection


@section('css')
<style>
    .label {
        font-size: 1.5em !important;
    }

    .input-group {
        position: relative;
        display: table;
        border-collapse: separate;
    }

    .input-group-btn {
        position: relative;
        font-size: 0;
        white-space: nowrap;
    }

    .input-group-btn {
        width: 1%;
        white-space: nowrap;
        vertical-align: middle;
    }

    .input-group-btn,
    .input-group .form-control {
        display: table-cell;
    }

    .input-group-btn:first-child>.btn {
        margin-right: -1px;
    }

    .input-group .form-control:first-child,
    .input-group-btn:first-child>.btn,
    .input-group-btn:first-child>.btn-group>.btn,
    .input-group-btn:last-child>.btn:not(:last-child):not(.dropdown-toggle),
    .input-group-btn:last-child>.btn-group:not(:last-child)>.btn {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .input-group-btn>.btn {
        position: relative;
    }

    .btn-default {
        color: #333;
        background-color: #fff;
        border-color: #ccc !important;
    }

    .btn-default:hover,
    .btn-default:focus,
    .btn-default.focus,
    .btn-default:active,
    .btn-default.active,
    .open>.dropdown-toggle.btn-default {
        color: #333;
        background-color: #e6e6e6;
        border-color: #adadad;
    }

    .btn {
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: 400;
        line-height: 1.42857143;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
    }

    .input-group .form-control:last-child,
    .input-group-btn:last-child>.btn,
    .input-group-btn:last-child>.btn-group>.btn,
    .input-group-btn:first-child>.btn:not(:first-child),
    .input-group-btn:first-child>.btn-group:not(:first-child)>.btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .input-group-btn,
    .input-group .form-control {
        display: table-cell;
    }

    .input-group .form-control {
        position: relative;
        z-index: 2;
        float: left;
        width: 100%;
        margin-bottom: 0;
    }

    .form-control[disabled],
    .form-control[readonly],
    fieldset[disabled] .form-control {
        cursor: not-allowed;
        background-color: #eee;
        opacity: 1;
    }

    .form-control {
        display: block;
        width: 100%;
        height: 34px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
        -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
        transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    }

    input,
    button,
    select,
    textarea {
        font-family: inherit;
        font-size: inherit;
        line-height: inherit;
    }



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


@section('js')
@include('laralum.include.vue.vue-axios')
<script src="https://cdn.tiny.cloud/1/zbgkypr4zql81wjmlop63u6tbcu83synj6nql15gueb6zxfk/tinymce/5/tinymce.min.js"
    referrerpolicy="origin"></script>

<script type="text/javascript">
    new Vue({
        el : "#vue-app",
        data : {
            departments : {},
            faculty_id : "",
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
                    @if($departmentID = old('department_id', $email->department_id))
                        this.faculty_id = this.departments.data.filter(department => department.id == {{$departmentID}})[0].faculty_id;
                    @endif
                });
            }
        },
        created () {
            this.getDepartmentList();
        }
    });

    $(document).ready( function() {

        $(document).on('change', "#btn-file :file" , function() {
            var input = $(this),
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [label]);
        });

        $("#btn-file :file").on('fileselect', function(event, label) {
            var input = $(this).parents('.input-group').find(':text'),
                log = label;

            if( input.length ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }

        });

        function readURL(input, printId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    console.log(e.target.result);
                    $("#"+printId).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imgInp").change(function(){
            readURL(this, "img-upload");
        });
    });
</script>
@endsection