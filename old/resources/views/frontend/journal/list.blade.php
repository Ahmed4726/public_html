@extends('frontend.layout.main')
@section('content')

<div class="container" id="vue-app" v-cloak>
    <div class="content-section">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Journal</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">
                <form @submit.prevent="searchJournalContentList()">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <input type="text" id="keyword" v-model="search" class="form-control"
                                placeholder="Name/Title/Author/Co-Author..." value="" data-selected-keyword="">
                        </div>
                        <div class="form-group col-md-3">
                            <select id="facultyId" v-model="faculty_id" @change="getDepartmentList()"
                                class="form-control" data-selected-faculty="-1">
                                <option value="">-- Select Faculty --</option>
                                @foreach($faculties as $faculty)
                                <option value="{{$faculty->id}}">{{$faculty->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <select id="departmentId" v-model="department_id" class="form-control"
                                data-selected-department="-1">
                                <option value="">-- Select Department --</option>

                                <optgroup v-for="faculty in faculties" v-cloak :label="faculty.name">
                                    <option v-for="department in faculty.departments" :value="department.id">
                                        @{{department.name}}
                                    </option>

                            </select>
                        </div>

                        <div class="form-group col-md-2" v-if="Object.keys(allVolume).length">
                            <select v-model="volume" class="form-control">
                                <option value="">-- Select Volume --</option>
                                <option v-for="volume in allVolume" :value="volume">@{{volume}}</option>
                            </select>
                        </div>

                        <div class="form-group col-md-1">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-12">
                <h5 class="text-right">Showing <span v-html="journalContent.from"></span> - <span
                        v-html="journalContent.to"></span> of <span v-html="journalContent.total"></span></h5>
            </div>
        </div>

        <div class="row" v-if="journalContent.data && journalContent.data.length == 0">
            <div class="col-md-12">
                <div class="alert alert-warning" role="alert">
                    <strong>Warning!</strong> No Data Found.
                </div>
            </div>
        </div>

        <div class="loading" v-if="loading">
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Authors</th>
                                <th>Co. Author</th>
                                <th>Name of Journal</th>
                                <th>Volume</th>
                            </tr>
                        </thead>

                        <tbody id="items">

                            <tr class="item-row" v-for="content in journalContent.data">
                                <td>
                                    @{{ content.title }}
                                    <br />
                                    <a class="btn btn-outline-primary"
                                        :href="`{{url('journal')}}/${content.id}/file`">Download</a>
                                </td>
                                <td>@{{ content.author }}</td>
                                <td>@{{ content.co_author }}</td>
                                <td>@{{ content.journal.title }}</td>
                                <td>@{{ content.volume }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-12">
                <h5 class="text-right">Showing <span v-html="journalContent.from"></span> - <span
                        v-html="journalContent.to"></span> of <span v-html="journalContent.total"></span></h5>
            </div>
        </div>

        <vue-pagination :pagination="journalContent" @paginate="getJournalContentList()"></vue-pagination>

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
                journalContent : {},
                search : '',
                department_id : @if(Request::has('department_id'))"{{Request::get('department_id')}}"@else''@endif,
                faculty_id : @if(Request::has('faculty_id'))"{{Request::get('faculty_id')}}"@else''@endif,
                faculties : {},
                volume : '',
                allVolume : {}
            },

            methods: {
                getJournalContentList() {
                    this.loading = true;
                    this.$http.get("{{route('Frontend::journal::search')}}", {
                        params: {
                            relation : 'journal',
                            page: this.journalContent.current_page,
                            search : this.search,
                            department_id : this.department_id,
                            faculty_id : this.faculty_id,
                            volume : this.volume,
                        }
                    }).
                    then(response => {
                        this.loading = false;
                        this.journalContent = response.data.content;
                        this.allVolume = response.data.volume;
                        if(response.data.content.data && response.data.content.data.length == 0){
                            this.volume = '';
                        }
                        VueScrollTo.scrollTo('body', 1000);
                    });
                },

                searchJournalContentList () {
                    this.journalContent.current_page = 1;
                    this.getJournalContentList();
                },

                getDepartmentList() {
                    this.$http.get("{{ route('Frontend::faculty::search') }}", {
                        params: {
                        faculty_id : this.faculty_id,
                    }
                    }).
                    then(response => {
                        this.faculties = response.data
                    });
                },
            },

            computed : {
                /* volumeList(){
                    if(this.journalContent.data && this.journalContent.data.length > 0){
                        var volumes = [];
                        this.journalContent.data.map(function(value, key) {
                            volumes.push(value['volume']);
                        });
                        return volumes;
                    }else{
                        return [];
                    }
                } */
            },

            created () {
                this.getJournalContentList();
                this.getDepartmentList();
            }
        });
</script>

@endsection