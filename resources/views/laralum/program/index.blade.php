@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if(isset($uri)) <a class="section" href="{{ route("Laralum::$uri::list") }}">{{ucfirst($uri)}} List</a>
        <i class="right angle icon divider"></i>
        <a class="section" href="{{ route("Laralum::$uri::advance", [$uri => $uriValue]) }}">Advanced Option</a>
        <i class="right angle icon divider"></i>@endif
        <div class="active section">Program @if(!isset($uri))/ Calendar @endif List</div>
    </div>
@endsection
@section('title', 'Program / Calendar List')
@section('icon', "list")

@if(isset($uri))
    @section('subtitle', 'Program List')
@else
    @section('subtitle', 'Program / Calendar List')
@endif

@section('createButton')
    @if(isset($uri)) <a href="{{route("Laralum::$uri::program::create", [$uri => $uriValue])}}" class='large ui green right floated button white-text'>Create New Program</a>
    @else <a href="{{route('Laralum::program::create')}}" class='large ui green right floated button white-text'>Create Program / Calendar</a> @endif
@endsection

@section('content')

    <div class="ui one column doubling stackable grid container" id="vue-app" >

        <div class="column">
            <form>
                <div class="ui fluid action input">
                    <input type="text" placeholder="Search by name..." class="" v-model="search" name="search" value="{{ Request::get('search') }}">

                    @if(!isset($uri))
                        <select class="ui selection dropdown" name="type_id">
                            <option value="">Select Type</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" @if(Request::get('type_id') == $type->id) selected @endif>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    @endif

                    <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
                </div>
            </form>
            @if(isset($uri)) <a class="float-right" href="{{route("Laralum::$uri::program::list", [$uri => $uriValue])}}">Clear Search</a>
            @else <a class="float-right" href="{{route('Laralum::program::list')}}">Clear Search</a>@endif
        </div>

        <div class="column">
            <div class="ui very padded segment">

                <table class="ui selectable striped celled small table">
                    <thead>
                    <tr>
                        @if($sortable)<th>#</th>@endif
                        <th>Name</th>
                        <th>Type</th>
                        <th>Area</th>
                        @if(!isset($uri))<th>File URL</th>@endif
                        <th>URL</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody @if($sortable)id="sortable"@endif>
                    @foreach($programs as $key => $program)
                        <tr data-item="{{$program->id}}">
                            @if($sortable)<td>{{$key+ $programs->firstItem()}}</td>@endif
                            <td>
                                @if(!$program->enabled)
                                    <i data-position="top center" data-content="Disabled" class="pop orange warning icon"></i>
                                @else
                                    <i data-position="top center" data-content="Enabled" class="pop green checkmark icon"></i>
                                @endif
                                {{ $program->name }}
                            </td>
                            <td> @if($program->typeInfo()->exists()) {{ $program->typeInfo->name }} @endif </td>
                            <td>
                                @if($program->department_id && $program->department()->exists())
                                    {{ $program->department->short_name }}
                                    <span class="ui mini label">
                                        Department
                                    </span>
                                @elseif($program->center_id && $program->center()->exists())
                                    {{ $program->center->name }}
                                    <span class="ui mini label">
                                        Centers / Offices
                                    </span>
                                @elseif($program->hall_id && $program->hall()->exists())
                                    {{ $program->hall->name }}
                                    <span class="ui mini label">
                                        Hall
                                    </span>
                                @else
                                    <label class="ui basic label">Global</label>
                                @endif
                            </td>

                            @if(!isset($uri))
                            <td>
                                @if($program->path) <a href="{{route('program::file::view', ['program' => $program->id])}}">/program/{{$program->id}}/file </a>@endif
                            </td>
                            @endif

                            <td>
                                @if($program->external_link)
                                    <a href="{{$program->external_link}}">{{$program->external_link}}</a>
                                @else

                                    @if($program->center_id)
                                        @php
                                            $center = \App\Center::find($program->center_id)
                                        @endphp

                                        <a href="{{route("Frontend::".strtolower($center->type)."::program::view", ['center' =>$center->slug, 'program' => ($program->slug)? $program->slug : $program->id])}}">center/{{$center->slug}}/program/{{($program->slug) ? $program->slug : $program->id}}</a>

                                    @elseif($program->department_id)

                                        @php
                                            $department = \App\department::with('faculty')->find($program->department_id);
                                        @endphp

                                        @if($department->faculty()->exists() && $department->faculty->type == "FACULTY")
                                            <a href="{{route("Frontend::department::program::view", ['department' =>$department->slug, 'program' => ($program->slug)? $program->slug : $program->id])}}">department/{{$department->slug}}/program/{{($program->slug) ? $program->slug : $program->id}}</a>
                                        @else
                                            <a href="{{route("Frontend::institute::program::view", ['institute' =>$department->slug, 'program' => ($program->slug)? $program->slug : $program->id])}}">institute/{{$department->slug}}/program/{{($program->slug) ? $program->slug : $program->id}}</a>
                                        @endif

                                    @else <a href="{{route('Frontend::program::view', ['program' => ($program->slug)? $program->slug : $program->id])}}">program/{{($program->slug) ? $program->slug : $program->id}}</a>
                                    @endif

                                @endif
                            </td>
                            <td>
                                <a href="{{ route('Laralum::program::edit', ['program' => $program->id]) }}" class="mini ui blue icon button ">
                                    <i class="edit icon"></i> Edit
                                </a>
                                <a href="{{ route('Laralum::program::delete', ['program' => $program->id]) }}" class="delete mini ui red icon button ">
                                    <i class="delete icon"></i> Delete
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if($sortable) <button class="ui green button" type="button" id="applyReOrder"><i class="icon list"></i>Update Order</button><br/><br/> @endif
                {{ $programs->links() }}

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
                            $(this).find('td').first().html(index + {{$programs->firstItem()}})
                        });
                    }
                });

                $("#applyReOrder").click(function(){
                    var data = $("#sortable").sortable('toArray', {attribute: "data-item"});
                    axios.get("{{route('Laralum::setting::reorder')}}", {
                        params: {
                            data : data,
                            model : 'App\\DepartmentProgram',
                            field : 'sorting_order',
                            orderStart : {{$programs->firstItem()}}
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