@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Officers / Staffs List</div>
    </div>
@endsection
@section('title', 'Officers / Staffs List')
@section('icon', "list")
@section('subtitle', 'Officers / Staffs List')

@section('createButton')
    <a href="{{route('Laralum::officer::create')}}" class='large ui green right floated button white-text'>Create Officers / Staffs</a>
@endsection

@section('content')

    <div class="ui one column doubling stackable grid container" id="vue-app" >

        <div class="column">
            <form>
                <div class="ui fluid action input">
                    <input type="text" placeholder="Search by name, designation, email..." class="" v-model="search" name="search" value="{{ Request::get('search') }}">

                    <select class="ui  selection dropdown" name="status">
                        <option value="">Select Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" @if(Request::get('status') == $status->id) selected @endif>{{ $status->name }}</option>
                        @endforeach
                        <option @if(Request::get('status') == 'inactive') selected @endif value="inactive">Inactive</option>
                    </select>

                    {{--<select class="ui selection dropdown" name="department_id">
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @if(Request::get('department_id') == $department->id) selected @endif>{{ $department->name }}</option>
                        @endforeach
                    </select>--}}

                    @if(Request::has('department_id')) <input type="hidden" name="department_id" value="{{Request::get('department_id')}}"> @endif

                    {{--<select class="ui selection dropdown" name="center_id">
                        <option value="">Select Center</option>
                        @foreach($centers as $center)
                            <option value="{{ $center->id }}" @if(Request::get('center_id') == $center->id) selected @endif>{{ $center->name }}</option>
                        @endforeach
                    </select>--}}

                    @if(Request::has('center_id')) <input type="hidden" name="center_id" value="{{Request::get('center_id')}}"> @endif

                    <select class="ui  selection dropdown" name="type_id">
                        <option value="">Select Type</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" @if(Request::get('type_id') == $type->id) selected @endif>{{ $type->name }}</option>
                        @endforeach
                    </select>

                    <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
                </div>
            </form>
            @can('ADMIN')<a class="float-right" href="{{route('Laralum::officer::list')}}">Clear Search</a>@endcan
        </div>

        <div class="column">
            <div class="ui very padded segment">

                <table class="ui selectable striped celled small table">
                    <thead>
                    <tr>
                        @if($sortable)<th>#</th>@endif
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Status</th>
                        <th>Email</th>
                        <th>Section</th>
                        <th>Type</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody @if($sortable)id="sortable"@endif>
                    @foreach($officers as $key => $officer)
                        <tr data-item="{{$officer->id}}">
                            @if($sortable)<td>{{$key+ $officers->firstItem()}}</td>@endif
                            <td> {{ $officer->name }}</td>
                            <td>{{ $officer->designation }}</td>
                            <td>
                                @if($officer->statusInfo()->exists())
                                    <label class="ui basic label">
                                        {{ $officer->statusInfo->name }}
                                        @elseif($officer->status == 0)
                                            <label class="ui red label"> Inactive
                                                @endif
                                    </label>
                            </td>
                            <td> {{ $officer->email }} </td>
                            <td>
                                @if($officer->department_id && $officer->department()->exists())
                                    {{ $officer->department->short_name }}
                                    <span class="ui mini label">
                                        Department
                                    </span>
                                @elseif($officer->center_id && $officer->center()->exists())
                                    {{ $officer->center->name }}
                                    <span class="ui mini label">
                                        Centers / Offices
                                    </span>
                                @endif
                            </td>
                            <td> @if($officer->typeInfo()->exists()) {{ $officer->typeInfo->name }} @endif </td>
                            <td>
                                <div class="ui blue top icon right pointing dropdown button">
                                    Edit
                                    <div class="menu">
                                        <div class="header">{{ trans('laralum.editing_options') }}</div>
                                        <a href="{{ route('Laralum::officer::edit', ['officer' => $officer->id]) }}" class="item">
                                            <i class="edit icon"></i>
                                            Basic Edit
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if($sortable) <button class="ui green button" type="button" id="applyReOrder"><i class="icon list"></i>Update Order</button><br/><br/> @endif
                {{ $officers->links() }}

            </div>
            <br>
        </div>
    </div>
@endsection

@section('js')

    @if($sortable)
        @include('laralum.include.jquery-ui')
        @include('laralum.include.pnotify')
        @include('laralum.include.vue.vue-axios')

        <script type="text/javascript">

            $(document).ready( function() {
                $('td, th', '#sortable').each(function () {
                    var cell = $(this);
                    cell.width(cell.width());
                });

                // $( "#sortable" ).sortable().disableSelection();

                $( "#sortable" ).sortable( {
                    update: function( event, ui ) {
                        $(this).children().each(function(index) {
                            $(this).find('td').first().html(index + {{$officers->firstItem()}})
                        });
                    }
                });

                $("#applyReOrder").click(function(){
                    var data = $("#sortable").sortable('toArray', {attribute: "data-item"});
                    axios.get("{{route('Laralum::setting::reorder')}}", {
                        params: {
                            data : data,
                            model : 'App\\Officer',
                            field : 'sorting_order',
                            orderStart : {{$officers->firstItem()}}
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
    @endif

@endsection
