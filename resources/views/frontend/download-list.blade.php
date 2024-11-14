@extends('frontend.layout.main')
@section('content')

    <div class="container" id="vue-app" v-cloak>
        <div class="content-section">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Download</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row md-3">
                <div class="col-md-12 d-flex justify-content-center">
                    <form @submit.prevent="searchDownloadList()" class="form-inline">

                        <div class="form-group m-2">
                            <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Keyword" v-model="search" data-selected-keyword="">
                        </div>

                        <div class="form-group m-2">
                            <select id="centerId" v-model="center_id" class="form-control">
                                <option value="">-- Select Center/Office --</option>
                                @foreach($centers as $center)
                                    <option value="{{$center->id}}" >{{$center->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group m-2">
                            <select id="departmentId" v-model="department_id" class="form-control">
                                <option value="">-- Select Department --</option>
                                @foreach($faculties as $faculty)
                                    <optgroup label="{{$faculty->name}}">
                                        @foreach($faculty->departments as $department)
                                        <option value="{{$department->id}}">{{$department->name}}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        <input type="submit" class="btn btn-primary m-2" value="Search">
                    </form>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <p class="text-right">Showing <span v-html="downloads.from"></span> - <span v-html="downloads.to"></span> of <span v-html="downloads.total"></span></p>
                </div>
                <div class="col-md-1"></div>
            </div>

            <div class="row" v-if="downloads.data && downloads.data.length == 0">
                <div class="col-md-12">
                    <div class="alert alert-warning" role="alert">
                        <strong>Warning!</strong> No Data Found.
                    </div>
                </div>
            </div>

            <div class="loading" v-if="loading">
            </div>

            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="table-responsive">
                        <table class="table table-striped ">
                            <thead>
                            <tr>
                                <th>Sl. No.</th>
                                <th>Name</th>
                                <th class="text-right">Download</th>
                            </tr>
                            </thead>

                            <tbody id="items">

                            <tr class="item-row" v-for="(download, index) in downloads.data">
                                <td>@{{ index+1 }}</td>
                                <td v-html="download.name"></td>
                                <td class="text-right"><a class="btn btn-outline-primary" target="_blank"
                                                          :href="[center_id ? `{{url('center')}}/${download.center_id}/file/${download.id}` : `{{url('department')}}/${download.department_id}/file/${download.id}`]"
                                                          >Click Here</a> </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>

            <div class="row mb-2">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <p class="text-right">Showing <span v-html="downloads.from"></span> - <span v-html="downloads.to"></span> of <span v-html="downloads.total"></span></p>
                </div>
                <div class="col-md-1"></div>
            </div>

            {{--<div class="row text-center mb-4">
                <div class="col-md-12">
                    <a class="btn btn-outline-secondary" id="show-more-items" data-currentPage="1" data-pageSize="5">SHOW MORE</a>
                </div>
            </div>--}}

            <vue-pagination :pagination="downloads" @paginate="getDownloadList()"></vue-pagination>

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
                downloads : {},
                search : '',
                center_id : @if(Request::has('center_id'))"{{Request::get('center_id')}}"@else''@endif,
                department_id : @if(Request::has('department_id'))"{{Request::get('department_id')}}"@else''@endif,
            },

            methods: {
                getDownloadList() {
                    this.loading = true;
                    this.$http.get("{{route('Frontend::download::center::department::file::search')}}", {
                        params: {
                            page: this.downloads.current_page,
                            search : this.search,
                            center_id : this.center_id,
                            department_id : this.department_id
                        }
                    }).
                    then(response => {
                        this.loading = false;
                        this.downloads = response.data;
                        VueScrollTo.scrollTo('body', 1000);
                    });
                },

                searchDownloadList () {
                    this.downloads.current_page = 1;
                    this.getDownloadList();
                }
            },

            created () {
                this.getDownloadList();
            }
        });
    </script>

@endsection