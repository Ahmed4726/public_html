@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <div class="active section">Student Email List</div>
</div>
@endsection
@section('title', 'Student Email List')
@section('icon', "list")
@section('subtitle', 'Student Email List')

@section('createButton')
<div class="ui green buttons right floated">
    <form action="{{route('Laralum::student-email-apply::export')}}" method="get">
        <div class="ui floating dropdown icon button" id="export">
            <i class="dropdown icon"></i>
            <div class="menu" id="export-menu">
                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" name="columns[]" value="Admission Session">
                        <label>Admission Session</label>
                    </div>
                </div>

                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" name="columns[]" value="Registration Number">
                        <label>Registration Number</label>
                    </div>
                </div>
                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" name="columns[]" value="Department">
                        <label>Department Name</label>
                    </div>
                </div>
                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" name="columns[]" value="Program">
                        <label>Program Name</label>
                    </div>
                </div>
                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" name="columns[]" value="Hall">
                        <label>Hall Name</label>
                    </div>
                </div>
                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" checked name="columns[]" value="First Name">
                        <label>First Name</label>
                    </div>
                </div>
                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" name="columns[]" value="Middle Name">
                        <label>Middle Name</label>
                    </div>
                </div>
                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" checked name="columns[]" value="Last Name">
                        <label>Last Name</label>
                    </div>
                </div>
                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" checked name="columns[]" value="Email">
                        <label>Email</label>
                    </div>
                </div>
                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" checked name="columns[]" value="Password">
                        <label>Password</label>
                    </div>
                </div>
                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" checked name="columns[]" value="Existing Email">
                        <label>Existing Email</label>
                    </div>
                </div>
                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" checked name="columns[]" value="Contact Phone">
                        <label>Contact Phone</label>
                    </div>
                </div>
                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" name="columns[]" value="Status">
                        <label>Status</label>
                    </div>
                </div>
                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" name="columns[]" value="Applied At">
                        <label>Applied At</label>
                    </div>
                </div>
                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" name="columns[]" value="Updated At">
                        <label>Updated At</label>
                    </div>
                </div>
                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" name="columns[]" value="Updated By">
                        <label>Updated By</label>
                    </div>
                </div>
                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" name="columns[]" value="Comment">
                        <label>Comment</label>
                    </div>
                </div>

                @foreach (request()->all() as $key => $value)
                <input type="hidden" name="{{$key}}" value="{{$value}}">
                @endforeach
            </div>
        </div>
        <button type="submit" class="ui button">Export</button>
    </form>
</div>
@endsection

@section('content')

