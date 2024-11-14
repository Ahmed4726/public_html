@extends('frontend.layout.main')
@section('content')

    <div class="container" id="vue-app" v-cloak>
        <div class="content-section">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">NOC & GO Jahangirnagar University</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 d-flex justify-content-center">
                    <form @submit.prevent="searchCertificateList()" class="form-inline">
                        <div class="form-group mb-2 mr-sm-2 mb-sm-0">
                            <input type="text" id="formDate" readonly v-model="fromDate" class="form-control" placeholder="From Date ...">
                        </div>
                        <div class="form-group mb-2 mr-sm-2 mb-sm-0">
                            <input type="text" id="toDate" readonly v-model="toDate" class="form-control" placeholder="To Date ...">
                        </div>
                        <div class="form-group mb-2 mr-sm-2 mb-sm-0">
                            <input type="text" id="keyword" v-model="search" class="form-control" placeholder="Name/ Serial/ Designation ..." value="" data-selected-keyword="">
                        </div>
                        <input type="submit" class="btn btn-primary" value="Search">
                    </form>
                </div>
            </div>


            <div class="row mb-2">
                <div class="col-md-12">
                    <h5 class="text-right">Showing <span v-html="certificates.from"></span> - <span v-html="certificates.to"></span> of <span v-html="certificates.total"></span></h5>
                </div>
            </div>

            <div class="row" v-if="certificates.data && certificates.data.length == 0">
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
                    <div id="items">

                        <div class="item-row" v-for="certificate in certificates.data">
                            <div class="card">
                                <div class="card-body" style="line-height: 1rem;">
                                    <h4 class="card-title">@{{ certificate.serial }}. @{{ certificate.type_info.name }} of @{{ certificate.name }}, @{{ certificate.designation }}</h4>
                                    <p class="card-text">@{{ certificate.address }}</p>
                                    <p class="card-text">Date: @{{ certificate.date }}</p>
                                    <a class="btn btn-primary" :href="[certificate.external_link ? `${certificate.external_link}` : `{{url('certificate')}}/${certificate.id}/file`]">
                                        View NOC</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-12">
                    <h5 class="text-right">Showing <span v-html="certificates.from"></span> - <span v-html="certificates.to"></span> of <span v-html="certificates.total"></span></h5>
                </div>
            </div>

            {{--<div class="row text-center mb-4">
                <div class="col-md-12">
                    <a class="btn btn-outline-secondary" id="show-more-items" data-currentPage="1" data-pageSize="5">SHOW MORE</a>
                </div>
            </div>--}}

            <vue-pagination :pagination="certificates" @paginate="getCertificateList()"></vue-pagination>

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
                certificates : {},
                search : '',
                fromDate : '',
                toDate : '',
            },

            methods: {
                getCertificateList() {
                    console.log('work');
                    this.loading = true;
                    this.$http.get("{{route('Frontend::certificate::search')}}", {
                        params: {
                            relation : 'typeInfo',
                            page: this.certificates.current_page,
                            search : this.search,
                            fromDate : this.fromDate,
                            toDate : this.toDate,
                        }
                    }).
                    then(response => {
                        this.loading = false;
                        this.certificates = response.data;
                        VueScrollTo.scrollTo('body', 1000);
                    });
                },

                searchCertificateList () {
                    this.certificates.current_page = 1;
                    this.getCertificateList();
                }
            },

            created () {
                this.getCertificateList();
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
            },
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