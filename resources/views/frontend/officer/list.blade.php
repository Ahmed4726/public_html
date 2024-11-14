@extends('frontend.layout.main')
@section('content')

<div class="container" id="vue-app" v-cloak>
    <div class="content-section officer-page">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        {{--                            <li class="breadcrumb-item"><a href="#">ICT Cell</a></li>--}}
                        <li aria-current="page" class="breadcrumb-item active" v-if="currentType">@{{ currentType }}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">
                <form @submit.prevent="searchMemberList()" class="form-inline">

                    <input type="text" id="keyword" v-model="search" class="form-control col-md-2 m-10"
                        placeholder="Name...">

                    <select id="departmentId" v-model="department_id" class="form-control col-md-3 m-10">
                        <option value="">-- Select Department --</option>
                        <optgroup :label="faculty.name" v-for="faculty in faculties">
                            <option :value="department.id" v-for="department in faculty.departments">
                                @{{department.name}}</option>
                        </optgroup>
                    </select>

                    <select id="type" v-model="center_id" class="form-control col-md-3 m-10">
                        <option value="">-- Select Center/Office --</option>
                        <option :value="center.id" v-for="center in centers">@{{center.name}}</option>
                    </select>

                    <select id="type" v-model="type_id" class="form-control col-md-2 m-10">
                        <option value="">-- Select Officer Type --</option>
                        <option :value="type.id" v-for="type in types">@{{type.name}}</option>
                    </select>

                    <input type="submit" class="btn btn-primary" value="Search">

                </form>
            </div>
        </div>

        <br />
        <div class="row mb-2">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <p class="text-right">Showing <span v-html="members.from"></span> - <span v-html="members.to"></span> of
                    <span v-html="members.total"></span></p>
            </div>
            <div class="col-md-1"></div>
        </div>

        <div class="row" v-if="members.data && members.data.length == 0">
            <div class="col-md-12">
                <div class="alert alert-warning" role="alert">
                    <strong>Warning!</strong> No Data Found.
                </div>
            </div>
        </div>

        <div class="loading" v-if="loading">
        </div>

        <div class="row mb-3">

            <div class="col-md-3" v-for="member in members.data">
                <div class="card mb-3">
                    <img class="card-img-top lazy" :src="member.real_image_path">

                    <div class="card-body">
                        <h4 class="card-title">
                            <a :href="member.external_link ? member.external_link : member.slug && '/officers/'+member.slug"
                                target="_blank">@{{ member.name }}</a>
                        </h4>
                        <p class="card-text">
                            <span v-if="member.department_name">@{{member.designation}},</span> <span
                                v-else>@{{member.designation}}</span>
                            <span v-if="member.department_name">@{{ member.department_name }}</span>
                            <span v-if="member.status == 2 && member.status_info" class="btn btn-warning" role="button"
                                style="font-size: 12px;padding: 5px 10px;">@{{ member.status_info.name }}</span>
                            <span v-if="member.status == 3 && member.status_info" class="btn btn-secondary"
                                role="button"
                                style="font-size: 12px;padding: 5px 10px;">@{{ member.status_info.name }}</span>
                            <span v-if="member.status == 4 && member.status_info" class="btn btn-info" role="button"
                                style="font-size: 12px;padding: 5px 10px;">@{{ member.status_info.name }}</span>
                        </p>

                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Email:</strong> @{{ member.email }}
                        </li>
                        <li class="list-group-item">
                            <strong>Office Phone:</strong> @{{ member.work_phone }}
                        </li>
                        <li class="list-group-item">
                            <strong>Home Phone:</strong> @{{ member.home_phone }}
                        </li>
                    </ul>
                </div>
            </div>

        </div>

        <vue-pagination :pagination="members" @paginate="getMemberList()"></vue-pagination>

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
                centers : {!! $centers->toJson() !!},
                faculties : {!! $faculties->toJson() !!},
                types : {!! $types->toJson() !!},
                slug : "{{$slug ?? ''}}",
                members : {},
                search : '',
                center_id : @if(Request::has('center_id'))"{{Request::get('center_id')}}"@else''@endif,
                type_id : @if(Request::has('type_id'))"{{Request::get('type_id')}}"@else''@endif,
                department_id : @if(Request::has('department_id'))"{{Request::get('department_id')}}"@elseif(Request::has('departmentId'))"{{Request::get('departmentId')}}"@else''@endif,
            },

            methods: {
                getMemberList() {
                    this.loading = true;
                    this.$http.get("{{route('Frontend::officer::search')}}", {
                        params: {
                            status : [1, 2, 3, 4],
                            page: this.members.current_page,
                            search : this.search,
                            center_id : this.center_id,
                            type_id : this.type_id,
                            department_id : this.department_id,
                            relation : 'statusInfo',
                            slug : this.slug
                        }
                    }).
                    then(response => {
                        this.loading = false;
                        this.members = response.data;
                        VueScrollTo.scrollTo('body', 1000);
                    });
                },

                searchMemberList () {
                    this.members.current_page = 1;
                    this.getMemberList();
                }
            },

            created () {
                this.getMemberList();

                @if(Request::has('type'))
                    type = this.types.filter( item => item.name == "{{Request::get('type')}}" );
                if(type.length)
                    this.type_id = type[0].id;
                @endif
            },

            computed: {
                currentType() {
                    let info = this.types.filter( item => item.id == this.type_id);
                    if(info.length > 0)
                        return info[0].name;
                    else
                        return '';
                },
            },
        });
</script>

@endsection