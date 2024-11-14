@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if(isset($uri)) <a class="section" href="{{ route("Laralum::$uri::program::list", [$uri => $uriValue]) }}">Program List</a>
        @else <a class="section" href="{{ route('Laralum::program::list') }}">Program / Calendar List</a> @endif
        <i class="right angle icon divider"></i>
        <div class="active section">Program @if(!isset($uri))/ Calendar @endif Edit</div>
    </div>
@endsection
@section('title', 'Program / Calendar Edit')
@section('icon', "edit")
@section('subtitle', $program->name)
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column" id="vue-app">
                <form class="ui form" method="POST" action="{{ route('Laralum::program::update', ['program' => $program->id]) }}" enctype="multipart/form-data">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field required">
                            <label>Name</label>
                            <input type="text"  name="name" placeholder="Name..." value="{{$program->name}}" required>
                        </div>

                        <div class="field required">
                            <label>Slug (User Friendly URL)</label>
                            <input type="text"  name="slug" placeholder="Slug..." value="{{$program->slug}}" required>
                        </div>

                        @if(!isset($uri))
                            <div class="field required">
                                <label>Type</label>
                                <select name="type_id" required v-model="type_id">
                                    <option value=""> Please Select a Type</option>
                                    @foreach($types as $type)
                                        <option @if($program->type_id == $type->id) selected @endif value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        @endif


                        <div class="field">
                            <label>External Link</label>
                            <input type="text"  name="external_link" placeholder="External Link..." value="{{$program->external_link}}">
                        </div>

                        <div class="field">
                            <label>Description</label>
                            <textarea name="description" id="description">{{$program->description}}</textarea>
                        </div>

                        <div class="inline field">
                            <div class="ui slider checkbox">
                                <input type="checkbox" name="enabled" tabindex="0" class="hidden" @if($program->enabled) checked @endif>
                                <label>Enabled</label>
                            </div>
                        </div>

                        @if(!isset($uri))
                        <div class="field" v-if="type_id==2">
                            <label>Upload File</label>
                            <input type="file"  name="file" placeholder="Upload File...">
                            @if($program->path) <a class="ui mini label" href="{{asset("storage/image/program/$program->path")}}">{{$program->path}}</a> @endif
                        </div>
                        @endif

                        <br>
                        <button type="submit" class="ui blue submit button">Update</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection

@section('js')
    @include('laralum.include.vue.vue-axios')
    <script src="https://cdn.tiny.cloud/1/zbgkypr4zql81wjmlop63u6tbcu83synj6nql15gueb6zxfk/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

    <script type="text/javascript">
        tinymce.init({
            selector:'#description',
            plugins: "advlist autolink link image lists charmap print preview autoresize table code",
            toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor"
        });
        /*setTimeout(function(){
            CKEDITOR.replace( 'description' );
        },100);*/

        new Vue({
            el: '#vue-app',
            data: {
                type_id: "{{$program->type_id}}"
            }
        })

    </script>

@endsection
