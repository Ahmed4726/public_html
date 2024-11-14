@extends('layouts.admin.panel')
@section('breadcrumb')
     <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::users') }}">User List</a>
        <i class="right angle icon divider"></i>
         <a class="section" href="{{ route('Laralum::users_roles', ['id' => $user->id]) }}">Edit Roles</a>
         <i class="right angle icon divider"></i>
        <div class="active section">User Role Department Assign</div>
    </div>
@endsection
@section('title', 'User Role Department Assign')
@section('icon', "edit")
@section('subtitle', $user->name)
@section('content')

    <div class="ui doubling stackable grid container" id="vue-app">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column" id="vue-app">
                <form class="ui form" method="POST" action="{{ route('Laralum::users_role_department_assign_save', ['user' => $user->id]) }}" enctype="multipart/form-data">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field">
                            <label>Faculty</label>
                            <select v-model="faculty" @change="getDepartmentList()">
                                <option value="">Please Select Faculty to Find Department </option>
                                @foreach($faculties as $faculty)
                                    <option value="{{$faculty->id}}">{{$faculty->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field required">
                            <label>Department</label>
                            <select class="ui search dropdown" required name="department_id">
                                <option value="">Please Select a Department</option>
                                <option v-for="department in departments.data" v-cloak :value="department.id">@{{department.name}}</option>
                            </select>
                        </div>

                        <br>
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
                departments : {},
                faculty : "",
            },

            methods : {
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
            }
        });

    </script>

@endsection

