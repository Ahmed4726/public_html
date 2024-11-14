@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">E-mail Broadcast</div>
    </div>
@endsection
@section('title', 'E-mail Broadcast')
@section('icon', "send")
@section('subtitle', 'E-mail Broadcast')
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column" id="vue-app">
                <form class="ui form" method="POST" action="{{ route('Laralum::email::broadcast::send') }}" enctype="multipart/form-data">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field">
                            <label>To Emails (comma separated if multiple):</label>
                            <input type="text"  name="email" placeholder="Emails (comma separated if multiple):...">
                        </div>

                        <label class="ui red label">OR</label>
                        <div class="field">
                            <label>User Group</label>
                            <select name="role_id">
                                <option value="">Please Select User Group</option>
                                @foreach($roles as $role)
                                    <option value="{{$role->id}}">{{$role->type}}</option>
                                @endforeach
                            </select>
                        </div>

                        <label class="ui red label">OR</label>
                        <div class="field">
                            <label>Teachers Of</label>
                            <select name="department_id">
                                <option value="">Please Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{$department->id}}">{{$department->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <label class="ui red label">OR</label>
                        <div class="field">
                            <label>Officers Of</label>
                            <select name="center_id">
                                <option value="">Please Select Centers / Offices</option>
                                @foreach($centers as $center)
                                    <option value="{{$center->id}}">{{$center->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field required">
                            <label>E-mail Subject</label>
                            <input type="text" name="subject" placeholder="E-mail Subject...">
                        </div>

                        <div class="field required">
                            <label>E-mail Body</label>
                            <textarea name="body" id="body" placeholder="Description">
                                Dear concern,
                                <br/><br/><br/>
                                ...
                                <br/>
                                Best Regards,<br/>
                                Jahangirnagar University
                            </textarea>
                        </div>

                        <br>
                        <button type="submit" class="ui blue submit button">Send Now</button>
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
            selector:'#body',
            plugins: "advlist autolink link image lists charmap print preview autoresize table code",
            toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor"
        });
        /*setTimeout(function(){
            CKEDITOR.replace( 'body' );
        },100);*/

    </script>
@endsection
