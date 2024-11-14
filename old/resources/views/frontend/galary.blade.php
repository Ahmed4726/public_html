@extends('frontend.layout.main')
@section('content')

    <div class="container" id="vue-app" v-cloak>

        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Gallery</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h5 class="text-right">Showing <span v-html="images.from"></span> - <span v-html="images.to"></span> of <span v-html="images.total"></span></h5>
            </div>
        </div>

        <div class="loading" v-if="loading">
        </div>

        <div class="gallery-container tz-gallery">
            <div class="row" id="gallery-images">

                <div class="col-sm-6 col-md-3 item-row" v-for="(image, idx) in images.data" @click="index = idx">
                    <div class="thumbnail hovereffect">
                        <img class="lazy" :src="image.real_image_path" :alt="image.title">
                        <div class="overlay">
                            <h2 v-html="image.title"> </h2>
                            <p></p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row mt-2">
                <div class="col-md-12">
                    <h5 class="text-right">Showing <span v-html="images.from"></span> - <span v-html="images.to"></span> of <span v-html="images.total"></span></h5>
                </div>
            </div>

            <div class="row text-center mb-4" v-if="images.to < images.total">
                <div class="col-md-12">
                    <a class="btn btn-outline-secondary" v-on:click="images.current_page ++ && getImageList()">SHOW MORE</a>
                </div>
            </div>

            <Tinybox v-model="index" :images="formattedImages" no-thumbs></Tinybox>

        </div>
    </div>

@endsection

@section('headerStyle')
    <link href="{{asset('css/gallery-clean.css')}}" rel="stylesheet">
@endsection

@section('footerScript')

    @include('laralum.include.vue.vue-axios')
    <script src="https://cdn.jsdelivr.net/npm/vue-tinybox"></script>

    <script type="text/javascript">

        Vue.component('Tinybox', Tinybox);
        new Vue({
            el : "#vue-app",

            data : {
                loading : false,
                images : {!! $images->toJson() !!},
                category_id : 5218,
                counter: 0,
                index: null
            },

            methods: {
                getImageList() {
                    this.loading = true;
                    this.$http.get("{{route('Frontend::gallery::search')}}", {
                        params: {
                            page: this.images.current_page,
                            category_id : this.category_id,
                            status : 1
                        }
                    }).
                    then(response => {
                        this.loading = false;
                        var _this = this;
                        response.data.data.forEach(function(value){
                            _this.images.data.push(value);
                        });
                        this.images.to = response.data.to;

                    });
                }
            },

            computed: {
                formattedImages: function () {
                    let modal = [];
                    if(this.images.data.length){
                        this.images.data.forEach(function(value, key){
                            modal.push({
                                src: value.real_image_path,
                                caption: value.title,
                            });
                        });
                        return modal;
                    }
                    return modal;
                }
            }
        });
    </script>

@endsection