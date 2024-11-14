@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::custom::page::list') }}">Custom Page List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Custom Page Edit</div>
    </div>
@endsection
@section('title', 'Custom Page Edit')
@section('icon', "edit")
@section('subtitle', $page->title)
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column" id="vue-app">
                <form class="ui form" method="POST" action="{{ route('Laralum::custom::page::update', ['page' => $page->id]) }}" enctype="multipart/form-data">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field required">
                            <label>Title</label>
                            <input type="text"  name="title" placeholder="Title..." required value="{{$page->title}}">
                        </div>

                        <div class="field required">
                            <label>Slug (User Friendly URL)</label>
                            <input type="text"  name="slug" placeholder="Slug..." required value="{{$page->slug}}">
                        </div>

                        <div class="field">
                            <label>Content</label>
                            <textarea name="description" id="description">{{$page->content}}</textarea>
                        </div>

                        <div class="inline field">
                            <div class="ui slider checkbox">
                                <input type="checkbox" name="enabled" tabindex="0" class="hidden" @if($page->enabled) checked @endif>
                                <label>Enabled</label>
                            </div>
                        </div>

                        <br>
                        <button type="submit" class="ui blue submit button">Update</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection

@section('js')
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

    </script>
@endsection
