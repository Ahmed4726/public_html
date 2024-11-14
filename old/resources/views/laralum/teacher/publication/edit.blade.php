@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <a class="section" href="{{ route('Laralum::teacher::list') }}">Teacher List</a>
    <i class="right angle icon divider"></i>
    <a class="section" href="{{ route('Laralum::teacher::publication::list', ['teacher' => $teacher->id]) }}">Teacher
        Publication List</a>
    <i class="right angle icon divider"></i>
    <div class="active section">Teacher Publication Edit</div>
</div>
@endsection
@section('title', 'Teacher Publication Edit')
@section('icon', "edit")
@section('subtitle', html_entity_decode($publication->name))
@section('content')

<div class="ui doubling stackable grid container">
    <div class="row">
        <div class="two wide column"></div>
        <div class="twelve wide column" id="vue-app">
            <form class="ui form" method="POST"
                action="{{ route('Laralum::teacher::publication::update', ['teacher' => $teacher->id, 'publication' => $publication->id]) }}"
                enctype="multipart/form-data">
                <div class="ui very padded segment">
                    {{ csrf_field() }}

                    <div class="field required">
                        <label>Publication Type</label>
                        <select name="teacher_publication_type_id" required>
                            @foreach($types as $type)
                            <option @if($publication->teacher_publication_type_id == $type->id) selected @endif
                                value="{{$type->id}}">{{$type->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label>Authors Name</label>
                        <input type="text" name="author_name" placeholder="Authors Name..."
                            value="{{$publication->author_name}}">
                    </div>

                    <div class="field">
                        <label>Title</label>
                        <input type="text" name="name" placeholder="Name..." value="{!!$publication->name!!}">
                    </div>

                    <div class="field">
                        <label>Journal/ Conference Name</label>
                        <input type="text" name="journal_name" placeholder="Journal/ Conference Name..."
                            value="{{$publication->journal_name}}">
                    </div>

                    <div class="field">
                        <label>Conference Location</label>
                        <input type="text" name="conference_location" placeholder="Conference Location..."
                            value="{{$publication->conference_location}}">
                    </div>

                    <div class="field">
                        <label>Volume</label>
                        <input type="text" name="volume" placeholder="Volume..." value="{{$publication->volume}}">
                    </div>

                    <div class="field">
                        <label>Issue</label>
                        <input type="text" name="issue" placeholder="Issue..." value="{{$publication->issue}}">
                    </div>

                    <div class="field">
                        <label>Page</label>
                        <input type="text" name="page" placeholder="Page..." value="{{$publication->page}}">
                    </div>

                    <div class="field">
                        <label>Year</label>
                        <input type="text" name="publication_year" placeholder="Year..."
                            value="{{$publication->publication_year}}">
                    </div>

                    <div class="field">
                        <label>URL</label>
                        <input type="text" name="url" placeholder="URL..." value="{{$publication->url}}">
                    </div>

                    <div class="field">
                        <label>DOI</label>
                        <input type="text" name="url2" placeholder="DOI..." value="{{$publication->url2}}">
                    </div>

                    <div class="field">
                        <label>Description</label>
                        <textarea name="description" id="description">{{$publication->description}}</textarea>
                    </div>

                    <div class="field">
                        <label>Sorting Number</label>
                        <input type="number" name="sorting_order" placeholder="Sorting Number..."
                            value="{{$publication->sorting_order}}">
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