@extends('layouts.admin.panel')

@section('breadcrumb')
    <div class="ui breadcrumb">
        @if(isset($uri)) 
            <a class="section" href="{{ route("Laralum::$uri::list") }}">{{ucfirst($uri)}} List</a>
            <i class="right angle icon divider"></i>
            <a class="section" href="{{ route("Laralum::$uri::advance", [$uri => $uriValue]) }}">Advanced Option</a>
            <i class="right angle icon divider"></i>
        @endif
        <div class="active section">All Notice List</div>
    </div>
@endsection

@section('title', 'All Notice List')
@section('icon', "list")
@section('subtitle', 'All Notice List')

@section('createButton')
    @if(isset($uri)) 
        <a href="{{route("Laralum::$uri::event::create", [$uri => $uriValue])}}" class='large ui green right floated button white-text'>Create All Notice</a>
    @else 
        <a href="{{route('Laralum::event::create')}}" class='large ui green right floated button white-text'>Create All Notice</a> 
    @endif
@endsection

@section('content')
    <div class="ui one column doubling stackable grid container" id="vue-app">
        <div class="column">
            <form>
                <div class="ui fluid action input">
                    <input type="text" placeholder="Search by title..." name="search" value="{{ Request::get('search') }}">
                    <input type="text" id="from" name="from_date" autocomplete="off" readonly placeholder="Search From Date" value="{{ Request::get('from_date') }}">
                    <input type="text" id="to" name="to_date" autocomplete="off" readonly placeholder="Search To Date" value="{{ Request::get('to_date') }}">
                    <select class="ui selection dropdown" name="event_id">
                        <option value="">Please Select a type</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" @if(Request::get('event_id') == $event->id) selected @endif>{{ $event->name }}</option>
                        @endforeach
                    </select>
                    <select class="ui compact selection dropdown" name="status">
                        <option value="">Please Select a status</option>
                        <option value="1" @if(Request::get('status') == 1) selected @endif>Enabled</option>
                        <option value="disable" @if(Request::get('status') == 'disable') selected @endif>Disabled</option>
                    </select>
                    <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
                </div>
            </form>
            @if(isset($uri)) 
                <a class="float-right" href="{{route("Laralum::$uri::event::list", [$uri => $uriValue])}}">Clear Search</a>
            @else 
                <a class="float-right" href="{{route('Laralum::event::list')}}">Clear Search</a> 
            @endif
        </div>

        <div class="column">
            <div class="ui very padded segment">
                <!-- Bulk Delete Form - Positioned on top -->
                <form action="/admin/bulkDeleteEvent" method="POST">
                    @csrf
                    <!-- Bulk Delete Button at the top -->
                    <div style="margin-bottom: 10px;">
                        <button type="submit" class="ui red button" id="bulk-delete-button">Delete Selected</button>
                    </div>

                    <div class="checked checkbox">
                        <input type="checkbox" id="select-all">
                        <label>Select All</label>
                    </div>

                    <table class="ui selectable striped celled small table">
                        <thead>
                            <tr>
                                <th class="one wide">Select</th>
                                <th class="eight wide">Title</th>
                                <th class="one wide">Department</th>
                                <th class="one wide">Type</th>
                                <th class="two wide">Date</th>
                                <th class="four wide">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($discussions as $discussion)
                                <tr>
                                    <td>
                                        <div class="checkbox">
                                            <input type="checkbox" name="selected_discussions[]" value="{{ $discussion->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        @if(!$discussion->enabled)
                                            <i data-position="top center" data-content="Disabled" class="pop orange warning icon"></i>
                                        @else
                                            <i data-position="top center" data-content="Enabled" class="pop green checkmark icon"></i>
                                        @endif
                                        {!! $discussion->title !!}
                                    </td>
                                    <td>@if($discussion->department_id) {{ $discussion->department->short_name }} @else Global @endif</td>
                                    <td><label class="ui basic label">{{ $discussion->event->name }}</label></td>
                                    <td>{{ $discussion->publish_date }}</td>
                                    <td>
                                        <a href="{{ route('Laralum::event::edit', ['discussion' => $discussion->id]) }}" class="mini ui blue icon button"><i class="edit icon"></i> Edit</a>
                                        <a href="{{ route('Laralum::event::upload', ['discussion' => $discussion->id]) }}" class="mini ui green icon button"><i class="upload icon"></i> Upload</a>
                                        <a href="{{ route('Laralum::event::delete', ['discussion' => $discussion->id]) }}" class="delete mini ui red icon button"><i class="delete icon"></i> Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $discussions->links() }}
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
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        .mini.button {
            margin: 1px !important;
        }
    </style>
@endsection

@section('js')
    @include('laralum.include.jquery-ui')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
$(document).ready(function () {
    // Select all checkboxes
    $('#select-all').on('click', function () {
        $('input[name="selected_discussions[]"]').prop('checked', this.checked);
    });

    // Individual item deletion with SweetAlert
    $('.delete').on("click", function (e) {
        e.preventDefault();
        var _this = $(this);

        // SweetAlert for individual item deletion
        Swal.fire({
            title: 'Are you sure?',
            text: "This action will delete the discussion!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to delete URL if confirmed
                window.location.href = _this.attr('href');
            }
        });
    });

    // Bulk delete form submission with SweetAlert
    $('#bulk-delete-form').on('submit', function (e) {
        e.preventDefault();
        var selectedIds = $('input[name="selected_discussions[]"]:checked').map(function () {
            return $(this).val();
        }).get();

        if (selectedIds.length > 0) {
            // SweetAlert2 confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: "This action will delete the selected discussions!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete them!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with the bulk delete
                    $.ajax({
                        url: '/admin/discussion/bulkDelete', // Update this URL based on your route
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            selected_discussions: selectedIds
                        },
                        success: function (response) {
                            // Reload the page after successful delete
                            window.location.reload();
                        },
                        error: function () {
                            Swal.fire('Error!', 'An error occurred while deleting. Please try again.', 'error');
                        }
                    });
                }
            });
        } else {
            Swal.fire('No selection', 'Please select at least one discussion to delete.', 'info');
        }
    });

    // Datepicker initialization
    $("#from").datepicker({
        dateFormat: 'M dd, yy',
        changeMonth: true,
        changeYear: true,
        onClose: function (selectedDate) {
            $("#to").datepicker("option", "minDate", selectedDate);
        }
    });

    $("#to").datepicker({
        dateFormat: 'M dd, yy',
        changeMonth: true,
        changeYear: true,
        onClose: function (selectedDate) {
            $("#from").datepicker("option", "maxDate", selectedDate);
        }
    });
});

    </script>
@endsection
