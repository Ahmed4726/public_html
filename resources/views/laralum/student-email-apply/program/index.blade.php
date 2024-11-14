@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <div class="active section">Program</div>
</div>
@endsection
@section('title', 'Program')
@section('icon', "list")
@section('subtitle', 'Program')
@section('content')

<div class="ui doubling stackable grid container">
    <div class="row">
        <div class="sixteen wide column">
            <table class="ui selectable striped celled small table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody id="sortable">
                    @foreach($programs as $program)
                    <tr data-item="{{$program->id}}">
                        <td> {{ $loop->iteration }}</td>
                        <td> {{ $program->name }}</td>
                        <td> {{ $program->slug }}</td>
                        <td>
                            <a href='{{ route('Laralum::student-email-apply::program::edit', ['program' => $program->id]) }}'
                                class="mini ui icon blue button">
                                <i class="edit icon"></i>Edit
                            </a>

                            <a href="/{{$program->id}}/delete" class="delete mini ui icon red button">
                                <i class="trash alternate outline icon"></i> Delete
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button class="ui green button" type="button" id="applyReOrder"><i class="icon list"></i>Update
                Order</button><br /><br />
        </div>
    </div>

    <div class="row">
        <div class="sixteen wide column" id="vue-app">
            <form class="ui form" method="POST" action="{{ route('Laralum::student-email-apply::program::store') }}">
                <div class="ui very padded segment">
                    {{ csrf_field() }}

                    <div class="field required">
                        <label>Name</label>
                        <input type="text" name="name" placeholder="Name..." required>
                    </div>

                    <div class="field">
                        <label>Slug</label>
                        <input type="text" name="slug" placeholder="Slug...">
                    </div>

                    <div class="inline field">
                        <div class="ui slider checkbox">
                            <input type="checkbox" name="verifyable" value="1" tabindex="0" class="hidden">
                            <label>Is Verifyable</label>
                        </div>
                    </div>

                    <br />

                    <button type="submit" class="ui blue submit button">Save</button>
                </div>
            </form>
        </div>
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

<script type="text/javascript">
    $(document).ready( function() {

        $('.delete').on("click", function (e) {
            e.preventDefault();
            var _this = $(this);
        
            $('.mini.modal').modal({
                onApprove: function() {
                    window.location.href = "{{route('Laralum::student-email-apply::program::list')}}"+_this.attr('href');
                }
            }).modal('show');
        });
        
        $('td, th', '#sortable').each(function () {
            var cell = $(this);
            cell.width(cell.width());
        });

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
                    model : 'App\\Program',
                    field : 'sorting_order',
                    orderStart : '1'
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