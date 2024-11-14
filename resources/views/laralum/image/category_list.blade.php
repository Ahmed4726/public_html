@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Gallery Image Type List</div>
    </div>
@endsection
@section('title', 'Gallery Image Type List')
@section('icon', "list")
@section('subtitle', 'Gallery Image Type List')
@section('content')

    <div class="ui doubling stackable grid container" id="vue-app">
        <div class="row">
            <div class="sixteen wide column">
                <table class="ui selectable striped celled small table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Max Size</th>
                        <th>Department</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->max_image_size_in_kb }} KB</td>
                                <td><label class="ui basic label"> {{ ($category->department_id) ? $category->department->name : 'Global' }}</label></td>
                                <td>
                                    <div class="ui blue top icon left pointing dropdown button">
                                        Edit
                                        <div class="menu">
                                            <div class="header">{{ trans('laralum.editing_options') }}</div>
                                            <a href="{{ route('Laralum::gallery::category::edit', ['id' => $category->id]) }}" class="item">
                                                <i class="edit icon"></i>
                                                Basic Edit
                                            </a>

                                            <a href='{{ url("admin/gallery/image?category_id=$category->id") }}' class="item">
                                                <i class="list icon"></i>
                                                All Image
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="sixteen wide column">
                <form class="ui form" method="POST" action="{{route('Laralum::gallery::category::store')}}">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field required">
                            <label>Name</label>
                            <input type="text"  name="name" placeholder="Name" required>
                        </div>

                        <div class="fields">
                            <div class="five wide field">
                                <label>Image Width</label>
                                <div class="ui right labeled input">
                                    <input type="number" name="width" placeholder="Width...">
                                    <div class="ui basic label">
                                        px
                                    </div>
                                </div>
                            </div>
                            <div class="five wide field">
                                <label>Image Height</label>
                                <div class="ui right labeled input">
                                    <input type="number" name="height" placeholder="Height...">
                                    <div class="ui basic label">
                                        px
                                    </div>
                                </div>
                            </div>
                            <div class="six wide field">
                                <label>Max Image Size</label>
                                <div class="ui right labeled input">
                                    <input type="number" name="max_image_size_in_kb" placeholder="Max Size...">
                                    <div class="ui basic label">
                                        kb
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="field">
                            <label>Help Text</label>
                            <input type="text"  name="help_text" placeholder="Help Text">
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
                            <select name="department_id">
                                <option value="">Please Select a department</option>
                                <option v-for="(department, index) in departments.data" v-cloak :value="department.id">@{{department.name}}</option>
                            </select>
                        </div>

                        {{--<div class="field" v-if="!global">
                            <label>Department</label>
                            <select name="department_id">
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->short_name }}</option>
                                @endforeach
                            </select>
                        </div>--}}

                        <div class="field">
                            <label>Description</label>
                            <textarea name="description"></textarea>
                        </div>

                        <br>
                        <button type="submit" class="ui blue submit button">Save</button>
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
                departments : {},
                global : true,
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
