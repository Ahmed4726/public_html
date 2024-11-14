@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <div class="active section">Student List</div>
</div>
@endsection
@section('title', 'Student List')
@section('icon', "list")
@section('subtitle', 'Student List')
@section('createButton')
<a href="{{route('Laralum::student::create')}}" class='large ui green right floated button white-text'>Create
    Student</a>
@endsection

@section('content')
<div class="ui one column doubling stackable grid container">

    <div class="column">
        <form>
            <div class="ui fluid action input container grid mb-0">
                <input type="text" placeholder="Search by name / department / registration / hall..."
                    name="filter[search]" value="{{ request('filter[search]') }}">

                <select class="ui compact dropdown two wide column" name="filter[admission_session_id]">
                    <option value="">Session</option>
                    @foreach($sessions as $session)
                    <option value="{{$session->id}}" @if(request('filter.admission_session_id')==$session->id)
                        selected
                        @endif>
                        {{$session->name}}
                    </option>
                    @endforeach
                </select>

                <select class="ui compact dropdown two wide column" name="filter[program_id]">
                    <option value="">Program</option>
                    @foreach($programs as $key => $program)
                    <option value="{{$key}}" @if(request('filter.program_id')==$key) selected @endif>
                        {{$program}}
                    </option>
                    @endforeach
                </select>

                <select class="ui compact dropdown two wide column" name="filter[department_id]">
                    <option value="">Department</option>
                    @foreach($departments as $department)
                    <option value="{{$department->id}}" @if(request('filter.department_id')==$department->id)
                        selected
                        @endif>
                        {{$department->name}}
                    </option>
                    @endforeach
                </select>

                <select class="ui compact dropdown two wide column" name="filter[hall_id]">
                    <option value="">Hall</option>
                    @foreach($halls as $hall)
                    <option value="{{$hall->id}}" @if(request('filter.hall_id')==$hall->id)
                        selected
                        @endif>
                        {{$hall->name}}
                    </option>
                    @endforeach
                </select>

                <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
            </div>
        </form>

        <a class="float-right" href="{{route('Laralum::student::list')}}">Clear Search</a>
    </div>

    <div class="column">
        <div class="ui padded segment" id="vue-app">

            <table class="ui selectable padded compact striped celled small table">
                <thead>
                    <tr>
                        <th>Session</th>
                        <th>Name</th>
                        <th>Program</th>
                        <th>Department</th>
                        <th>Hall</th>
                        <th>Roll</th>
                        <th>Registration</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr>
                        <td>{{ optional($student->admissionSession)->name }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{$student->program_name}}</td>
                        <td>{{ $student->department->short_name ?? '' }}</td>
                        <td>{{ $student->hall->name ?? '' }}</td>
                        <td>{{ $student->roll }}</td>
                        <td>{{ $student->registration }}</td>
                        <td>
                            <div class="ui blue top icon right floating pointing dropdown button">
                                <i class="cog icon"></i>
                                <div class="menu">
                                    <div class="header">Advanced Option</div>
                                    <a href='{{route("Laralum::student::edit", ['student' => $student->id])}}'
                                        class="item">
                                        <i class="edit icon"></i>Edit
                                    </a>
                                    <a href="/{{$student->id}}/delete" class="delete item">
                                        <i class="trash red alternate outline icon"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="row ui grid">
                <div class="twelve wide column">
                    {{ $students->appends(request()->all())->links() }}
                </div>
                <div class="four wide column right aligned">
                    Showing {{$students->firstItem()}} to {{$students->lastItem()}} of {{$students->total()}} entries
                </div>
            </div>
        </div>
    </div>
</div>

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
<script type="text/javascript">
    $(document).ready( function() {
        $('.delete').on("click", function (e) {
            e.preventDefault();
            var _this = $(this);
            $('.deleted.modal').modal({
                onApprove: function() {
                    window.location.href = "{{route('Laralum::student::list')}}"+_this.attr('href');
                }
            }).modal('show');
        });
    });

</script>
@endsection