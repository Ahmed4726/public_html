@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::event::list') }}">All Notice List</a>
        <i class="right angle icon divider"></i>
        <a class="section" href="{{ route('Laralum::event::upload', ['discussion' => $discussion->id]) }}">All Notice File List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">All Notice File Upload</div>
    </div>
@endsection
@section('title', 'All Notice File Edit')
@section('icon', "upload")
@section('subtitle', $file->name)
@section('content')

    <div class="ui doubling stackable grid container">

        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column" id="vue-app">
                <form class="ui form" method="POST" action="{{ route('Laralum::event::upload::update', ['discussion' => $discussion->id, 'file' => $file->id]) }}" enctype="multipart/form-data">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field required">
                            <label>Name</label>
                            <input type="text"  name="name" placeholder="Name..." required value="{{$file->name}}">
                        </div>

                        <div class="field">
                            <label>Notes</label>
                            <textarea name="note" id="note" placeholder="Note...">{{$file->note}}</textarea>
                        </div>

                        <div class="field">
                            <label>Upload File</label>
                            <input type="file"  name="file" placeholder="Upload File...">
                            @if($file->path) <a class="ui mini label" href="{{route('event::file::view', ['discussion' => $discussion->id, 'file' => $file->id])}}">{{$file->path}}</a> @endif
                        </div>

                        <button type="submit" class="ui blue submit button">Upload</button>
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
            selector:'#note',
            plugins: "advlist autolink link image lists charmap print preview autoresize table code",
            toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor"
        });

        /*setTimeout(function(){
            CKEDITOR.replace( 'note');
        },100);*/

    </script>
@endsection
