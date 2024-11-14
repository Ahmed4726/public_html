@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::faculty::list') }}">Faculty List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Faculty Dean Assign</div>
    </div>
@endsection
@section('title', 'Faculty Dean Assign')
@section('icon', "check")
@section('subtitle', $faculty->name)
@section('content')

    <div class="ui doubling stackable grid container" id="vue-app" v-cloak>
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column">
                <form @submit.prevent="getTeacherList()">
                    <div class="ui action input container">
                        <input type="text" placeholder="Search by name / email..." v-model="search">

                        <select class="ui selection dropdown" v-model="faculty" @change="getDepartmentList()">
                            <option value="">Please Select Faculty</option>
                            @foreach($faculties as $faculty)
                                <option value="{{$faculty->id}}">{{$faculty->name}}</option>
                            @endforeach
                        </select>

                        <select class="ui selection dropdown" v-model="department" @change="getTeacherList()" name="department_id">
                            <option value="">Please Select Department</option>
                            <option v-for="department in departments.data" v-cloak :value="department.id">@{{department.name}}</option>
                        </select>

                        <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column">
                <form class="ui form" method="POST" action="{{ route('Laralum::faculty::assign::save', ['faculty' => $faculty->id]) }}" enctype="multipart/form-data">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="grouped fields">
                            <label>Please Select a Teacher to Assign</label>
                            <div class="field" v-for="teacher in teachers.data" style=" margin : 1em 0px">
                                <div class="ui slider checkbox">
                                    <input type="radio" name="teacher_id" :value="teacher.id" required>
                                    <label v-html="teacher.name"></label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="ui blue submit button">Assign</button>
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
                teachers : {},
                department : '',
                departments : {},
                faculty : "",
                search : ''
            },

            methods : {
                getTeacherList() {
                    this.$http.get("{{ route('Frontend::teacher::search') }}", {
                        params: {
                            take : 20,
                            department_id : this.department,
                            search : this.search
                        }
                    }).
                    then(response => {
                        this.teachers = response.data
                    });
                },

                getDepartmentList() {
                    this.$http.get("{{ route('Frontend::department::search') }}", {
                        params: {
                            faculty_id : this.faculty,
                        }
                    }).
                    then(response => {
                        this.departments = response.data
                    });
                }
            },

            created () {
                this.getDepartmentList();
                this.getTeacherList();
            }
        });
    </script>

@endsection

