@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::officer::list') }}">Officers / Staff List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Officers / Staff Edit</div>
    </div>
@endsection
@section('title', 'Officers / Staff Edit')
@section('icon', "edit")
@section('subtitle', $officer->name)
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column" id="vue-app">
                <form class="ui form" method="POST" action="{{ route('Laralum::officer::update', ['officer' => $officer->id]) }}" enctype="multipart/form-data">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field required">
                            <label>Status</label>
                            <select name="status" required>
                                <option value="">Please select a status</option>
                                @foreach($statuses as $status)
                                    <option @if($officer->status == $status->id) selected @endif value="{{ $status->id }}">{{ $status->name }}</option>
                                @endforeach
                                <option @if($officer->status == 0) selected @endif value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="field required">
                            <label>Type</label>
                            <select name="type_id" @change="eventChange($event)" required>
                                <option value="">Please select a type</option>
                                @foreach($types as $type)
                                    <option @if($officer->type_id == $type->id) selected @endif value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="inline field">
                            <div class="ui toggle checkbox">
                                <input type="checkbox" tabindex="0" class="hidden" v-model="department">
                                <label>Department</label>
                            </div>
                        </div>

                        <div class="field" v-if="department">
                            <label>Faculty</label>
                            <select v-model="faculty_id">
                                <option value="">Please select a Faculty</option>
                                @foreach($faculties as $faculty)
                                    <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field required" v-if="department">
                            <select required name="department_id" v-model="department_id">
                                <option value="">Please Select a department</option>
                                <option v-for="(department, index) in departments.data" v-cloak :value="department.id">@{{department.name}}</option>
                            </select>
                        </div>

                        {{--<div class="field" v-if="department">
                            --}}{{--<label>Type</label>--}}{{--
                            <select name="department_id" required>
                                <option value="">Please select a Department</option>
                                @foreach($departments as $department)
                                    <option @if($officer->department_id == $department->id) selected @endif value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>--}}

                        <div class="inline field">
                            <div class="ui toggle checkbox">
                                <input type="checkbox" tabindex="0" class="hidden" v-model="center">
                                <label>Centers / Offices</label>
                            </div>
                        </div>

                        <div class="field required" v-if="center">
                            {{--<label>Type</label>--}}
                            <select name="center_id" required>
                                <option value="">Please select a Centers / Offices</option>
                                @foreach($centers as $center)
                                    <option @if($officer->center_id == $center->id) selected @endif value="{{ $center->id }}">{{ $center->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field required">
                            <label>Name</label>
                            <input type="text"  name="name" placeholder="Name..." value="{{$officer->name}}" required>
                        </div>

                        <div class="field">
                            <label>Name in Bangla</label>
                            <input type="text"  name="name_ben" placeholder="Name in Bangla..." value="{{$officer->name_ben}}">
                        </div>

                        <div class="field required">
                            <label>Email</label>
                            <input type="email"  name="email" placeholder="Email..." value="{{$officer->email}}" required>
                        </div>

                        <div class="field">
                            <label>Designation</label>
                            <input type="text"  name="designation" placeholder="Designation..." value="{{$officer->designation}}">
                        </div>

                        <div class="field">
                            <label>Department Name</label>
                            <input type="text"  name="department_name" placeholder="Department Name..." value="{{$officer->department_name}}">
                        </div>

                        <div class="field">
                            <label>Work Phone</label>
                            <input type="text"  name="work_phone" placeholder="Work Phone..." value="{{$officer->work_phone}}">
                        </div>

                        <div class="field">
                            <label>Home Phone</label>
                            <input type="text"  name="home_phone" placeholder="Home Phone..." value="{{$officer->home_phone}}">
                        </div>

                        <div class="field">
                            <label>External Link</label>
                            <input type="text"  name="external_link" placeholder="External Link..." value="{{$officer->external_link}}">
                        </div>

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
                            <label>Profile Picture</label>
                            <div class="input-group">
                                        <span class="input-group-btn">
                                            <span class="btn btn-default btn-file" id="btn-file">
                                                Browse… <input type="file" id="imgInp" name="image">
                                            </span>
                                        </span>
                                <input type="text" class="form-control" name="path" value="{{$officer->image_url}}" readonly style="border-radius: 0">
                            </div>
                            <br/>
                            <img id="img-upload" @if($officer->image_url) src='{{asset("storage/image/officer/$officer->image_url")}}' @else src="{{ asset('storage/image/no-image.png') }}" @endif />
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

    <script type="text/javascript">

        new Vue({
            el : "#vue-app",
            data : {
                center : @if($officer->center_id) true @else false @endif,
                department : @if($officer->department_id) true @else false @endif,
                types : {!! $types !!},
                maxSize : '',
                width : '',
                height : '',
                departments : {},
                faculty_id : "",
                department_id : @if($officer->department_id)"{{$officer->department_id}}" @else "" @endif
            },

            methods : {
                eventChange (event) {
                    this.getSpecificInfoFromTypeId(event.target.value);
                },

                getSpecificInfoFromTypeId(id){
                    var currentType = this.types.filter( item => item.id == id )[0];
                    this.maxSize = currentType.max_size;
                    this.width = currentType.width;
                    this.height = currentType.height;
                },

                getDepartmentList() {
                    this.$http.get("{{ route('Frontend::department::search') }}", {
                        params: {
                            faculty_id : this.faculty_id,
                        }
                    }).
                    then(response => {
                        this.departments = response.data
                    });
                },
            },

            watch: {
                center: function (val) {
                    val ? this.department = false : this.department = true;
                },
                department: function (val) {
                    val ? this.center = false : this.center = true;
                },

                faculty_id: function (val) {
                    this.getDepartmentList();
                }
            },

            mounted (){
                this.getSpecificInfoFromTypeId({{$officer->type_id}});
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

            // $('.ui.checkbox').checkbox();
        });

    </script>
@endsection
