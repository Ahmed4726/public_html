@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <a class="section" href="{{ route('Laralum::center::list') }}">Centers / Offices List</a>
    <i class="right angle icon divider"></i>
    <a class="section" href="{{route('Laralum::center::upload::list', ['center'=>$center->id])}}">Centers / Offices File
        List</a>
    <i class="right angle icon divider"></i>
    <div class="active section">Centers / Offices File Edit</div>
</div>
@endsection
@section('title', 'Centers / Offices File Edit')
@section('icon', "edit")
@section('subtitle', $upload->name)
@section('content')

<div class="ui doubling stackable grid container">
    <div class="row">
        <div class="two wide column"></div>
        <div class="twelve wide column" id="vue-app">
            <form class="ui form" method="POST"
                action="{{ route('Laralum::center::upload::update', ['center' => $center->id, 'upload' => $upload->id]) }}"
                enctype="multipart/form-data">
                <div class="ui very padded segment">
                    {{ csrf_field() }}

                    <div class="field required">
                        <label>Name</label>
                        <input type="text" name="name" placeholder="Name..." required value="{{$upload->name}}">
                    </div>

                    <div class="field">
                        <label>Note</label>
                        <textarea name="note" id="note">{{$upload->note}}</textarea>
                    </div>

                    <div class="inline field">
                        <div class="ui slider checkbox">
                            <input type="checkbox" name="listing_enabled" tabindex="0" class="hidden"
                                @if($upload->listing_enabled) checked @endif>
                            <label>List In Home Page</label>
                        </div>
                    </div>

                    <div class="field">
                        <label>Externa Link</label>
                        <input type="text" name="external_link" placeholder="Externa Link..."
                            value="{{$upload->external_link}}">
                    </div>


                    <div class="field">
                        <label>Upload File</label>
                        <input type="file" name="file" placeholder="Upload Journal File...">
                        @if($upload->path) <a class="ui mini label"
                            href="{{asset("storage/image/center/$upload->center_id/$upload->path")}}">{{$upload->path}}</a>
                        @endif
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
            selector:'#note',
            plugins: "advlist autolink link image lists charmap print preview autoresize table code",
            toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor"
        });
        /*setTimeout(function(){
            CKEDITOR.replace( 'note' );
        },100);*/

</script>
@endsection