<div class="ui one column doubling stackable grid container">

    <div class="column">
        <form>
            <div class="ui fluid action input container grid mb-0">
                <input type="text" placeholder="Search by name / registration / phone / username / existing email ..."
                    name="search" value="{{ request()->search }}">

                <select class="ui dropdown" name="admission_session_id">
                    <option value="">--- Session ---</option>
                    @foreach($sessions as $session)
                    <option value="{{$session->id}}" @if(request()->admission_session_id==$session->id) selected
                        @endif>
                        {{$session->name}}
                    </option>
                    @endforeach
                </select>

                <select class="ui dropdown" name="department_id">
                    <option value="">--- Department ---</option>
                    @foreach($departments as $department)
                    <option value="{{$department->id}}" @if(request()->department_id==$department->id) selected
                        @endif>
                        {{$department->name}}
                    </option>
                    @endforeach
                </select>

            </div>

            <div class="ui fluid action input container grid mb-0">
                <input type="text" placeholder="Search by applied at date range ..." id="range" name="range"
                    value="{{ request()->range }}">

                <select class="ui dropdown" name="program_id">
                    <option value="">--- Program ---</option>
                    @foreach($programs as $program)
                    <option value="{{$program->id}}" @if(request()->program_id==$program->id) selected
                        @endif>{{$program->name}}</option>
                    @endforeach
                </select>

                <select class="ui dropdown" name="hall_id">
                    <option value="">--- Hall ---</option>
                    @foreach($halls as $hall)
                    <option value="{{$hall->id}}" @if(request()->hall_id==$hall->id) selected
                        @endif>{{$hall->name}}</option>
                    @endforeach
                </select>

                <select class="ui dropdown" name="status_id">
                    <option value="">--- Status ---</option>
                    @foreach($statuses as $status)
                    <option value="{{$status->id}}" @if(request()->status_id==$status->id) selected
                        @endif>{{$status->name}}</option>
                    @endforeach
                </select>

                <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
            </div>
        </form>

        <a class="float-right" href="{{route('Laralum::student-email-apply::list')}}">Clear Search</a>
    </div>

    <div class="column">
        <div class="ui padded segment" id="vue-app">
            <form method="POST" action="/admin/student-email-apply/bulk-delete">
                @csrf
                <button type="submit" class="ui red button" id="bulk-delete-btn" disabled>
                     Delete Selected
                </button>
            <table class="ui selectable padded compact striped celled small table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>Session</th>
                        <th>Registration</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Program</th>
                        <th>Email</th>
                        <th>ID Card</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($emails as $email)
                    <tr>
                        <td><input type="checkbox" name="ids[]" value="{{ $email->id }}" class="select-checkbox"></td>
                        <td>{{ optional($email->admissionSession)->name }}</td>
                        <td>{{ $email->registration_number }}</td>
                        <td>{{ $email->name }}</td>
                        <td>{{ optional($email->department)->short_name }}</td>
                        <td>
                            {{ optional($email->program)->name }}
                            @if ($email->semester)
                            <br><span class="italic"> {{$email->semester}} </span>
                            @endif
                        </td>
                        <td class="flex">
                            @if ($email->isPending())
                            <i data-position="top center" data-content="{{App\StudentEmailApply::$pending}}"
                                class="pop yellow warning icon"></i>
                            @elseif($email->isCompleted())
                            <i data-position="top center" data-content="{{App\StudentEmailApply::$completed}}"
                                class="pop green checkmark icon"></i>
                            @elseif($email->isRejected())
                            <i data-position="top center" data-content="{{App\StudentEmailApply::$rejected}}"
                                class="pop red x icon"></i>
                            @elseif($email->isChecked())
                            <i data-position="top center" data-content="{{App\StudentEmailApply::$checked}}"
                                class="pop teal check circle outline icon"></i>
                            @endif
                            {{ $email->username }}@juniv.edu
                        </td>
                        <td>
                            <a href="{{asset("storage/image/student-email-apply/$email->image")}}" target="_blank">
                                View ID Card
                            </a>
                        </td>
                        <td>
                            <div class="ui blue top icon right floating pointing dropdown button">
                                <i class="cog icon"></i>
                                <div class="menu">
                                    <div class="header">Advanced Option</div>
                                    @can('STUDENT-EMAIL-MANAGE')
                                    <a href='{{route("Laralum::student-email-apply::edit", ['email' => $email->id])}}'
                                        class="item">
                                        <i class="edit icon"></i>Edit
                                    </a>
                                    @endcan
                                    <a target="_blank"
                                        href='{{route("Laralum::student-email-apply::detail", ['email' => $email->id])}}'
                                        class="item">
                                        <i class="envelope icon"></i>Detail
                                    </a>

                                    @if ($email->isPending() && auth()->user()->can('STUDENT-EMAIL-MANAGE'))
                                    <a href='/{{$email->id}}/mark-checked' class="item check">
                                        <i class="check circle outline icon"></i>Mark Checked
                                    </a>
                                    <a href='#' style="color: red" class="item"
                                        @click.prevent="modalShow('/{{$email->id}}/mark-rejected')">
                                        <i class="x icon"></i>Mark Rejected
                                    </a>

                                    @elseif($email->isChecked() && auth()->user()->can('STUDENT-EMAIL-MANAGE'))
                                    <a href="{{route("Laralum::student-email-apply::mark::completed", ['email' => $email->id])}}"
                                        class="item">
                                        <i class="check icon"></i>Mark Completed
                                    </a>
                                    @endif

                                    @if ($email->isRejected() && auth()->user()->can('ADMIN'))
                                    <a href="/{{$email->id}}/delete" class="delete item">
                                        <i class="trash red alternate outline icon"></i> Delete
                                    </a>
                                    @endif

                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="row ui grid">
                <div class="twelve wide column">
                    {{ $emails->appends(request()->all())->links() }}
                </div>
                <div class="four wide column right aligned">
                    Showing {{$emails->firstItem()}} to {{$emails->lastItem()}} of {{$emails->total()}} entries
                </div>
            </div>

            {{-- Rejected Submit Modal Start --}}
            <form :action="url" method="get" class="ui mini rejected modal">
                <div class="ui icon header">
                    <div class="ui input">
                        <input type="text" placeholder="Reason for Reject..." style="padding: .45em 1em;" name="comment"
                            v-model="comment">
                    </div>
                </div>

                <div class="actions">
                    <div class="ui negative button">
                        Cancel
                    </div>
                    <button class="ui positive right labeled icon button" type="submit" :disabled="!comment"><i
                            class="checkmark icon"></i>
                        Reject
                    </button>
                </div>
            </form>
            {{-- Rejected Submit Modal End --}}

        </div>
        <br>
    </div>
