@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <div class="active section">Internet Complain List</div>
</div>
@endsection
@section('title', 'Internet Complain List')
@section('icon', "list")
@section('subtitle', 'Internet Complain List')

@section('createButton')

<a href="{{route('Laralum::internet-complain::create')}}" class='ui green button white-text right floated'>
    New Complain
</a>

<div class="ui green buttons right floated">
    <form action="{{route('Laralum::internet-complain::export')}}" method="get">
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
                        <input type="checkbox" checked name="columns[]" value="Department / Office">
                        <label>Department / Office</label>
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
                        <input type="checkbox" checked name="columns[]" value="Complain Category">
                        <label>Complain Category</label>
                    </div>
                </div>

                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" checked name="columns[]" value="User Type">
                        <label>User Type</label>
                    </div>
                </div>

                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" checked name="columns[]" value="Complain Details">
                        <label>Complain Details</label>
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
                        <input type="checkbox" checked name="columns[]" value="Email">
                        <label>Email</label>
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
                        <input type="checkbox" name="columns[]" value="Team">
                        <label>Team</label>
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
                        <input type="checkbox" name="columns[]" value="Solved By">
                        <label>Solved By</label>
                    </div>
                </div>

                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" name="columns[]" value="Solved At">
                        <label>Solved At</label>
                    </div>
                </div>

                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" name="columns[]" value="Assigned By">
                        <label>Assigned By</label>
                    </div>
                </div>

                <div class="item">
                    <div class="ui checkbox">
                        <input type="checkbox" name="columns[]" value="Assigned At">
                        <label>Assigned At</label>
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
                <input type="text" placeholder="Search by name/ ID/ details/ phone/ email ..." name="search"
                    value="{{ request()->search }}">

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
            </div>

            <div class="ui fluid action input container grid mb-0">
                <input type="text" placeholder="Search by applied at date range ..." id="range" name="range"
                    value="{{ request()->range }}">

                <select class="ui dropdown" name="internet_complain_category_id">
                    <option value="">--- Complain Category ---</option>
                    @foreach($categories as $category)
                    <option value="{{$category->id}}" @if(request()->internet_complain_category_id==$category->id)
                        selected
                        @endif>{{$category->name}}</option>
                    @endforeach
                </select>

                <select class="ui dropdown" name="user_type_id">
                    <option value="">--- User Type ---</option>
                    @foreach($types as $type)
                    <option value="{{$type->id}}" @if(request()->user_type_id==$type->id) selected
                        @endif>{{$type->name}}</option>
                    @endforeach
                </select>

                <select class="ui dropdown" name="team_id">
                    <option value="">--- Team ---</option>
                    @foreach($teams as $team)
                    <option value="{{$team->id}}" @if(request()->team_id==$team->id) selected
                        @endif>{{$team->name}}</option>
                    @endforeach
                </select>



                <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
            </div>

        </form>

        <a class="float-right" href="{{route('Laralum::internet-complain::list')}}">Clear Search</a>
    </div>

    <div class="column">
        <div class="ui padded segment" id="internet-complain">

            <table class="ui selectable padded compact striped celled small table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Deparment / Office</th>
                        <th>Type</th>
                        <th>Employee ID</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($complains as $complain)
                    <tr>
                        <td>{{ $complain->name }}</td>
                        <td>{{ $complain->facultyOrOffice() }}</td>
                        <td>{{ $complain->userType->name ?? '' }}</td>
                        <td>{{ $complain->employee_id }}</td>
                        <td>{{ $complain->internetComplainCategory->name }}</td>
                        <td>{{ optional($complain->userType)->name }}</td>
                        <td>{{ $complain->phone_no }}</td>
                        <td>{{ $complain->email }}</td>
                        <td>
                            <span class="ui 
                            {{
                            [
                                App\InternetComplain::initial => 'teal',
                                App\InternetComplain::success => 'green',
                                App\InternetComplain::reject => 'red',
                                App\InternetComplain::assign => 'purple',
                            ][optional($complain->globalStatus)->name ?? App\EmployeeEmail::initial]
                            }}
                            label">{{ optional($complain->globalStatus)->name }}</span>
                            @if (optional($complain->globalStatus)->name == App\InternetComplain::assign)
                            <div class="ui pointing purple basic label">
                                {{$complain->team->name ?? ''}}
                            </div>
                            @endif

                        </td>
                        <td>

                            <div class="ui blue top icon right floating pointing dropdown button">
                                <i class="cog icon"></i>
                                <div class="menu">
                                    <div class="header">Advanced Option</div>
                                    @can('ADMIN')
                                    <a href='{{route("Laralum::internet-complain::edit", ['complain' => $complain->id])}}'
                                        class="item">
                                        <i class="edit icon"></i>Edit
                                    </a>
                                    @endcan
                                    <a target="_blank"
                                        href='{{route("Laralum::internet-complain::detail", ['complain' => $complain->id])}}'
                                        class="item">
                                        <i class="envelope icon"></i>Detail
                                    </a>

                                    @if (auth()->user()->can('INTERNET-STAFF') && !$complain->isReject())
                                    <a href='#' style="color: red" class="item"
                                        @click.prevent="rejectModal('/{{$complain->id}}/mark-rejected')">
                                        <i class="x icon"></i>Mark Rejected
                                    </a>
                                    @endif

                                    @if($complain->isInitial())
                                    <a href="#" class="item"
                                        @click.prevent="assignModal('/{{$complain->id}}/assign-team')">
                                        <i class="user circle icon"></i>Assign team
                                    </a>
                                    @endif

                                    @if ($complain->isAssign() && auth()->user()->can('INTERNET-STAFF'))
                                    <a href='#' class="item"
                                        @click.prevent="solveModal('/{{$complain->id}}/mark-success')">
                                    <!-- <a href='{{route("Laralum::internet-complain::success", ['complain' => $complain->id])}}'
                                        class="item"> //-->
                                        <i class="check icon"></i>Mark Solved
                                    </a>
                                    @endif

                                    @if (($complain->isReject() || $complain->isSolved()) && auth()->user()->can('INTERNET-MANAGER'))
                                    <a href="/{{$complain->id}}/delete" class="delete item"
                                        @click.prevent="deleteModal('/{{$complain->id}}/delete')">
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
                    {{ $complains->appends(request()->all())->links() }}
                </div>
                <div class="four wide column right aligned">
                    Showing {{$complains->firstItem()}} to {{$complains->lastItem()}} of {{$complains->total()}} entries
                </div>
            </div>

            {{-- Assign Submit Modal Start --}}
            <form :action="assignUrl" method="get" class="ui mini assign modal">
                <div class="ui icon header">
                    <div class="ui input">
                        <select class="mr-2" name="team_id" style="padding: .2em .5em;">
                            <option v-for="team in teams" :value="team.id">
                                @{{team.name}}</option>
                        </select>
                        {{-- <input class="ml-2" type="text" placeholder="Reason for Reject..." style="padding: .45em 1em;"
                            name="username" v-model="username" required> --}}
                    </div>
                </div>

                <div class="actions">
                    <div class="ui negative button">
                        Cancel
                    </div>
                    <button class="ui positive right labeled icon button" type="submit"><i class="checkmark icon"></i>
                        Assign
                    </button>
                </div>
            </form>
            {{-- Assign Submit Modal End --}}

            {{-- Solved Submit Modal Start --}}
            <form :action="solveUrl" method="get" class="ui mini solved modal">
                <div class="ui icon header">
                    <div class="ui input">
                        <input type="text" placeholder="Optional Reason for Solved..." style="padding: .45em 1em;" name="comment"
                            v-model="comment">
                    </div>
                </div>

                <div class="actions">
                    <div class="ui negative button">
                        Cancel
                    </div>
                    <button class="ui positive right labeled icon button" type="submit"><i
                            class="checkmark icon"></i>
                        Solved
                    </button>
                </div>
            </form>
            {{-- Solved Submit Modal End --}}

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
@include('laralum.include.flatpickr')
@include('laralum.include.vue.vue-axios')
@include('laralum.include.vue.internet-complain-modal')
<script type="text/javascript">
    $(document).ready( function() {

        flatpickr("#range", {mode: "range"});

        $("#export-menu").click(function(e){
            e.preventDefault();
            e.stopPropagation();
            $('#export').dropdown('show');
        });
    });

</script>
@endsection