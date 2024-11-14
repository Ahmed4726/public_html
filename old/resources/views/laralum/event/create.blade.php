@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if(isset($uri)) <a class="section" href="{{ route("Laralum::$uri::event::list", [$uri => $uriValue]) }}">All Notice List</a>
        @else <a class="section" href="{{ route('Laralum::event::list') }}">All Notice List</a> @endif
        <i class="right angle icon divider"></i>
        <div class="active section">Create All Notice</div>
    </div>
@endsection
@section('title', 'Create All Notice')
@section('icon', "plus")
@section('subtitle', 'Create All Notice')
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column" id="vue-app">
                <form class="ui form" method="POST" action="{{ route('Laralum::event::store') }}" enctype="multipart/form-data">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field required">
                            <label>Title</label>
                            <input type="text"  name="title" placeholder="title" required>
                        </div>

                        <div class="field required">
                            <label>Type</label>
                            <select name="event_id" @change="eventChange($event)" required v-model="event_id">
                                <option value=""> Please Select a Type</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}">{{ $event->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field">
                            <label>Details</label>
                            <textarea name="details" id="details"></textarea>
                        </div>

                        <div class="field">
                            <label>External Link</label>
                            <input type="text" name="external_link" placeholder="External Link...">
                        </div>

                        <div class="field">
                            <label>Reporter</label>
                            <input type="text" name="reporter" placeholder="Reporter...">
                        </div>

                        <div class="ui segment">
                            <div class="field">
                                <div class="ui toggle checkbox">
                                    <input id="enable" type="checkbox" name="enabled" tabindex="0" class="hidden" checked="checked">
                                    <label for="enable">Enable</label>
                                </div>
                            </div>
                        </div>

                        @if(Auth::user()->hasRole('ADMIN'))<div class="ui segment" v-if=" event_id==1 || event_id==2 || event_id==4 || event_id==6 ">
                            <div class="field">
                                <div class="ui toggle checkbox">
                                    <input id="featured" type="checkbox" name="featured_news" tabindex="0" class="hidden">
                                    <label for="featured">Featured</label>
                                </div>
                            </div>
                        </div>@endif

                        <div class="ui segment" v-if=" event_id==1 || event_id==2 || event_id==3 || event_id==4 || event_id==6 ">
                            <div class="field">
                                <div class="ui toggle checkbox">
                                    <input id="highlight" type="checkbox" name="highlight" tabindex="0" class="hidden">
                                    <label for="highlight">Highlight</label>
                                </div>
                            </div>
                        </div>

                        @if(!isset($uri))<div class="ui segment" v-if=" event_id==1 || event_id==2 || event_id==4 || event_id==6 ">
                            <div class="field">
                                <div class="ui toggle checkbox">
                                    <input id="spotlight" type="checkbox" name="spotlight" tabindex="0" class="hidden">
                                    <label for="spotlight">Spotlight</label>
                                </div>
                            </div>
                        </div>@endif

                        <div class="ui segment">
                            <div class="field">
                                <div class="ui toggle checkbox">
                                    <input id="showPublish" type="checkbox" name="show_publish_date" tabindex="0" class="hidden" checked="checked">
                                    <label for="showPublish">Show Publish Date</label>
                                </div>
                            </div>
                        </div>

                        <div class="ui calendar field" id="date">
                            <label>Publish Date</label>
                            <div class="ui input left icon">
                                <i class="calendar icon"></i>
                                <input type="text" name="publish_date" id="datePicker" placeholder="Publish Date.." autocomplete="off" value="{{date('M d, Y')}}">
                            </div>
                        </div>

                        @if(isset($uri) && $uri == 'department')
                            <input type="hidden" name="department_id" value="{{$uriValue}}">
                        @endif

                        <div class="inline field" v-if="maxSize">
                            Max Upload Size : <label v-text="maxSize"></label> kb
                        </div>

                        <div class="inline field" v-if="width">
                            Width : <label v-text="width"></label> px
                        </div>

                        <div class="inline field" v-if="height">
                            Height : <label v-text="height"></label> px
                        </div>


                        <div class="field">
                            <label>Upload Image</label>
                            <div class="input-group">
                                        <span class="input-group-btn">
                                            <span class="btn btn-default btn-file" id="btn-file">
                                                Browse… <input type="file" id="imgInp" name="image">
                                            </span>
                                        </span>
                                <input type="text" class="form-control" name="path" readonly style="border-radius: 0">
                            </div>
                            <br/>
                            <img id="img-upload" src="{{ asset('storage/image/no-image.png') }}"/>
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
            selector:'#details',
            plugins: "advlist autolink link image lists charmap print preview autoresize table code",
            toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor"
        });

        /*setTimeout(function(){
            CKEDITOR.replace( 'details' );
        },100);*/

        new Vue({
            el : "#vue-app",
            data : {
                events : {!! $events !!},
                maxSize : '',
                width : '',
                height : '',
                event_id : ''
            },

            methods : {

                eventChange (event) {
                    this.getSpecificInfoFromEventId(event.target.value);
                },

                getSpecificInfoFromEventId(id){
                    var currentEvent = this.events.filter( item => item.id == id )[0];
                    this.maxSize = currentEvent.max_size;
                    this.width = currentEvent.width;
                    this.height = currentEvent.height;
                }
            }
        });


        $(document).ready( function() {

            $( "#datePicker" ).datepicker({
                dateFormat: 'M dd, yy',
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
