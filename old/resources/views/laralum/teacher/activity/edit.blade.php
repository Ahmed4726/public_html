@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <a class="section" href="{{ route('Laralum::teacher::list') }}">Teacher List</a>
    <i class="right angle icon divider"></i>
    <a class="section" href="{{ route('Laralum::teacher::activity::list', ['teacher' => $teacher->id]) }}">Teacher
        Activity List</a>
    <i class="right angle icon divider"></i>
    <div class="active section">Teacher Activity Edit</div>
</div>
@endsection
@section('title', 'Teacher Activity Edit')
@section('icon', "edit")
@section('subtitle', $activity->organization)
@section('content')

<div class="ui doubling stackable grid container">
    <div class="row">
        <div class="two wide column"></div>
        <div class="twelve wide column" id="vue-app">
            <form class="ui form" method="POST"
                action="{{ route('Laralum::teacher::activity::update', ['teacher' => $teacher->id, 'activity' => $activity->id]) }}"
                enctype="multipart/form-data">
                <div class="ui very padded segment">
                    {{ csrf_field() }}

                    <div class="field">
                        <label>Organization</label>
                        <input type="text" name="organization" placeholder="Organization..."
                            value="{{$activity->organization}}">
                    </div>

                    <div class="field">
                        <label>Position</label>
                        <input type="text" name="position" placeholder="Position..." value="{{$activity->position}}">
                    </div>

                    <div class="field">
                        <label>Description</label>
                        <textarea name="description" id="description">{{$activity->description}}</textarea>
                    </div>

                    <div class="field">
                        <label>Period</label>
                        <input type="text" name="period" placeholder="Period..." value="{{$activity->period}}">
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