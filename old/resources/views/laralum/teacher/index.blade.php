@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    @if(isset($uri)) <a class="section" href="{{ route("Laralum::$uri::list") }}">{{ucfirst($uri)}} List</a>
    <i class="right angle icon divider"></i>
    <a class="section" href="{{ route("Laralum::$uri::advance", [$uri => $uriValue]) }}">Advanced Option</a>
    <i class="right angle icon divider"></i>@endif
    <div class="active section">Teacher List</div>
</div>
@endsection
@section('title', 'Teacher List')
@section('icon', "list")
@section('subtitle', 'Teacher List')

@can('ADMIN')
@section('createButton')

<div class="float-right">

    <a href="{{route('Laralum::teacher::create')}}" class='large ui green button white-text'>
        Create Teacher
    </a>

    <div class="large ui green buttons">
        <form action="{{route('Laralum::teacher::export')}}" method="get" target="_blank">
            <div class="ui floating dropdown icon button" id="export">
                <i class="dropdown icon"></i>
                <div class="menu" id="export-menu">

                    <div class="item">
                        <div class="ui checkbox">
                            <input type="checkbox" name="headings[]" checked value="Name">
                            <label>Name</label>
                        </div>
                    </div>
                    <div class="item">
                        <div class="ui checkbox">
                            <input type="checkbox" name="headings[]" checked value="Email">
                            <label>Email</label>
                        </div>
                    </div>
                    <div class="item">
                        <div class="ui checkbox">
                            <input type="checkbox" name="headings[]" checked value="Additional Email">
                            <label>Additional Email</label>
                        </div>
                    </div>
                    <div class="item">
                        <div class="ui checkbox">
                            <input type="checkbox" name="headings[]" checked value="Phone">
                            <label>Phone</label>
                        </div>
                    </div>
                    <div class="item">
                        <div class="ui checkbox">
                            <input type="checkbox" name="headings[]" checked value="Status">
                            <label>Status</label>
                        </div>
                    </div>
                    <div class="item">
                        <div class="ui checkbox">
                            <input type="checkbox" name="headings[]" checked value="Designation">
                            <label>Designation</label>
                        </div>
                    </div>
                    <div class="item">
                        <div class="ui checkbox">
                            <input type="checkbox" name="headings[]" checked value="Department">
                            <label>Department</label>
                        </div>
                    </div>
                    <div class="item">
                        <div class="ui checkbox">
                            <input type="checkbox" name="headings[]" checked value="Image">
                            <label>Image</label>
                        </div>
                    </div>

                    @foreach (array_filter(request()->all()) as $key => $value)
                    <input type="hidden" name="{{$key}}" value="{{$value}}">
                    @endforeach
                </div>
            </div>
            <button type="submit" class="ui button">Export</button>
        </form>
    </div>

</div>

@endsection
@endcan

@section('content')