</div>

{{-- Check Confirmation Modal Start --}}
<div class="ui mini checked modal">
    <div class="ui icon header">
        <i class="archive icon"></i>
        Are you sure you want to checked this item?
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
{{-- Check Confirmation Modal End --}}

{{-- Delete Confirmation Modal Start --}}
<div class="ui mini deleted modal">
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
{{-- Delete Confirmation Modal End --}}

@endsection

@section('js')
@include('laralum.include.flatpickr')
@include('laralum.include.vue.vue-axios')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<script type="text/javascript">
    new Vue({
        el : "#vue-app",
        data : {
            comment : '',
            url : ""
        },
        
        methods : {
            modalShow(id) {
                this.comment = '';
                this.url = "{{route('Laralum::student-email-apply::list')}}"+id;
                $('.rejected.modal').modal('show');
            }
        }
    });
    $(document).ready(function() {
    // Toggle Select All checkboxes
    $('#select-all').on('change', function() {
        var checked = $(this).prop('checked');
        $('.select-checkbox').prop('checked', checked);
        toggleBulkDeleteButton();
    });

    // Enable/disable the bulk delete button based on the selected checkboxes
    $('.select-checkbox').on('change', function() {
        toggleBulkDeleteButton();
    });

    // Function to enable/disable the bulk delete button
    function toggleBulkDeleteButton() {
        var selected = $('.select-checkbox:checked').length > 0;
        $('#bulk-delete-btn').prop('disabled', !selected);
    }

    // Initially check the state of the checkboxes
    toggleBulkDeleteButton();

    // Bulk delete confirmation with SweetAlert
    $('#bulk-delete-btn').on('click', function(e) {
        e.preventDefault();

        // SweetAlert confirmation before performing the bulk delete
        Swal.fire({
            title: 'Are you sure?',
            text: "This action will delete all selected items!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete them!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the bulk delete action here (you can trigger the form submit, or any other action)
                // For example, if you are submitting a form for bulk delete:
                $('#bulk-delete-form').submit(); // Ensure this is your form for bulk delete
            } else {
                // If canceled, do nothing
                return false;
            }
        });
    });
});


    $(document).ready( function() {
        flatpickr("#range", {mode: "range"});

        $("#export-menu").click(function(e){
            e.preventDefault();
            e.stopPropagation();
            $('#export').dropdown('show');
        });

        $('.check').on("click", function (e) {
            e.preventDefault();
            var _this = $(this);
            $('.checked.modal').modal({
                onApprove: function() {
                    window.location.href = "{{route('Laralum::student-email-apply::list')}}"+_this.attr('href');
                }
            }).modal('show');
        });

        $('.delete').on("click", function (e) {
            e.preventDefault();
            var _this = $(this);
            $('.deleted.modal').modal({
                onApprove: function() {
                    window.location.href = "{{route('Laralum::student-email-apply::list')}}"+_this.attr('href');
                }
            }).modal('show');
        });
    });

</script>
@endsection