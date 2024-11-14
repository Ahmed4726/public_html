@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::center::list') }}">Centers / Offices List</a>
        <i class="right angle icon divider"></i>
        <a class="section" href="{{route('Laralum::center::upload::list', ['center'=>$center->id])}}">Centers / Offices File List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Centers / Offices File Upload</div>
    </div>
@endsection
@section('title', 'Centers / Offices File Upload')
@section('icon', "plus")
@section('subtitle', $center->name)
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column" id="vue-app">
                <form class="ui form" method="POST" action="{{ route('Laralum::center::upload::store', ['center' => $center->id]) }}" enctype="multipart/form-data">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field required">
                            <label>Name</label>
                            <input type="text"  name="name" placeholder="Name..." required>
                        </div>

                        <div class="field">
                            <label>Note</label>
                            <textarea name="note" id="note"></textarea>
                        </div>

                        <div class="inline field">
                            <div class="ui slider checkbox">
                                <input type="checkbox" name="listing_enabled" tabindex="0" class="hidden" checked>
                                <label>List In Home Page</label>
                            </div>
                        </div>

                        <div class="field ">
                            <label>Externa Link</label>
                            <input type="text"  name="external_link" placeholder="Externa Link...">
                        </div>

                        <div class="field">
                            <label>Upload File</label>
                            <input type="file"  name="file" placeholder="Upload Journal File..." required>
                        </div>

                        <br>
                        <button type="submit" class="ui blue submit button">Save</button>
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
            CKEDITOR.replace( 'note' );
        },100);*/

    </script>
@endsection
