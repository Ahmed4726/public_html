@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::department::list') }}">Department List</a>
        <i class="right angle icon divider"></i>
        <a class="section" href="{{ route('Laralum::department::advance', ['department' => $department->id]) }}">Advanced Option</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Department File Upload</div>
    </div>
@endsection
@section('title', 'Department File Upload')
@section('icon', "upload")
@section('subtitle', $department->name)

@section('createButton')
    <a href="{{route('Laralum::department::upload::create', ['department' => $department->id])}}" class='large ui green right floated button white-text'>Upload New File</a>
@endsection

@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="sixteen wide column">
                <table class="ui selectable striped celled small table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody id="sortable">
                    @foreach($files as $key => $file)
                        <tr data-item="{{$file->id}}">
                            <td>{{$key+1}}</td>
                            <td> @if(!$file->listing_enabled)
                                    <i data-position="top center" data-content="Disabled to Show in Home" class="pop orange warning icon"></i>
                                @else
                                    <i data-position="top center" data-content="Enabled to Show in Home" class="pop green checkmark icon"></i>
                                @endif
                                {{ $file->name }}</td>
                            <td>{{ $file->type }}</td>
                            <td>{!! $file->description !!}</td>
                            <td>{{date('Y-m-d', strtotime($file->created_on))}}</td>
                            <td>
                                <a href='{{ route('Laralum::department::upload::edit', ['department' => $department->id, 'file' => $file->id]) }}' class="mini ui blue icon button"><i class="edit icon"></i> Edit</a>
                                <a href='{{ route('department::file::view', ['department' => $department->id, 'file' => $file->id]) }}' class="mini ui green icon button"><i class="download icon"></i> Download</a>
                                <a href='{{ route('Laralum::department::upload::delete', ['department' => $department->id, 'file' => $file->id]) }}' class="delete mini ui red icon button"><i class="delete icon"></i> Delete</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <button class="ui green button" type="button" id="applyReOrder"><i class="icon list"></i>Update Order</button><br/><br/>
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
    @include('laralum.include.jquery-ui')
    @include('laralum.include.pnotify')
    @include('laralum.include.vue.vue-axios')

    <script type="text/javascript">

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

            $('td, th', '#sortable').each(function () {
                var cell = $(this);
                cell.width(cell.width());
            });

            // $( "#sortable" ).sortable().disableSelection();

            $( "#sortable" ).sortable( {
                update: function( event, ui ) {
                    $(this).children().each(function(index) {
                        $(this).find('td').first().html(index + 1)
                    });
                }
            });

            $("#applyReOrder").click(function(){
                var data = $("#sortable").sortable('toArray', {attribute: "data-item"});
                axios.get("{{route('Laralum::setting::reorder')}}", {
                    params: {
                        data : data,
                        model : 'App\\DepartmentFile',
                        field : 'sorting_order'
                    }
                }).
                then(response => {
                    if(response.status == 200) {
                        PNotify.success({title: 'Success', text: response.data})
                    }
                    else PNotify.error('Something Went Wrong!');
                });
            });
        });
    </script>
@endsection
