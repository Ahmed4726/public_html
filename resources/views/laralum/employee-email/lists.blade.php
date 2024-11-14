@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <div class="active section">Employee Email List</div>
</div>
@endsection
@section('title', 'Employee Email List')
@section('icon', "list")
@section('subtitle', 'Employee Email List')

@section('createButton')
<div class="ui green buttons right floated">
    <form action="{{route('Laralum::employee-email::export')}}" method="get">
        <div class="ui floating dropdown icon button" id="export">
            <i class="dropdown icon"></i>
            <div class="menu" id="export-menu">
                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" checked name="columns[]" value="Name">
                        <label>Name</label>
                    </div>
                </div>

                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" checked name="columns[]" value="Employee ID">
                        <label>Employee ID</label>
                    </div>
                </div>

                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" checked name="columns[]" value="Designation">
                        <label>Designation</label>
                    </div>
                </div>
                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" checked name="columns[]" value="Department / Office">
                        <label>Department / Office</label>
                    </div>
                </div>

                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" checked name="columns[]" value="Phone Number">
                        <label>Phone Number</label>
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
                        <input type="checkbox" name="columns[]" value="Expected Email 1">
                        <label>Expected Email 1</label>
                    </div>
                </div>
                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" name="columns[]" value="Expected Email 2">
                        <label>Expected Email 2</label>
                    </div>
                </div>
                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" name="columns[]" value="Expected Email 3">
                        <label>Expected Email 3</label>
                    </div>
                </div>
                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" checked name="columns[]" value="Username">
                        <label>Username</label>
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
                        <input type="checkbox" checked name="columns[]" value="Status">
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
                        <input type="checkbox" name="columns[]" value="Completed By">
                        <label>Completed By</label>
                    </div>
                </div>
                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" name="columns[]" value="Completed At">
                        <label>Completed At</label>
                    </div>
                </div>
                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" name="columns[]" value="Rejected By">
                        <label>Rejected By</label>
                    </div>
                </div>
                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" name="columns[]" value="Rejected At">
                        <label>Rejected At</label>
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
                <input type="text" placeholder="Search by name/ id/ designation/ phone/ email/ username ..."
                    name="search" value="{{ request()->search }}">

                {{-- <select class="ui dropdown" name="employee_type_id">
                    <option value="">--- Designation ---</option>
                    @foreach($types as $type)
                    <option value="{{$type->id}}" @if(request()->employee_type_id==$type->id) selected
                @endif>{{$type->name}}</option>
                @endforeach
                </select> --}}

                <select class="ui dropdown" name="department_id">
                    <option value="">--- Department ---</option>
                    @foreach($departments as $department)
                    <option value="{{$department->id}}" @if(request()->department_id==$department->id) selected
                        @endif>{{$department->name}}</option>
                    @endforeach
                    {{-- <option value="other" @if(request()->department_id=='other') selected @endif>Other</option> --}}
                </select>

                <select class="ui dropdown" name="office_id">
                    <option value="">--- Office ---</option>
                    @foreach($offices as $office)
                    <option value="{{$office->id}}" @if(request()->office_id==$office->id) selected
                        @endif>{{$office->name}}</option>
                    @endforeach
                </select>

                <select class="ui dropdown" name="global_status_id">
                    <option value="">--- Status ---</option>
                    @foreach($statuses as $key => $status)
                    <option value="{{$status->id}}" @if(request()->global_status_id==$status->id) selected
                        @endif>{{$status->name}}</option>
                    @endforeach
                </select>

                <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
            </div>
        </form>

        <a class="float-right" href="{{route('Laralum::employee-email::list')}}">Clear Search</a>
    </div>

    <div class="column">
        <div class="ui padded segment" id="employee-email">

            <table class="ui selectable padded compact striped celled small table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>ID</th>
                        <th>Designation</th>
                        <th>Deparment / Office</th>
                        <th>Phone</th>
                        <th>Current Email</th>
                        <th>Expected Username</th>
                        <th>Status</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($emails as $email)
                    <tr>
                        <td>{{ $email->name }}</td>
                        <td>{{ $email->employee_id }}</td>
                        <td>{{ $email->designation }}</td>
                        <td>{{ $email->facultyOrOffice() }}</td>
                        <td>{{ $email->phone_no }}</td>
                        <td>{{ $email->current_email }}</td>
                        <td>
                            @if ($email->expected_email_1)
                            {{$email->expected_email_1}}<br />
                            @endif
                            @if ($email->expected_email_2)
                            {{$email->expected_email_2}}<br />
                            @endif
                            @if ($email->expected_email_3)
                            {{$email->expected_email_3}}<br />
                            @endif
                        </td>
                        <td>
                            <span class="ui 
                            {{
                            [
                                App\EmployeeEmail::$pending => 'teal',
                                App\EmployeeEmail::$completed => 'green',
                                App\EmployeeEmail::$rejected => 'red',
                            ][optional($email->globalStatus)->name ?? App\EmployeeEmail::$pending]
                            }}
                            label">{{ optional($email->globalStatus)->name }}</span>
                        </td>
                        <td>
                            <div class="ui blue top icon right floating pointing dropdown button">
                                <i class="cog icon"></i>
                                <div class="menu">
                                    <div class="header">Advanced Option</div>
                                    @can('ADMIN')
                                    <a href='{{route("Laralum::employee-email::edit", ['email' => $email->id])}}'
                                        class="item">
                                        <i class="edit icon"></i>Edit
                                    </a>
                                    @endcan
                                    <a target="_blank"
                                        href='{{route("Laralum::employee-email::detail", ['email' => $email->id])}}'
                                        class="item">
                                        <i class="envelope icon"></i>Detail
                                    </a>

                                    @if ($email->isPending() && auth()->user()->can('ADMIN'))
                                    <a href='#' style="color: red" class="item"
                                        @click.prevent="rejectModal('/{{$email->id}}/mark-rejected')">
                                        <i class="x icon"></i>Mark Rejected
                                    </a>

                                    <a href="#" class="item"
                                        @click.prevent="completeModal(
                                            '/{{$email->id}}/mark-completed',
                                             ['{{$email->expected_email_1}}', '{{$email->expected_email_2}}', '{{$email->expected_email_3}}'])">
                                        <i class="check icon"></i>Mark Completed
                                    </a>
                                    @endif

                                    {{-- @if ($email->isCompleted() && auth()->user()->can('ADMIN')) --}}
                                    <a target="_blank"
                                        href="{{route('Laralum::employee-email::pdf', ['email' => $email->id])}}"
                                        class="item">
                                        <i class="download icon"></i> PDF Download
                                    </a>
                                    {{-- @endif --}}

                                    @if ($email->isRejected() && auth()->user()->can('ADMIN'))
                                    <a href="/{{$email->id}}/delete" class="delete item"
                                        @click.prevent="deleteModal('/{{$email->id}}/delete')">
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

            {{-- Completed Submit Modal Start --}}
            <form :action="completeUrl" method="get" class="ui mini complete modal">
                <div class="ui icon header">
                    <div class="ui input">
                        <select v-model="username" class="mr-2">
                            <option v-for="currentUsername in currentAllUsernames" :value="currentUsername">
                                @{{currentUsername}}</option>
                        </select>
                        <input class="ml-2" type="text" placeholder="Reason for Reject..." style="padding: .45em 1em;"
                            name="username" v-model="username" required>
                    </div>
                </div>

                <div class="actions">
                    <div class="ui negative button">
                        Cancel
                    </div>
                    <button class="ui positive right labeled icon button" type="submit"><i class="checkmark icon"></i>
                        Complete
                    </button>
                </div>
            </form>
            {{-- Completed Submit Modal End --}}

            {{-- Rejected Submit Modal Start --}}
            <form :action="rejectUrl" method="get" class="ui mini rejected modal">
                <div class="ui icon header">
                    <div class="ui input">
                        <input type="text" placeholder="Reason for Reject..." style="padding: .45em 1em;" name="comment"
                            v-model="comment" required>
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

            {{-- Delete Confirmation Modal Start --}}
            <form :action="deleteUrl" method="get" class="ui mini delete modal">
                <div class="ui icon header">
                    <i class="archive icon"></i>
                    Are you sure you want to delete this item?
                </div>

                <div class="actions">
                    <div class="ui negative button">
                        No
                    </div>
                    <button class="ui positive right labeled icon button" type="submit"><i class="checkmark icon"></i>
                        Yes
                    </button>
                </div>
            </form>
            {{-- Delete Confirmation Modal End --}}

        </div>
        <br>
    </div>
</div>

@endsection

@section('js')
@include('laralum.include.vue.vue-axios')
@include('laralum.include.vue.employee-email-modal')
<script type="text/javascript">
    $(document).ready( function() {

        $("#export-menu").click(function(e){
            e.preventDefault();
            e.stopPropagation();
            $('#export').dropdown('show');
        });
    });

</script>
@endsection