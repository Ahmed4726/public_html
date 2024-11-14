@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route("Laralum::$uri::list") }}">{{ucfirst($uri)}} List</a>
        <i class="right angle icon divider"></i>
        <a class="section" href="{{ route("Laralum::$uri::advance", [$uri => $uriValue]) }}">Advanced Option</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Employee List</div>
    </div>
@endsection
@section('title', 'Employee List')
@section('icon', "list")
@section('subtitle', 'Employee List')

@section('createButton')
    <a href="{{route("Laralum::$uri::officer::create", [$uri => $uriValue])}}" class='large ui green right floated button white-text'>Create Employee</a>
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

                    <select class="ui  selection dropdown" name="type_id">
                        <option value="">Select Type</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" @if(Request::get('type_id') == $type->id) selected @endif>{{ $type->name }}</option>
                        @endforeach
                    </select>

                    <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
                </div>
            </form>
            <a class="float-right" href="{{route("Laralum::$uri::officer::list", [$uri => $uriValue])}}">Clear Search</a>
        </div>

        <div class="column">
            <div class="ui very padded segment">

                <table class="ui selectable striped celled small table">
                    <thead>
                    <tr>
                        @if($sortable)<th>#</th>@endif
                        <th class="four wide">Name</th>
                        <th class="two wide">Designation</th>
                        <th class="one wide">Status</th>
                        <th class="two wide">Email</th>
                        <th class="two wide">Section</th>
                        <th class="one wide">Type</th>
                        <th class="three wide">Options</th>
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
                                <a href="{{ route('Laralum::officer::edit', ['officer' => $officer->id]) }}" class="mini ui blue icon button"><i class="edit icon"></i> Edit</a>
                                <a href="{{ route('Laralum::officer::delete', ['officer' => $officer->id]) }}" class="delete mini ui red icon button"><i class="delete icon"></i> Delete</a>
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
        });

    </script>

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
