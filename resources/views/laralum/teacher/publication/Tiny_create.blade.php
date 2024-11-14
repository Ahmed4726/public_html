@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <a class="section" href="{{ route('Laralum::teacher::list') }}">Teacher List</a>
    <i class="right angle icon divider"></i>
    <a class="section" href="{{ route('Laralum::teacher::publication::list', ['teacher' => $teacher->id]) }}">Teacher
        Publication List</a>
    <i class="right angle icon divider"></i>
    <div class="active section">Add Teacher Publication</div>
</div>
@endsection
@section('title', 'Add Teacher Publication')
@section('icon', "plus")
@section('subtitle', $teacher->name)
@section('content')

<div class="ui doubling stackable grid container">
    <div class="row">
        <div class="two wide column"></div>
        <div class="twelve wide column" id="vue-app">
            <form class="ui form" method="POST"
                action="{{ route('Laralum::teacher::publication::store', ['teacher' => $teacher->id]) }}"
                enctype="multipart/form-data">
                <div class="ui very padded segment">
                    {{ csrf_field() }}

                    <div class="field required">
                        <label>Publication Type</label>
                        <select name="teacher_publication_type_id" required>
                            @foreach($types as $type)
                            <option value="{{$type->id}}">{{$type->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>Description / Cite Text</label>
                        <textarea name="description" id="description"></textarea>
                    </div>
<!-- First Accordion Start -->
  <div class="panel-group" id="accordion"> 
    <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
          Enter Details (Optional)
        </a>
      </h4>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse">
      <div class="panel-body">
        <!-- Label Start -->
        <div class="field">
            <label>Authors Name</label>
            <input type="text" name="author_name" placeholder="Authors Name...">
        </div>

        <div class="field">
            <label>Title</label>
            <input type="text" name="name" placeholder="Name...">
        </div>

        <div class="field">
            <label>Journal/ Conference Name</label>
            <input type="text" name="journal_name" placeholder="Journal/ Conference Name...">
        </div>

        <div class="field">
            <label>Conference Location</label>
            <input type="text" name="conference_location" placeholder="Conference Location...">
        </div>

        <div class="field">
            <label>Volume</label>
            <input type="text" name="volume" placeholder="Volume...">
        </div>

        <div class="field">
            <label>Issue</label>
            <input type="text" name="issue" placeholder="Issue...">
        </div>

        <div class="field">
            <label>Page</label>
            <input type="text" name="page" placeholder="Page...">
        </div>

        <div class="field">
            <label>Year</label>
            <input type="text" name="publication_year" placeholder="Year...">
        </div>

        <div class="field">
            <label>URL</label>
            <input type="text" name="url" placeholder="URL...">
        </div>

        <div class="field">
            <label>DOI</label>
            <input type="text" name="url2" placeholder="DOI...">
        </div>
        <div class="field">
          <label>Embed iFrame</label>
          <textarea name="iframe" id="iframe"></textarea>
      </div>
        <div class="field">
            <label>Sorting Number</label>
            <input type="number" name="sorting_order" placeholder="Sorting Number..." value="0">
        </div>
         <!-- Label END --> 
      </div>
    </div>
  </div>
  </div>
  <!-- First Accordion END -->
 </div>
  
<!-- Latest compiled and minified Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<!-- Latest compiled and minified JavaScript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

 
                    <br>
                    <button type="submit" class="ui blue submit button">Save</button>
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