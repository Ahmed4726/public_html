@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::department::list') }}">Department List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Department Update</div>
    </div>
@endsection
@section('title', 'Department Update')
@section('icon', "edit")
@section('subtitle', $department->name)
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column" id="vue-app">
                <form class="ui form" method="POST" action="{{ route('Laralum::department::update', ['department' => $department->id]) }}" enctype="multipart/form-data">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        @can('ADMIN')<div class="field required">
                            <label>Name</label>
                            <input type="text"  name="name" placeholder="Name..." value="{{ $department->name }}" required>
                        </div>@endcan

                        <div class="field">
                            <label>Name in Bangla</label>
                            <input type="text"  name="name_ben" placeholder="Name in Bangla..." value="{{ $department->name_ben }}">
                        </div>

                        <div class="field required">
                            <label>Short Name</label>
                            <input type="text"  name="short_name" placeholder="Short Name..." value="{{ $department->short_name }}" required>
                        </div>

                        <div class="field">
                            <label>Short Name in Bangla</label>
                            <input type="text"  name="short_name_ben" placeholder="Short Name in Bangla..." value="{{ $department->short_name_ben }}">
                        </div>

                        @can('ADMIN')
                        <div class="field required">
                            <label>Faculty</label>
                            <select name="faculty_id" required>
                                @foreach($faculties as $faculty)
                                    <option @if($faculty->id == $department->faculty_id) selected="selected" @endif value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field required">
                            <label>Slug (User Friendly URL)</label>
                            <input type="text"  name="slug" placeholder="Slug..." value="{{ $department->slug }}" required>
                        </div>
                        @endcan

                        <div class="ui calendar field" id="establishDate">
                            <label>Established Date</label>
                            <div class="ui input left icon">
                                <i class="calendar icon"></i>
                                <input type="text" name="dob" id="datePicker" placeholder="Established Date.." autocomplete="off" value="{{$department->dob }}">
                            </div>
                        </div>

                        <div class="field">
                            <label>Contact Email</label>
                            <input type="email"  name="contact_email" placeholder="Contact Email..." value="{{ $department->contact_email }}">
                        </div>

                        <div class="field">
                            <label>Contact Mobile Number</label>
                            <input type="text"  name="contact_mobile_number" placeholder="Contact Moblie Number..." value="{{ $department->contact_mobile_number }}">
                        </div>

                        <div class="field">
                            <label>Contact Phone Number</label>
                            <input type="text"  name="contact_phone_number" placeholder="Contact Phone Number..." value="{{ $department->contact_phone_number }}">
                        </div>

                        <div class="field">
                            <label>Description</label>
                            <textarea name="description" class="tinymce" id="description">{{$department->description}}</textarea>
                        </div>

                        <div class="field">
                            <label>Message From Chairman</label>
                            <textarea name="message_from_chairman" class="tinymce" id="message_from_chairman">{{$department->message_from_chairman}}</textarea>
                        </div>

                        @can('ADMIN')
                            <div class="field">
                                <label>Configuration</label>
                                <textarea name="config">{{stripcslashes(\Laralum::arrayToConfigFormat($department->config))}}</textarea>
                            </div>
                        @endcan

                        <div class="field">
                            <label>External Link</label>
                            <input type="text"  name="external_link" placeholder="External Link..." value="{{ $department->external_link }}">
                        </div>

                        <div class="inline field">
                            Max Upload Size : <label>500</label> kb
                        </div>

                        <div class="inline field">
                            Width : <label>600</label> px
                        </div>

                        <div class="inline field">
                            Height : <label>400</label> px
                        </div>

                        <div class="field">
                            <label>Upload Image</label>
                            <div class="input-group">
                                        <span class="input-group-btn">
                                            <span class="btn btn-default btn-file" id="btn-file">
                                                Browse… <input type="file" id="imgInp" name="image">
                                            </span>
                                        </span>
                                <input type="text" class="form-control" name="path" readonly style="border-radius: 0" value="{{ $department->image_url }}">
                            </div>
                            <br/>
                            <img id="img-upload" @if($department->image_url) src='{{asset("storage/image/department/$department->image_url")}}' @else src="{{ asset('storage/image/no-image.png') }}" @endif />
                        </div>

                        <br>
                        <button type="submit" class="ui blue submit button">Save</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection


@section('css')
    <style>

        .label{
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

        .input-group-btn, .input-group .form-control {
            display: table-cell;
        }

        .input-group-btn:first-child>.btn {
            margin-right: -1px;
        }

        .input-group .form-control:first-child, .input-group-btn:first-child>.btn, .input-group-btn:first-child>.btn-group>.btn, .input-group-btn:last-child>.btn:not(:last-child):not(.dropdown-toggle), .input-group-btn:last-child>.btn-group:not(:last-child)>.btn {
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
        .open > .dropdown-toggle.btn-default {
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

        .input-group .form-control:last-child, .input-group-btn:last-child>.btn, .input-group-btn:last-child>.btn-group>.btn, .input-group-btn:first-child>.btn:not(:first-child), .input-group-btn:first-child>.btn-group:not(:first-child)>.btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .input-group-btn, .input-group .form-control {
            display: table-cell;
        }

        .input-group .form-control {
            position: relative;
            z-index: 2;
            float: left;
            width: 100%;
            margin-bottom: 0;
        }

        .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
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
            -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
            -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
            -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
            transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
        }

        input, button, select, textarea {
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

        #img-upload{
            width: 200px;
            height: 200px;
        }
    </style>
@endsection


@section('js')
    @include('laralum.include.vue.vue-axios')
    @include('laralum.include.jquery-ui')
    <script src="https://cdn.tiny.cloud/1/zbgkypr4zql81wjmlop63u6tbcu83synj6nql15gueb6zxfk/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

    <script type="text/javascript">

        tinymce.init({
            selector:'.tinymce',
            plugins: "advlist autolink link image lists charmap print preview autoresize table code",
            toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor"
        });

        /*setTimeout(function(){
            CKEDITOR.replace( 'message_from_chairman' );
        },100);

        setTimeout(function(){
            CKEDITOR.replace( 'description' );
        },100);*/

        $(document).ready( function() {

            $( "#datePicker" ).datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true
            });

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
