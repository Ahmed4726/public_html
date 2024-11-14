@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <a class="section" href="{{ route('Laralum::administration::list') }}">Administrative Member List</a>
    <i class="right angle icon divider"></i>
    <div class="active section">Administrative Member Edit</div>
</div>
@endsection
@section('title', 'Administrative Member Edit')
@section('icon', "edit")
@section('subtitle', $member->name)
@section('content')

<div class="ui doubling stackable grid container">
    <div class="row">
        <div class="two wide column"></div>
        <div class="twelve wide column" id="vue-app">
            <form class="ui form" method="POST"
                action="{{ route('Laralum::administration::update', ['member' => $member->id]) }}"
                enctype="multipart/form-data">
                <div class="ui very padded segment">
                    {{ csrf_field() }}

                    <div class="field required">
                        <label>Name</label>
                        <input type="text" name="name" placeholder="Name..." required value="{{$member->name}}">
                    </div>

                    <div class="field">
                        <label>Position</label>
                        <input type="text" name="designation" placeholder="Position..."
                            value="{{$member->designation}}">
                    </div>

                    <div class="field">
                        <label>Department</label>
                        <input type="text" name="department" placeholder="Department..."
                            value="{{$member->department}}">
                    </div>

                    <div class="field">
                        <label>Address</label>
                        <textarea class="tinymce" name="address" id="address">{{$member->address}}</textarea>
                    </div>

                    <div class="field">
                        <label>Primary Email</label>
                        <input type="text" name="primary_email" placeholder="Primary Email..."
                            value="{{$member->primary_email}}">
                    </div>

                    <div class="field">
                        <label>Other Emails</label>
                        <input type="text" name="other_emails" placeholder="Other Emails..."
                            value="{{$member->other_emails}}">
                    </div>

                    <div class="field">
                        <label>Website Link</label>
                        <input type="text" name="website_link" placeholder="Website Link..."
                            value="{{$member->website_link}}">
                    </div>

                    <div class="field">
                        <label>FAX</label>
                        <input type="text" name="fax" placeholder="FAX..." value="{{$member->fax}}">
                    </div>

                    <div class="field">
                        <label>FABX</label>
                        <input type="text" name="fabx" placeholder="FABX..." value="{{$member->fabx}}">
                    </div>

                    @can('ADMIN')
                    <div class="field">
                        <label>Configuration</label>
                        <textarea
                            name="config">{{stripcslashes(\Laralum::arrayToConfigFormat($member->config))}}</textarea>
                    </div>
                    @endcan

                    <div class="field">
                        <label>Message</label>
                        <textarea class="tinymce" name="message" id="message">{{$member->message}}</textarea>
                    </div>

                    <div class="field">
                        <label>Bio-Data</label>
                        <textarea class="tinymce" name="biography" id="biography">{{$member->biography}}</textarea>
                    </div>

                    <div class="inline field">
                        Max Upload Size : <label>500</label> kb
                    </div>

                    <div class="inline field">
                        Width : <label>236</label> px
                    </div>

                    <div class="inline field">
                        Height : <label>255</label> px
                    </div>

                    <div class="field">
                        <label>Upload Image</label>
                        <div class="input-group">
                            <span class="input-group-btn">
                                <span class="btn btn-default btn-file" id="btn-file">
                                    Browse… <input type="file" id="imgInp" name="image">
                                </span>
                            </span>
                            <input type="text" class="form-control" name="path" readonly style="border-radius: 0"
                                value="{{$member->image_url}}">
                        </div>
                        <br />
                        <img id="img-upload" @if($member->image_url)
                        src="{{ asset("storage/image/administration/$member->image_url") }}" @else
                        src="{{ asset('storage/image/no-image.png') }}" @endif/>
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
<script src="https://cdn.tiny.cloud/1/zbgkypr4zql81wjmlop63u6tbcu83synj6nql15gueb6zxfk/tinymce/5/tinymce.min.js"
    referrerpolicy="origin"></script>

<script type="text/javascript">
    tinymce.init({
            selector:'.tinymce',
            plugins: "advlist autolink link image lists charmap print preview autoresize table code",
            toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor"
        });

        /*setTimeout(function(){
            CKEDITOR.replace( 'biography' );
        },100);

        setTimeout(function(){
            CKEDITOR.replace( 'message' );
        },100);

        setTimeout(function(){
            CKEDITOR.replace( 'address' );
        },100);*/

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