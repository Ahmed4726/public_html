@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <a class="section" href="{{ route('Laralum::file::list') }}">File List</a>
    <i class="right angle icon divider"></i>
    <div class="active section">Uploaded File Edit</div>
</div>
@endsection
@section('title', 'Uploaded File Edit')
@section('icon', "edit")
@section('subtitle', html_entity_decode($file->name))
@section('content')

<div class="ui doubling stackable grid container">
    <div class="row">
        <div class="two wide column"></div>
        <div class="twelve wide column" id="vue-app">
            <form class="ui form" method="POST" action="{{ route('Laralum::file::update', ['file' => $file->id]) }}"
                enctype="multipart/form-data">
                <div class="ui very padded segment">
                    {{ csrf_field() }}

                    <div class="field required">
                        <label>Name</label>
                        <input type="text" name="name" placeholder="Name..." required value="{!!$file->name!!}">
                    </div>

                    <div class="field">
                        <label>Description</label>
                        <textarea name="description" id="description">{{$file->description}}</textarea>
                    </div>

                    <div class="field">
                        <label>Upload File</label>
                        <input type="file" name="file" placeholder="Upload File...">
                        @if($file->path) <a class="ui mini label"
                            href="{{asset("storage/image/global/$file->path")}}">{{$file->path}}</a> @endif
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
<script src="https://cdn.tiny.cloud/1/zbgkypr4zql81wjmlop63u6tbcu83synj6nql15gueb6zxfk/tinymce/5/tinymce.min.js"
    referrerpolicy="origin"></script>

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