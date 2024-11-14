@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::administration::role::list') }}">Administrative Role List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Administrative Role Create</div>
    </div>
@endsection
@section('title', 'Administrative Role Create')
@section('icon', "plus")
@section('subtitle', 'Administrative Role Create')
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column" id="vue-app">
                <form class="ui form" method="POST" action="{{ route('Laralum::administration::role::store') }}" enctype="multipart/form-data">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field required">
                            <label>Name</label>
                            <input type="text"  name="name" placeholder="Name..." required>
                        </div>

                        <div class="field required">
                            <label>Type</label>
                            <select name="type_id" required>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field">
                            <label>Description</label>
                            <textarea name="description" id="description"></textarea>
                        </div>


                        <div class="field required">
                            <label>View Type</label>
                            <select name="preview" required>
                                <option value="LIST">List</option>
                                <option value="CUSTOM">Custom Page</option>
                            </select>
                        </div>

                        <div class="field">
                            <label>Custom Page URL</label>
                            <input type="text"  name="page_url" placeholder="Page URL...">
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
        selector:'#description',
            plugins: "advlist autolink link image lists charmap print preview autoresize table code",
        toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor"
        });

        /*setTimeout(function(){
            CKEDITOR.replace( 'description' );
        },100);*/

    </script>
@endsection
