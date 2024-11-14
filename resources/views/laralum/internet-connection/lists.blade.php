@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <div class="active section">Internet Connection List</div>
</div>
@endsection
@section('title', 'Internet Connction List')
@section('icon', "list")
@section('subtitle', 'Internet Connction List')

@section('createButton')
<div class="ui green buttons right floated">
    <form action="{{route('Laralum::internet-connection::export')}}" method="get">
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
                        <input type="checkbox" name="columns[]" value="Address">
                        <label>Address</label>
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
                        <input type="checkbox" checked name="columns[]" value="Phone Number">
                        <label>Phone Number</label>
                    </div>
                </div>

                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" name="columns[]" value="Comment">
                        <label>Comment</label>
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
                        <input type="checkbox" name="columns[]" value="Rejected Comment">
                        <label>Rejected Comment</label>
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
                <input type="text" placeholder="Search by name/ email/ designation / phone/ address/ comment ..."
                    name="search" value="{{ request()->search }}">

                <input type="text" id="from" name="from_date" autocomplete="off" readonly placeholder="Search From Date" value="{{ Request::get('from_date') }}">
                <input type="text" id="to" name="to_date" autocomplete="off" readonly placeholder="Search To Date" value="{{ Request::get('to_date') }}">

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
                    @foreach($statuses as $status)
                    <option value="{{$status->id}}" @if(request()->global_status_id==$status->id) selected
                        @endif>{{$status->name}}</option>
                    @endforeach
                </select>

                <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
            </div>
        </form>

        <a class="float-right" href="{{route('Laralum::internet-connection::list')}}">Clear Search</a>
    </div>

    <div class="column">
        <div class="ui padded segment" id="internet-connetion">

            <table class="ui selectable padded compact striped celled small table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Deparment / Office</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Comment</th>
                        <th>Status</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($connections as $connection)
                    <tr>
                        <td>{{ $connection->name }}</td>
                        <td>{{ $connection->designation }}</td>
                        <td>
                            {{ $connection->facultyOrOffice() }}
                        </td>
                        <td>{{ $connection->address }}</td>
                        <td>{{ $connection->email }}</td>
                        <td>{{ $connection->phone_no }}</td>
                        <td>{{ $connection->comment }}</td>
                        <td>
                            <span class="ui 
                            {{
                            [
                                App\InternetConnection::$pending => 'teal',
                                App\InternetConnection::$completed => 'green',
                                App\InternetConnection::$rejected => 'red',
                            ][optional($connection->globalStatus)->name ?? App\InternetConnection::$pending]
                            }}
                            label">{{ optional($connection->globalStatus)->name }}</span>
                        </td>
                        <td>
                            <div class="ui blue top icon right floating pointing dropdown button">
                                <i class="cog icon"></i>
                                <div class="menu">
                                    <div class="header">Advanced Option</div>

                                    @can('ADMIN')
                                    <a href='{{route("Laralum::internet-connection::edit", ['connection' => $connection->id])}}'
                                        class="item">
                                        <i class="edit icon"></i>Edit
                                    </a>
                                    @endcan

                                    <a target="_blank"
                                        href='{{route("Laralum::internet-connection::detail", ['connection' => $connection->id])}}'
                                        class="item">
                                        <i class="envelope icon"></i>Detail
                                    </a>

                                    @if ($connection->isPending() && auth()->user()->can('INTERNET-CONNECTION-MANAGER'))
                                    <a href='#' style="color: red" class="item"
                                        @click.prevent="rejectModal('/{{$connection->id}}/mark-rejected')">
                                        <i class="x icon"></i>Mark Rejected
                                    </a>

                                    <a href='#' class="item"
                                        @click.prevent="completeModal('/{{$connection->id}}/mark-completed')">
                                    <!-- <a href="{{route('Laralum::internet-connection::completed', ['connection' => $connection->id])}}"
                                        class="item">//-->
                                        <i class="check icon"></i>Mark Completed
                                    </a>
                                    @endif

                                    <a target="_blank"
                                        href="{{route('Laralum::internet-connection::pdf', ['connection' => $connection->id])}}"
                                        class="item">
                                        <i class="download icon"></i> PDF Download
                                    </a>

                                    @if (($connection->isRejected() || $connection->isCompleted()) && auth()->user()->can('INTERNET-CONNECTION-MANAGER'))
                                    <a href="/{{$connection->id}}/delete" class="delete item"
                                        @click.prevent="deleteModal('/{{$connection->id}}/delete')">
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
                    {{ $connections->appends(request()->all())->links() }}
                </div>
                <div class="four wide column right aligned">
                    Showing {{$connections->firstItem()}} to {{$connections->lastItem()}} of {{$connections->total()}}
                    entries
                </div>
            </div>

            {{-- Completed Submit Modal Start --}}
            <form :action="completeUrl" method="get" class="ui mini completed modal">
                <div class="ui icon header">
                    <div class="ui input">
                        <input type="text" placeholder="Option Reason for Complete..." style="padding: .45em 1em;" name="comment"
                            v-model="comment">
                    </div>
                </div>

                <div class="actions">
                    <div class="ui negative button">
                        Cancel
                    </div>
                    <button class="ui positive right labeled icon button" type="submit" ><i
                            class="checkmark icon"></i>
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
@include('laralum.include.jquery-ui')
@include('laralum.include.vue.vue-axios')
<script type="text/javascript">
    new Vue({
        el : "#internet-connetion",
        data : {
            comment : '',
            completeUrl : "{{route('Laralum::internet-connection::list')}}",
            rejectUrl : "{{route('Laralum::internet-connection::list')}}",
            deleteUrl : "{{route('Laralum::internet-connection::list')}}",
        },

        methods : {
            completeModal(url) {
                this.comment = '';
                this.completeUrl += url;
                $('.completed.modal').modal('show');
            },

            rejectModal(url) {
                this.comment = '';
                this.rejectUrl += url;
                $('.rejected.modal').modal('show');
            },

            deleteModal(url) {
                this.deleteUrl += url;
                $('.delete.modal').modal('show');
            }
        }
    });

    $(document).ready( function() {

        $("#export-menu").click(function(e){
            e.preventDefault();
            e.stopPropagation();
            $('#export').dropdown('show');
        });

        $( "#from" ).datepicker({
                dateFormat: 'M dd, yy',
                changeMonth: true,
                changeYear: true,
                onClose: function( selectedDate ) {
                    $( "#to" ).datepicker( "option", "minDate", selectedDate );
                }
            });
        $( "#to" ).datepicker({
            dateFormat: 'M dd, yy',
            changeMonth: true,
            changeYear: true,
            onClose: function( selectedDate ) {
                $( "#from" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
    });

</script>
@endsection