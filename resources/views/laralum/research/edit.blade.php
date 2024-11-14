@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if(isset($uri)) <a class="section" href="{{ route("Laralum::$uri::research::list", [$uri => $uriValue]) }}">Research List</a>
        @else <a class="section" href="{{ route('Laralum::research::list') }}">Research List</a> @endif
        <i class="right angle icon divider"></i>
        <div class="active section">Research Edit</div>
    </div>
@endsection
@section('title', 'Research Edit')
@section('icon', "edit")
@section('subtitle', $research->name)
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column" id="vue-app">
                <form class="ui form" method="POST" action="{{ route('Laralum::research::update', ['research' => $research->id]) }}" enctype="multipart/form-data">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field required">
                            <label>Name</label>
                            <input type="text"  name="name" placeholder="Name..." value="{{$research->name}}" required>
                        </div>

                        <div class="field required">
                            <label>Type</label>
                            <select name="type_id" required>
                                <option value=""> Please Select a Type</option>
                                @foreach($types as $type)
                                    <option @if($research->type_id == $type->id) selected @endif value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{--<div class="field">
                            <label>Faculty</label>
                            <select v-model="faculty_id">
                                <option value="">Please select a Faculty</option>
                                @foreach($faculties as $faculty)
                                    <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field required">
                            <label>Department</label>
                            <select required name="department_id" v-model="department_id">
                                <option value="">Please Select a department</option>
                                <option v-for="(department, index) in departments.data" v-cloak :value="department.id">@{{department.name}}</option>
                            </select>
                        </div>--}}

                        {{--<div class="field">
                            <label>Department</label>
                            <select name="department_id" required>
                                <option value=""> Please Select a Department</option>
                                @foreach($departments as $department)
                                    <option @if($research->department_id == $department->id) selected @endif value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>--}}

                        <div class="field">
                            <label>Website Link</label>
                            <input type="text"  name="website_link" placeholder="Website Link..." value="{{$research->website_link}}" >
                        </div>

                        <div class="field">
                            <label>Description</label>
                            <textarea class="tinymce" name="description" id="description">{{$research->description}}</textarea>
                        </div>

                        <div class="field">
                            <label>Message From Editor</label>
                            <textarea class="tinymce" name="message_from_editor" id="message_from_editor">{{$research->message_from_editor}}</textarea>
                        </div>

                        <div class="inline field">
                            <div class="ui slider checkbox">
                                <input type="checkbox" name="enabled" tabindex="0" class="hidden" @if($research->enabled)" checked @endif>
                                <label>Enabled</label>
                            </div>
                        </div>

                        <br>
                        <button type="submit" class="ui blue submit button">Update</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection



@section('js')

    @include('laralum.include.vue.vue-axios')
    <script src="https://cdn.tiny.cloud/1/zbgkypr4zql81wjmlop63u6tbcu83synj6nql15gueb6zxfk/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

    <script type="text/javascript">
        tinymce.init({
            selector:'.tinymce',
            plugins: "advlist autolink link image lists charmap print preview autoresize table code",
            toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor"
        });

        /*setTimeout(function(){
            CKEDITOR.replace( 'message_from_editor' );
        },100);

        setTimeout(function(){
            CKEDITOR.replace( 'description' );
        },100);*/

        /*new Vue({
            el : "#vue-app",
            data : {
                departments : {},
                faculty_id : "",
                department_id :"{{$research->department_id}}"
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
            },

            watch: {
                faculty_id: function (val) {
                    this.getDepartmentList();
                }
            }
        });*/

    </script>
@endsection
