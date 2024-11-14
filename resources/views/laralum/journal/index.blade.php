@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Journal List</div>
    </div>
@endsection
@section('title', 'Journal List')
@section('icon', "list")
@section('subtitle', 'Journal List')

@section('createButton')
    <a href="{{route('Laralum::journal::create')}}" class='large ui green right floated button white-text'>Create Journal</a>
@endsection

@section('content')

    <div class="ui one column doubling stackable grid container" id="vue-app" >

        <div class="column">
            <form>
                <div class="ui fluid action input">
                    <input type="text" placeholder="Search by name..." class="" name="search" value="{{ Request::get('search') }}">

                    @if(isset($departments)) <select class="ui selection dropdown" name="department_id">
                        <option value="">Please Select department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @if(Request::get('department_id') == $department->id) selected @endif>{{ $department->name }}</option>
                        @endforeach
                    </select> @endif

                    @if(isset($faculties)) <select class="ui selection dropdown" name="faculty_id">
                        <option value="">Please Select faculty</option>
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}" @if(Request::get('faculty_id') == $faculty->id) selected @endif>{{ $faculty->name }}</option>
                        @endforeach
                    </select> @endif


                    <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
                </div>
            </form>
            <a class="float-right" href="{{route('Laralum::journal::list')}}">Clear Search</a>
        </div>

        <div class="column">
            <div class="ui very padded segment">

                <table class="ui selectable striped celled small table">
                    <thead>
                    <tr>
                        <th class="four wide">Title</th>
                        <th class="four wide">Department</th>
                        <th class="four wide">Faculty</th>
                        <th class="five wide">Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($journals as $journal)
                        <tr>
                            <td>{{ $journal->title }}</td>
                            <td>
                                @if($journal->department_id && $journal->department()->exists())
                                    {{ $journal->department->name }}
                                @endif
                            </td>
                            <td>
                                @if($journal->faculty_id && $journal->faculty()->exists())
                                    {{ $journal->faculty->name }}
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('Laralum::journal::edit', ['journal' => $journal->id]) }}" class="mini ui blue icon button">
                                    <i class="edit icon"></i> Edit
                                </a>
                                <a href="{{ route('Laralum::journal::content::list', ['journal' => $journal]) }}" class="mini ui icon orange button">
                                    <i class="file icon"></i> Content
                                </a>
                                <a href="{{ route('Laralum::journal::delete', ['journal' => $journal]) }}" class="delete mini ui icon red button">
                                    <i class="delete icon"></i> Delete
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $journals->links() }}

            </div>
            <br>
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

            $('.ui.dropdown').dropdown();
            $('.pop').popup();
        });

    </script>

@endsection
