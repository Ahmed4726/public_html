@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Banner Image Settings</div>
    </div>
@endsection
@section('title', 'Banner Image Settings')
@section('icon', "options")
@section('subtitle', 'Banner Image Setting')
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="nine wide column">
                <div class="ui padded segment">
                    <center>
                        <div class='ui statistic'>
                            <div class='label'>Update Banner Image</div>
                        </div>
                    </center>
                </div>
            </div>

            <div class="seven wide column">
                <div class="ui padded segment">
                    <center>
                        <div class='ui statistic'>
                            <div class='label'>Create Banner Image</div>
                        </div>
                    </center>
                </div>
            </div>
        </div>
    </div>

    <div class="ui doubling stackable grid container">
        <div class="row">
            {{--<div class="one wide column"></div>--}}
            <div class="nine wide column">
                <form class="ui form" method="POST">
                    @foreach($imageList as $image)
                        <div class="ui very padded segment">
                            {{ csrf_field() }}
                                <div class="field">
                                    <label>Title</label>
                                    <input type="text"  name="title" placeholder="title" value="{{ $image->title }}">
                                </div>

                                <div class="inline field">
                                    <div class="ui slider checkbox">
                                        <input type="checkbox" name="" tabindex="0" class="hidden" @if($image->enabled) checked="checked" @endif>
                                        <label>Enable</label>
                                    </div>
                                </div>

                                <div class="field">
                                    <label>Upload Image</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <span class="btn btn-default btn-file" id="btn-file-{{ $image->id }}">
                                                Browse… <input type="file" id="imgInp-{{ $image->id }}">
                                            </span>
                                        </span>
                                        <input type="text" class="form-control" readonly style="border-radius: 0" value="{{ $image->path }}">
                                    </div>
                                    <br/>
                                    <img id="img-upload-{{ $image->id }}" width="50%" src='{{ asset("image/gallery/$image->path") }}'/>
                                </div>

                            <br>
                            <button type="submit" class="ui blue submit button">{{ trans('laralum.submit') }}</button>
                        </div>
                    @endforeach
                </form>
            </div>

            <div class="seven wide column">
                <form class="ui form" method="POST" action="{{ url('admin/image') }}">
                        <div class="ui very padded segment">
                            {{ csrf_field() }}
                            <div class="field">
                                <label>Title</label>
                                <input type="text"  name="title" placeholder="title" >
                            </div>

                            <div class="inline field">
                                <div class="ui slider checkbox">
                                    <input type="checkbox" name="enabled" tabindex="0" class="hidden" value="off">
                                    <label>Enable</label>
                                </div>
                            </div>

                            <div class="field">
                                <label>Upload Image</label>
                                <div class="input-group">
                                        <span class="input-group-btn">
                                            <span class="btn btn-default btn-file" id="btn-file">
                                                Browse… <input type="file" id="imgInp" name="image">
                                            </span>
                                        </span>
                                    <input type="text" class="form-control" readonly style="border-radius: 0">
                                </div>
                                <br/>
                                <img id="img-upload" width="50%"/>
                            </div>

                            <input type="hidden" name="category_id" value="101">
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
            width: 100%;
        }
    </style>
@endsection


@section('js')
    <script type="text/javascript">
        $(document).ready( function() {
            @foreach($imageList as $image)
                $(document).on('change', "#btn-file-{{ $image->id }} :file" , function() {
                    var input = $(this),
                        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                    input.trigger('fileselect', [label]);
                });

                $("#btn-file-{{ $image->id }} :file").on('fileselect', function(event, label) {
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

                $("#imgInp-{{ $image->id }}").change(function(){
                    readURL(this, "img-upload-{{ $image->id }}");
                });

            @endforeach

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
