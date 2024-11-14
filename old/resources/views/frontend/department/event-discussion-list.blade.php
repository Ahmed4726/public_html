@extends('frontend.layout.main')
@section('content')

    <div class="container" id="vue-app" v-cloak>
        <div class="content-section">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@{{ currentEvent }}</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 d-flex justify-content-center">
                    <form @submit.prevent="searchDiscussionList()" class="form-inline">
                        <div class="form-group mb-2 mr-sm-2 mb-sm-0">
                            <input type="text" id="formDate" readonly v-model="fromDate" class="form-control" placeholder="From Date...">
                        </div>
                        <div class="form-group mb-2 mr-sm-2 mb-sm-0">
                            <input type="text" id="toDate" readonly v-model="toDate" class="form-control" placeholder="To Date...">
                        </div>

                        <div class="form-group mb-2 mr-sm-2 mb-sm-0">
                            <input type="text" id="keyword" v-model="search" class="form-control" placeholder="Title...">
                        </div>
                        <div class="form-group mb-2 mr-sm-2 mb-sm-0">
                            <select id="type" v-model="event_id" class="form-control">
                                <option value="featured">Featured News</option>
                                <option :value="event.id" v-for="event in events">@{{event.name}}</option>
                            </select>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Search">
                    </form>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-12">
                    <h5 class="text-right">Showing <span v-html="discussions.from"></span> - <span v-html="discussions.to"></span> of <span v-html="discussions.total"></span></h5>
                </div>
            </div>

            <div class="discussion-page">

                <div class="row" v-if="discussions.data && discussions.data.length == 0">
                    <div class="col-md-12">
                        <div class="alert alert-warning" role="alert">
                            <strong>Warning!</strong> No Data Found.
                        </div>
                    </div>
                </div>

                <div class="loading" v-if="loading">
                </div>

                <div id="items" class="row">

                    <div class="item-row col-md-3" v-for="discussion in discussions.data">
                        <a :href="[discussion.external_link ? `${discussion.external_link}` : `{{url('discussion')}}/${discussion.id}`]" style="">
                            <div class="card">
                                <img class="card-img-top lazy" :src="discussion.real_image_path"  alt="Card image cap">

                                <div class="card-body">

                                        <h2 class="card-title" >
                                            <i v-if="discussion.highlight" class="fa fa-asterisk no-padding no-margin animated infinite flash submenu"></i>
                                            <span v-html="discussion.title"></span>
                                        </h2>

                                    <p style="margin-top: 10px; text-decoration: none; display: inline-block;">
                                        <small v-if="discussion.show_publish_date">
                                            <i class="fas fa-clock"></i> @{{ discussion.publish_date }}
                                        </small>
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>

                <div class="row mt-2">
                    <div class="col-md-12">
                        <h5 class="text-right">Showing <span v-html="discussions.from"></span> - <span v-html="discussions.to"></span> of <span v-html="discussions.total"></span></h5>
                    </div>
                </div>

                {{--<div class="row text-center mb-4">
                    <div class="col-md-12">
                        <a class="btn btn-outline-secondary" id="show-more-items" data-currentPage="1" data-pageSize="5">SHOW MORE</a>
                    </div>
                </div>--}}

                <vue-pagination :pagination="discussions" @paginate="getDiscussionList()"></vue-pagination>

            </div>
        </div>
    </div>

@endsection

@section('footerScript')

    @include('laralum.include.vue.vue-axios')

    <script type="text/javascript">

        function updateQueryStringParameter(uri, key, value) {
            var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
            var separator = uri.indexOf('?') !== -1 ? "&" : "?";
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + "=" + value + '$2');
            }
            else {
                return uri + separator + key + "=" + value;
            }
        }

        new Vue({
            el : "#vue-app",
            data : {
                loading : false,
                events : {!! $events->toJson() !!},
                discussions : {},
                search : '',
                event_id : @if(Request::has('event_id'))"{{Request::get('event_id')}}"@else'featured'@endif,
                fromDate : '',
                toDate : '',
                department_id : @if(Request::has('department_id'))"{{Request::get('department_id')}}"@else''@endif,
            },

            methods: {
                getDiscussionList() {
                    this.loading = true;
                    this.$http.get("{{route('Frontend::event::search')}}", {
                        params: {
                            page: this.discussions.current_page,
                            search : this.search,
                            event_id : this.event_id,
                            fromDate : this.fromDate,
                            toDate : this.toDate,
                            department_id : this.department_id
                        }
                    }).
                    then(response => {
                        this.loading = false;
                        this.discussions = response.data;
                        VueScrollTo.scrollTo('body', 1000);
                    });
                },

                searchDiscussionList () {
                    this.discussions.current_page = 1;
                    this.getDiscussionList();
                }
            },

            created () {
                this.getDiscussionList();
                this.events.push({ 'id' : 'featured', 'name' : 'Featured News'});

                @if(Request::has('type'))
                    type = this.events.filter( item => item.name == "{{Request::get('type')}}" );
                if(type.length)
                    this.event_id = type[0].id;
                @endif
            },

            computed: {
                currentEvent() {
                    return this.events.filter( item => item.id == this.event_id )[0].name;
                }
            },

            mounted: function() {
                var self = this;

                $( "#formDate" ).datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true,
                    onClose: function( selectedDate ) {
                        $( "#toDate" ).datepicker( "option", "minDate", selectedDate );
                    },
                    onSelect:function(selectedDate, datePicker) {
                        self.fromDate = selectedDate;
                    }
                });

                $( "#toDate" ).datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true,
                    onClose: function( selectedDate ) {
                        $( "#formDate" ).datepicker( "option", "maxDate", selectedDate );
                    },
                    onSelect:function(selectedDate, datePicker) {
                        self.toDate = selectedDate;
                    }
                });
            }
        });
    </script>

@endsection

@section('headerStyle')

    <style type="text/css">
        .topic .reporter {
            padding-bottom: 5px;
            font-size: 12px;
            text-align: left;
        }

        .topic .body {
            text-align: justify;
        }

        .topic .body img {
            width: 40%;
            float: left;
            padding-right: 5px;
            padding-bottom: 5px;
        }
    </style>

@endsection