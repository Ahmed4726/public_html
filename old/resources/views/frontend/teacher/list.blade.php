@extends('frontend.layout.main')
@section('content')

<div class="container">
    <div class="content-section people-page" id="vue-app" v-cloak>
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">People</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row pb-3">
            <div class="col-md-12 d-flex justify-content-center">

                <form @submit.prevent="searchTeacherList()" class="form-inline">
                    <div class="form-group mb-2 mr-sm-2 mb-sm-0">
                        <input type="text" v-model="search" class="form-control" placeholder="Teacher Name / Email">
                    </div>

                    <div class="form-group mb-2 mr-sm-2 mb-sm-0">
                        <select id="departmentId" v-model="department_id" class="form-control">
                            <option value="">-- Department --</option>
                            @foreach($faculties as $faculty)
                            <optgroup label="{{$faculty->name}}">
                                @foreach($faculty->departments as $department)
                                <option value="{{$department->id}}">{{$department->name}}</option>
                                @endforeach
                                @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-2 mr-sm-2 mb-sm-0">
                        <select v-model="designation_id" class="form-control">
                            <option value="">--- Designation ---</option>
                            @foreach ($designations as $designation)
                            <option value="{{$designation->id}}">{{$designation->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-2 mr-sm-2 mb-sm-0">
                        <select v-model="status_id" class="form-control">
                            <option value="">--- Status ---</option>
                            @foreach ($statuses->sortBy('name') as $status)
                            <option value="{{$status->id}}">{{$status->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <input type="submit" class="btn btn-primary" value="Search">

                </form>

            </div>
        </div>

        <div class="row mt-2" v-if="teachers.data && teachers.data.length == 0">
            <div class="col-md-12">
                <div class="alert alert-warning" role="alert">
                    <strong>Warning!</strong> No Data Found.
                </div>
            </div>
        </div>

        <div class="loading" v-if="loading">
        </div>

        <div id="items" class="row ">

            <div class="col-md-3 item-row mb-3" style="height: 400px;" v-for="teacher in teachers.data">

                <div class="card">
                    <img class="card-img-top img-fluid rounded-circle mx-auto d-block lazy"
                        :src="teacher.real_image_path" :alt="teacher.name">

                    <div class="card-body">
                        <h6>
                            <a
                                :href="[teacher.slug ? `{{url('teachers')}}/${teacher.slug}` : `{{url('teachers')}}/${userNameFromEmail(teacher.email)}`]">@{{teacher.name}}</a>
                            <small v-if="teacher.designation_info">@{{teacher.designation_info.name}}
                                <span v-if="teacher.status == 2 && teacher.status_info" class="btn btn-warning"
                                    role="button"
                                    style="font-size: 12px;padding: 5px 10px;">@{{ teacher.status_info.name }}</span>
                                <span v-if="teacher.status == 3 && teacher.status_info" class="btn btn-secondary"
                                    role="button"
                                    style="font-size: 12px;padding: 5px 10px;">@{{ teacher.status_info.name }}</span>
                                <span v-if="teacher.status == 4 && teacher.status_info" class="btn btn-info"
                                    role="button"
                                    style="font-size: 12px;padding: 5px 10px;">@{{ teacher.status_info.name }}</span>
                            </small>
                        </h6>
                        <strong>Research interest</strong>
                        <p v-if="teacher.research_interest"
                            :inner-html.prop='teacher.research_interest  | str_limit(60)'></p>
                        <p v-else>N/A</p>
                    </div>

                </div>

            </div>

        </div>

        <div class="row mt-2">
            <div class="col-md-12">
                <h5 class="text-right">Showing <span v-html="teachers.from"></span> - <span v-html="teachers.to"></span>
                    of <span v-html="teachers.total"></span></h5>
            </div>
        </div>
        <vue-pagination :pagination="teachers" @paginate="getTeacherList()"></vue-pagination>

    </div>
</div>

@endsection

@section('footerScript')

@include('laralum.include.vue.vue-axios')

<script type="text/javascript">
    new Vue({
            el : "#vue-app",

            data : {
                loading : false,
                teachers : {},
                search : '',
                status_id: @if(Request::has('status_id'))"{{Request::get('status_id')}}"@else''@endif,
                designation_id: @if(Request::has('designation_id'))"{{Request::get('designation_id')}}"@else''@endif,
                department_id : @if(Request::has('department_id'))"{{Request::get('department_id')}}"@elseif(Request::has('departmentId'))"{{Request::get('departmentId')}}"@else''@endif
            },

            methods: {
                getTeacherList() {
                    this.loading = true;
                    this.$http.get("{{route('Frontend::teacher::search')}}", {
                        params: {
                            status : this.status_id || [1, 2, 4],
                            designation_id : this.designation_id,
                            relation : ['designationInfo', 'statusInfo'],
                            page: this.teachers.current_page,
                            search : this.search,
                            department_id : this.department_id
                        }
                    }).
                    then(response => {
                        this.loading = false;
                        this.teachers = response.data;
                        VueScrollTo.scrollTo('body', 1000);
                    });
                },

                searchTeacherList () {
                    this.teachers.current_page = 1;
                    this.getTeacherList();
                },

                userNameFromEmail (email) {
                    return email.split("@")[0];
                }
            },

            created () {
                this.getTeacherList();
            }
        });
</script>

{{--<script type="text/javascript">

        var CURRENT_PAGE = 2;
        var KEY_WORD = "";
        var DEPARTMENT_ID = -1;

        $(document).ready(function () {
            if (724 <= 12){
                $("#show-more-items").hide();
            }
        });

        $("#show-more-items").click(function () {
            $("#show-more-items").hide("slow");
            KEY_WORD = $("#keyword").attr("data-selected-keyword");
            DEPARTMENT_ID = $("#departmentId").attr("data-selected-department");
            loadMoreItems();
        });

        function loadMoreItems() {
            var request = $.ajax({
                url: "/teachers/more?keyword=" + KEY_WORD +"&departmentId=" + DEPARTMENT_ID + "&currentPage=" + CURRENT_PAGE,
                type: 'GET',
                success: function (response) {
                    if (response) {
                        $("#items").append(response);
                        CURRENT_PAGE = CURRENT_PAGE + 1;
                        $(".item-row").show("slow");
                        $(".loadedItemCount").text($(".item-row").length);
                        if(response && response.trim() && parseInt($("#loadedItemCount").text()) < 724) {
                            $("#show-more-items").show("slow");
                        }

                        //lazy loadaing enabled
                        $('.lazy').Lazy();
                    } else {
                        $("#show-more-items").hide();
                    }
                },
                error: function (response) {
                    $("#show-more-items").show();
                },
                complete: function () {
                }
            }).always(function () {
            });
        }
    </script>--}}

@endsection