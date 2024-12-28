@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">NOC & GO List</div>
    </div>
@endsection
@section('title', 'NOC & GO List')
@section('icon', "list")
@section('subtitle', 'NOC & GO List')

@section('createButton')
    <a href="{{route('Laralum::certificate::create')}}" class='large ui green right floated button white-text'>Create NOC & GO</a>
@endsection

@section('content')

<div class="ui one column doubling stackable grid container" id="vue-app">

    <div class="column">
        <form action="/admin/certificate/bulkDelete" method="POST" id="bulk-delete-form">
            @csrf
            <div class="ui fluid action input">
                <input type="text" placeholder="Search by name, designation..." name="search" value="{{ Request::get('search') }}">

                <input type="text" id="from" name="from_date" autocomplete="off" readonly placeholder="Search From Date" value="{{ Request::get('from_date') }}">
                <input type="text" id="to" name="to_date" autocomplete="off" readonly placeholder="Search To Date" value="{{ Request::get('to_date') }}">

                <select class="ui selection dropdown" name="type_id">
                    <option value="">Please Select Type</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" @if(Request::get('type_id') == $type->id) selected @endif>{{ $type->name }}</option>
                    @endforeach
                </select>

                <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
            </div>
        </form>
        <a class="float-right" href="{{route('Laralum::certificate::list')}}">Clear Search</a>
    </div>

    <div class="column">
        <div class="ui very padded segment">
            <form id="bulk-delete-form" action="/admin/certificate/bulkDelete" method="POST">
                @csrf
                <button type="submit" class="ui red button" id="bulk-delete-button" disabled>Delete Selected</button>

                <table class="ui selectable striped celled small table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th class="five wide">Name</th>
                            <th class="one wide">Type</th>
                            <th class="three wide">Designation</th>
                            <th class="two wide">File</th>
                            <th class="two wide">Date</th>
                            <th class="three wide">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($certificates as $certificate)
                            <tr>
                                <td><input type="checkbox" name="selected_certificates[]" value="{{ $certificate->id }}" class="certificate-checkbox"></td>
                                <td>{{ $certificate->name }}</td>
                                <td>@if($certificate->typeInfo()->exists()) {{ $certificate->typeInfo->name }} @endif</td>
                                <td>{{ $certificate->designation }}</td>
                                <td>@if($certificate->path) <a href="{{ route('certificate::file::view', ['certificate' => $certificate->id]) }}">/certificate/{{$certificate->id}}/file</a> @endif</td>
                                <td>{{ $certificate->date }}</td>
                                <td>
                                    <a href="{{ route('Laralum::certificate::edit', ['certificate' => $certificate->id]) }}" class="mini ui blue icon button"><i class="edit icon"></i> Edit</a>
                                    <a href="{{ route('Laralum::certificate::delete', ['certificate' => $certificate->id]) }}" class="delete mini ui red icon button"><i class="delete icon"></i> Delete</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
            {{ $certificates->links() }}
        </div>
        <br>
    </div>
</div>

<div class="ui mini modal">
    <div class="ui icon header">
        <i class="archive icon"></i>
        Are you sure you want to delete these items?
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

    <script>
        $(document).ready(function() {
            // Enable/Disable the bulk delete button
            $('#select-all').on('change', function() {
                var checked = $(this).prop('checked');
                $('.certificate-checkbox').prop('checked', checked);
                toggleBulkDeleteButton();
            });

            $('.certificate-checkbox').on('change', function() {
                toggleBulkDeleteButton();
            });

            function toggleBulkDeleteButton() {
                if ($('.certificate-checkbox:checked').length > 0) {
                    $('#bulk-delete-button').prop('disabled', false);
                } else {
                    $('#bulk-delete-button').prop('disabled', true);
                }
            }

            // Bulk delete confirmation modal
            $('#bulk-delete-form').on('submit', function(e) {
                e.preventDefault();
                var selectedIds = $('.certificate-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length > 0) {
                    $.ajax({
                        url: '/admin/certificate/bulkDelete',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            selected_certificates: selectedIds
                        },
                        success: function(response) {
                            window.location.reload(); // Reload the page to reflect changes
                        },
                        error: function() {
                            alert('An error occurred. Please try again.');
                        }
                    });
                }
            });

            $( "#from" ).datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                onClose: function( selectedDate ) {
                    $( "#to" ).datepicker( "option", "minDate", selectedDate );
                }
            });
            $( "#to" ).datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                onClose: function( selectedDate ) {
                    $( "#from" ).datepicker( "option", "maxDate", selectedDate );
                }
            });
        });
    </script>
@endsection