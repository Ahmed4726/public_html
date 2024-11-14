@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::gallery::category::list') }}">Gallery Image Type List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Gallery Image Type Edit</div>
    </div>
@endsection
@section('title', 'Gallery Image Type Edit')
@section('icon', "edit")
@section('subtitle', "$category->name")
@section('content')

    <div class="ui doubling stackable grid container" id="vue-app">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column">
            <form class="ui form" method="POST" action="{{ route('Laralum::gallery::category::update', ['id' => $category->id]) }}">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field required">
                            <label>Name</label>
                            <input type="text"  name="name" placeholder="Name" value="{{ $category->name }}" required>
                        </div>

                        <div class="fields">
                            <div class="five wide field">
                                <label>Image Width</label>
                                <div class="ui right labeled input">
                                    <input type="number" name="width" placeholder="Width..." value="{{ $category->width }}">
                                    <div class="ui basic label">
                                        px
                                    </div>
                                </div>
                            </div>
                            <div class="five wide field">
                                <label>Image Height</label>
                                <div class="ui right labeled input">
                                    <input type="number" name="height" placeholder="Height..." value="{{ $category->height }}">
                                    <div class="ui basic label">
                                        px
                                    </div>
                                </div>
                            </div>
                            <div class="six wide field">
                                <label>Max Image Size</label>
                                <div class="ui right labeled input">
                                    <input type="number" name="max_image_size_in_kb" placeholder="Max Size..." value="{{ $category->max_image_size_in_kb }}">
                                    <div class="ui basic label">
                                        kb
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="field">
                            <label>Help Text</label>
                            <input type="text"  name="help_text" placeholder="Help Text" value="{{ $category->help_text }}">
                        </div>

                        <div class="inline field">
                            <div class="ui slider checkbox">
                                <input type="checkbox" name="enabled" tabindex="0" class="hidden" v-model="global"  style="z-index: 2">
                                <label>Global</label>
                            </div>
                        </div>

                        <div class="field" v-if="!global">
                            <label>Faculty</label>
                            <select v-model="faculty_id">
                                <option value="">Please select a Faculty</option>
                                @foreach($faculties as $faculty)
                                    <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field" v-if="!global">
                            <label>Department</label>
                            <select name="department_id" v-model="department_id">
                                <option value="">Please Select a department</option>
                                <option v-for="(department, index) in departments.data" v-cloak :value="department.id">@{{department.name}}</option>
                            </select>
                        </div>

                        {{--<div class="field" v-if="!global">
                            <label>Department</label>
                            <select name="department_id">
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" @if($category->department_id == $department->id) selected="selected" @endif>{{ $department->short_name }}</option>
                                @endforeach
                            </select>
                        </div>--}}

                        <div class="field">
                            <label>Description</label>
                            <textarea name="description">{{ $category->description }}</textarea>
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

    <script type="text/javascript">

        new Vue({
            el : "#vue-app",
            data : {
                @if($category->department_id == null)
                global : true,
                @else
                global : false,
                @endif
                departments : {},
                faculty_id : "",
                department_id : @if($category->department_id)"{{$category->department_id}}" @else "" @endif
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
        });

    </script>
@endsection
