@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <div class="active section">Administrative Member List</div>
</div>
@endsection
@section('title', 'Administrative Member List')
@section('icon', "list")
@section('subtitle', 'Administrative Member List')

@section('createButton')
<a href="{{route('Laralum::administration::create')}}" class='large ui green right floated button white-text'>Create
    Administrative Member</a>
@endsection

@section('content')

<div class="ui one column doubling stackable grid container" id="vue-app">

    <div class="column">
        <div class="ui very padded segment">

            <table class="ui selectable striped celled small table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="five wide">Name</th>
                        <th class="two wide">Position</th>
                        <th class="three wide">Department</th>
                        <th class="two wide">Roles</th>
                        <th class="four wide">Options</th>
                    </tr>
                </thead>
                <tbody id="sortable">
                    @foreach($members as $key => $member)
                    <tr data-item="{{$member->id}}">
                        <td>{{$key+1}}</td>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->designation }}</td>
                        <td>{{ $member->department }}</td>
                        <td>
                            @foreach($member->roles->pluck('name') as $role)
                            {{$role}}<br />
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('Laralum::administration::edit', ['member' => $member->id]) }}"
                                class="mini ui blue icon button">
                                <i class="edit icon"></i>
                                Edit
                            </a>

                            <a href="{{ route('Laralum::administration::role::assign', ['member' => $member->id]) }}"
                                class="mini ui teal icon button">
                                <i class="star icon"></i>
                                Edit Role
                            </a>
                            <a href="/{{$member->id}}/delete" class="delete mini ui icon red button">
                                <i class="trash alternate outline icon"></i> Delete
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button class="ui green button" type="button" id="applyReOrder"><i class="icon list"></i>Update
                Order</button><br /><br />
            {{ $members->links() }}

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


@section('js')

@include('laralum.include.jquery-ui')
@include('laralum.include.pnotify')
@include('laralum.include.vue.vue-axios')

{{-- Script For Delete Interaction Start --}}
<script type="text/javascript">
    $(document).ready( function() {

            $('.delete').on("click", function (e) {
                e.preventDefault();
                var _this = $(this);

                $('.mini.modal').modal({
                    onApprove: function() {
                        window.location.href = "{{route('Laralum::administration::list')}}"+_this.attr('href');
                    }
                }).modal('show');
            });

            $('.ui.dropdown').dropdown();
            $('.pop').popup();
        });

</script>
{{-- Script For Delete Interaction End --}}

<script type="text/javascript">
    $(document).ready( function() {
            $('.ui.dropdown').dropdown();
            $('.pop').popup();

            $('td, th', '#sortable').each(function () {
                var cell = $(this);
                cell.width(cell.width());
            });

            // $( "#sortable" ).sortable().disableSelection();

            $( "#sortable" ).sortable( {
                update: function( event, ui ) {
                    $(this).children().each(function(index) {
                        $(this).find('td').first().html(index + 1)
                    });
                }
            });

            $("#applyReOrder").click(function(){
                var data = $("#sortable").sortable('toArray', {attribute: "data-item"});
                axios.get("{{route('Laralum::setting::reorder')}}", {
                    params: {
                        data : data,
                        model : 'App\\AdministrativeMember',
                        field : 'sorting_order'
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

@endsection