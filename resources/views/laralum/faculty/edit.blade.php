@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::faculty::list') }}">Faculty List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Faculty Edit</div>
    </div>
@endsection
@section('title', 'Faculty Edit')
@section('icon', "edit")
@section('subtitle', $faculty->name)
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column" id="vue-app">
                <form class="ui form" method="POST" action="{{ route('Laralum::faculty::update', ['faculty' => $faculty->id]) }}" enctype="multipart/form-data">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        @can('ADMIN')<div class="field required">
                            <label>Name</label>
                            <input type="text"  name="name" placeholder="Name..." required value="{{$faculty->name}}">
                        </div>@endcan

                        <div class="field">
                            <label>Name in Bangla</label>
                            <input type="text"  name="name_ben" placeholder="Name in Bangla..." value="{{$faculty->name_ben}}">
                        </div>

                        @can('ADMIN') <div class="field required">
                            <label>Faculty</label>
                            <select name="type" required>
                                <option value="FACULTY" @if($faculty->type == 'FACULTY') selected @endif>FACULTY</option>
                                <option value="INSTITUTE" @if($faculty->type == 'INSTITUTE') selected @endif>INSTITUTE</option>
                            </select>
                        </div>

                        <div class="field required">
                            <label>Slug (User Friendly URL)</label>
                            <input type="text" name="slug" placeholder="Slug..." required value="{{$faculty->slug}}">
                        </div> @endcan

                        <div class="field">
                            <label>Description</label>
                            <textarea class="tinymce" name="description" id="description" placeholder="Description">{{$faculty->description}}</textarea>
                        </div>

                        <div class="field">
                            <label>Message From Dean</label>
                            <textarea class="tinymce" name="message_from_dean" id="message_from_dean" placeholder="Message from Dean...">{{$faculty->message_from_dean}}</textarea>
                        </div>

                        <div class="field">
                            <label>Email</label>
                            <input type="email"  name="email" placeholder="Email..." value="{{$faculty->email}}">
                        </div>

                        <div class="field">
                            <label>FAX</label>
                            <input type="text"  name="fax" placeholder="FAX..." value="{{$faculty->fax}}">
                        </div>

                        <div class="field">
                            <label>FABX</label>
                            <input type="text"  name="fabx" placeholder="FABX..." value="{{$faculty->fabx}}">
                        </div>

                        <div class="field">
                            <label>Mobile Number</label>
                            <input type="text"  name="mobile_number" placeholder="Mobile Number..." value="{{$faculty->mobile_number}}">
                        </div>

                        <div class="field">
                            <label>Phone Number</label>
                            <input type="text"  name="phone_number" placeholder="Phone Number..." value="{{$faculty->phone_number}}">
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
            selector:'.tinymce',
            plugins: "advlist autolink link image lists charmap print preview autoresize table code",
            toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor"
        });

        /*setTimeout(function(){
            CKEDITOR.replace( 'message_from_dean' );
        },100);

        setTimeout(function(){
            CKEDITOR.replace( 'description' );
        },100);*/

    </script>
@endsection