<div class="ui one column doubling stackable grid container" id="vue-app">
    @can('DEPARTMENT')
    <div class="column">
        <form>
            <div class="ui fluid action input container grid mb-0">
                <input type="text" placeholder="Search by name / email..." name="search"
                    value="{{ Request::get('search') }}">

                <select class="ui compact dropdown" name="status">
                    <option value="">--- Status ---</option>
                    @foreach($statuses as $status)
                    <option value="{{$status->id}}" @if(Request::get('status')==$status->id) selected
                        @endif>{{$status->name}}</option>
                    @endforeach
                    <option value="inactive" @if(Request::get('status')=='inactive' ) selected @endif>Inactive</option>
                </select>

                @can('ADMIN')
                <select class="ui dropdown" name="faculty_id" v-model="faculty_id" @change="getDepartmentList()">
                    <option value="">--- Faculty ---</option>
                    <option v-for="faculty in faculties" v-cloak :value="faculty.id">
                        @{{faculty.name}}
                    </option>
                </select>

                <select class="ui dropdown" name="department_id" v-model="department_id">
                    <option value="">--- Department ---</option>
                    <option v-for="department in departments" v-cloak :value="department.id">
                        @{{department.name}}
                    </option>
                </select>
                @endcan

                <select class="ui compact dropdown" name="designation_id">
                    <option value="">--- Designation ---</option>
                    @foreach($designations as $designation)
                    <option value="{{$designation->id}}" @if(Request::get('designation_id')==$designation->id) selected
                        @endif>{{$designation->name}}</option>
                    @endforeach
                </select>

                <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
            </div>
        </form>

        @if(isset($uri)) <a class="float-right"
            href="{{route("Laralum::$uri::teacher::list", [$uri => $uriValue])}}">Clear Search</a>
        @else <a class="float-right" href="{{route('Laralum::teacher::list')}}">Clear Search</a> @endif
    </div>
    @endcan

    <div class="column">
        <div class="ui very padded segment">

            <table class="ui selectable striped celled small table">
                <thead>
                    <tr>
                        @if($sortable)<th>#</th>@endif
                        <th class="four wide">Name</th>
                        <th class="two wide">Email</th>
                        <th class="one wide">Status</th>
                        <th class="one wide">Designation</th>
                        <th class="two wide">Department</th>
                        @if(!isset($uri))<th class="five wide">Options</th>@endif
                    </tr>
                </thead>
                <tbody @if($sortable)id="sortable" @endif>
                    @foreach($teachers as $key => $teacher)
                    <tr data-item="{{$teacher->id}}">
                        @if($sortable)<td>{{$key+ $teachers->firstItem()}}</td>@endif
                        <td>{{ $teacher->name }}</td>
                        <td>{{ $teacher->email }}</td>
                        <td>
                            @if($teacher->statusInfo()->exists())
                            <label
                                class="ui label @if($teacher->status == 1) basic @elseif($teacher->status == 2) black @elseif($teacher->status == 3) orange @elseif($teacher->status == 4) yellow @endif">
                                {{ $teacher->statusInfo->name }}
                                @elseif($teacher->status == 0)
                                <label class="ui red label"> Inactive
                                    @endif
                                </label>
                        </td>
                        <td>@if($teacher->designationInfo()->exists()){{ $teacher->designationInfo->name }}@endif</td>
                        <td> @if($teacher->department()->exists()) {{ $teacher->department->name }} @endif </td>
                        @if(!isset($uri))
                        <td>
                            <a href="{{ route('Laralum::teacher::edit', ['teacher' => $teacher->id]) }}"
                                class="mini ui blue icon button"><i class="edit icon"></i> Edit</a>
                            <a href="{{ route('Laralum::teacher::advance', ['teacher' => $teacher->id]) }}"
                                class="mini ui green icon button"><i class="setting icon"></i> Advanced Option</a>
                            @can('ADMIN')
                            <a href="/{{$teacher->id}}/delete" class="delete mini ui icon red button">
                                <i class="trash alternate outline icon"></i> Delete
                            </a>
                            @endcan
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @can('DEPARTMENT') @if($sortable) <button class="ui green button" type="button" id="applyReOrder"><i
                    class="icon list"></i>Update Order</button><br /><br /> @endif @endcan
            <div class="row ui grid">
                <div class="twelve wide column">
                    {{ $teachers->appends(request()->all())->links() }}
                </div>
                <div class="four wide column right aligned">
                    Showing {{$teachers->firstItem()}} to {{$teachers->lastItem()}} of {{$teachers->total()}} entries
                </div>
            </div>

        </div>
        <br>
    </div>
</div>

{{-- Delete Confirmation Modal Start --}}
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
{{-- Delete Confirmation Modal End --}}
@endsection

@section('css')
<style>
    .mini {
        margin: 1px !important;
    }
</style>
@endsection

@section('js')

@include('laralum.include.vue.vue-axios')

<script type="text/javascript">
    new Vue({
        el : "#vue-app",
        data : {
            departments : {},
            department_id: "{{request()->department_id}}",
            faculties : @json($faculties),
            faculty_id: "{{request()->faculty_id}}"
        },
        methods : {
            getDepartmentList() {
                this.$http.get("{{ route('Frontend::department::search') }}", {
                    params: {
                        faculty_id : this.faculty_id,
                    }
                }).
                then(({data}) => this.departments = data.data);
            },
        },
        created() {
            this.getDepartmentList();
        }
    });

    // {{-- Script For Delete Interaction Start --}}
    $(document).ready( function() {
        $('.delete').on("click", function (e) {
            e.preventDefault();
            var _this = $(this);

            $('.mini.modal').modal({
                onApprove: function() {
                    window.location.href = "{{route('Laralum::teacher::list')}}"+_this.attr('href');
                }
            }).modal('show');
        });

        $('.ui.dropdown').dropdown();
        $('.pop').popup();
    });
    // {{-- Script For Delete Interaction End --}}

</script>


@if($sortable)
@include('laralum.include.jquery-ui')
@include('laralum.include.pnotify')

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
                            $(this).find('td').first().html(index + {{$teachers->firstItem()}})
                        });
                    }
                });

                $("#applyReOrder").click(function(){
                    var data = $("#sortable").sortable('toArray', {attribute: "data-item"});
                    axios.get("{{route('Laralum::setting::reorder')}}", {
                        params: {
                            data : data,
                            model : 'App\\Teacher',
                            field : 'sorting_order',
                            orderStart : {{$teachers->firstItem()}}
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