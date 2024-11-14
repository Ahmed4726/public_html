@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if($discussion->department_id) <a class="section" href="{{ route("Laralum::department::event::list", ['department' => $discussion->department_id]) }}">All Notice List</a>
        @else <a class="section" href="{{ route('Laralum::event::list') }}">All Notice List</a> @endif
        <i class="right angle icon divider"></i>
        <div class="active section">All Notice File Upload</div>
    </div>
@endsection
@section('title', 'All Notice File Upload')
@section('icon', "upload")
@section('subtitle', $discussion->title)
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="sixteen wide column">
                <table class="ui selectable striped celled small table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Note</th>
                        <th>URL</th>
                        <th>Date</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($files as $file)
                        <tr>
                            <td> {{ $file->name }}</td>
                            <td>{!! $file->note !!}</td>
                            <td><a href="{{route("event::file::view", ['discussion' => $discussion->id, 'file' => $file->id])}}">/discussion/{{$discussion->id}}/file/{{$file->id}}</a> </td>
                            <td>{{date('Y-m-d', strtotime($file->created_on))}}</td>
                            <td>
                                <a href="{{ route("Laralum::event::upload::edit", ['discussion' => $discussion, 'file' => $file->id]) }}" class="small ui blue icon button ">
                                    <i class="edit icon"></i> Edit
                                </a>
                                <a href="{{route('event::file::view', ['discussion' => $discussion->id, 'file' => $file->id])}}" class="small ui icon teal button ">
                                    <i class="download icon"></i> Download
                                </a>
                                <a href="{{route('Laralum::event::upload::delete', ['discussion' => $discussion->id, 'file' => $file->id])}}" class="delete small ui icon red button ">
                                    <i class="delete icon"></i> Delete
                                </a>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="sixteen wide column" id="vue-app">
                <form class="ui form" method="POST" action="{{ route('Laralum::event::upload::save', ['discussion' => $discussion->id]) }}" enctype="multipart/form-data">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field required">
                            <label>Name</label>
                            <input type="text"  name="name" placeholder="Name..." required>
                        </div>

                        <div class="field">
                            <label>Notes</label>
                            <textarea name="note" id="note" placeholder="Notes..."></textarea>
                        </div>

                        <div class="field required">
                            <label>Upload File</label>
                            <input type="file"  name="file" placeholder="Upload File..." required>
                        </div>

                        <button type="submit" class="ui blue submit button">Upload</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <div class="ui mini modal">
        <div class="ui icon header">
            <i class="archive icon"></i>
            Are you sure you want to delete this item?
        </div>

        <div class="actions">
            <div class="ui negative button">
                No
            </div>
            <div class="ui positive right labeled icon button">
                Yes
                <i class="checkmark icon"></i>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .mini.button {
            margin: 1px !important;
        }
    </style>
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

        $(document).ready( function() {

            $('.delete').on("click", function (e) {
                e.preventDefault();
                var _this = $(this);

                $('.mini.modal').modal({
                    onApprove: function() {
                        window.location.href = _this.attr('href');
                    }
                }).modal('show');
            });

        });

    </script>
@endsection
