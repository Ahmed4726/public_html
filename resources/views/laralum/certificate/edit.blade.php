@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::certificate::list') }}">NOC & GO List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">NOC & GO Edit</div>
    </div>
@endsection
@section('title', 'NOC & GO Edit')
@section('icon', "edit")
@section('subtitle', $certificate->name)
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column" id="vue-app">
                <form class="ui form" method="POST" action="{{ route('Laralum::certificate::update', ['certificate' => $certificate->id]) }}" enctype="multipart/form-data">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field required">
                            <label>Name</label>
                            <input type="text"  name="name" placeholder="Name..." required value="{{$certificate->name}}">
                        </div>

                        <div class="field required">
                            <label>Type</label>
                            <select name="type_id" required>
                                <option value=""> Please Select a Type</option>
                                @foreach($types as $type)
                                    <option @if($certificate->type_id == $type->id) selected @endif value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field">
                            <label>Designation</label>
                            <input type="text"  name="designation" placeholder="Designation..." value="{{$certificate->designation}}">
                        </div>

                        <div class="field">
                            <label>Address</label>
                            <input type="text"  name="address" placeholder="Address..." value="{{$certificate->address}}">
                        </div>

                        <div class="ui calendar field" id="date">
                            <label>Date</label>
                            <div class="ui input left icon">
                                <i class="calendar icon"></i>
                                <input type="text" name="date" id="datePicker" placeholder="Date.." autocomplete="off"  value="{{$certificate->date}}">
                            </div>
                        </div>

                        <div class="field">
                            <label>External Link</label>
                            <input type="text"  name="external_link" placeholder="External Link..." value="{{$certificate->external_link}}">
                        </div>

                        <div class="field">
                            <label>Upload File</label>
                            <input type="file"  name="file" placeholder="Upload File...">
                            @if($certificate->path) <a class="ui mini label" href="{{asset("storage/image/certificate/$certificate->path")}}">{{$certificate->path}}</a> @endif
                        </div>

                        <br>
                        <button type="submit" class="ui blue submit button">Update</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection


@section('js')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script type="text/javascript">

        $(document).ready( function() {

            $( "#datePicker" ).datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true
            });

        });
    </script>
@endsection

