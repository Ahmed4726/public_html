@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if(isset($uri)) <a class="section" href="{{ route("Laralum::$uri::program::list", [$uri => $uriValue]) }}">Program List</a>
        @else <a class="section" href="{{ route('Laralum::program::list') }}">Program / Calendar List</a> @endif
        <i class="right angle icon divider"></i>
        <div class="active section">Program @if(!isset($uri))/ Calendar @endif Create</div>
    </div>
@endsection
@section('title', 'Program / Calendar Create')
@section('icon', "plus")
@if(isset($uri))
    @section('subtitle', 'Program Create')
@else
    @section('subtitle', 'Program / Calendar Create')
@endif
@section('content')

    <div class="ui doubling stackable grid container" id="vue-app">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column" id="vue-app">
                <form class="ui form" method="POST" action="{{ route('Laralum::program::store') }}" enctype="multipart/form-data">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field required">
                            <label>Name</label>
                            <input type="text"  name="name" placeholder="Name..." required>
                        </div>

                        <div class="field required">
                            <label>Slug (User Friendly URL)</label>
                            <input type="text"  name="slug" placeholder="Slug..." required>
                        </div>

                        @if(isset($uri))

                            @if($uri == 'department')
                                <input type="hidden" name="department_id" value="{{$uriValue}}">
                            @elseif($uri == 'center')
                                <input type="hidden" name="center_id" value="{{$uriValue}}">
                            @elseif($uri == 'hall')
                                <input type="hidden" name="hall_id" value="{{$uriValue}}">
                            @endif

                        @else
                            <div class="field required">
                                <label>Type</label>
                                <select name="type_id" required v-model="type_id">
                                    <option value=""> Please Select a Type</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        @endif

                        <div class="field">
                            <label>External Link</label>
                            <input type="text"  name="external_link" placeholder="External Link...">
                        </div>

                        <div class="field">
                            <label>Description</label>
                            <textarea name="description" id="description"></textarea>
                        </div>

                        <div class="inline field">
                            <div class="ui slider checkbox">
                                <input type="checkbox" name="enabled" tabindex="0" class="hidden" checked>
                                <label>Enabled</label>
                            </div>
                        </div>

                        @if(!isset($uri))
                        <div class="field" v-if="type_id==2">
                            <label>Upload File</label>
                            <input type="file"  name="file" placeholder="Upload File...">
                        </div>
                        @endif

                        <br>
                        <button type="submit" class="ui blue submit button">Save</button>
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
                type_id: ''
            }
        })

    </script>
@endsection
