@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <div class="active section">Internet Complain Category</div>
</div>
@endsection
@section('title', 'Internet Complain Category')
@section('icon', "list")
@section('subtitle', 'Internet Complain Category')
@section('content')

<div class="ui doubling stackable grid container">
    <div class="row">
        <div class="sixteen wide column">
            <table class="ui selectable striped celled small table">
                <thead>
                    <tr>
                        @if($sortable)<th>#</th>@endif
                        <th>Name</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody @if($sortable)id="sortable" @endif>
                    @foreach($categories as $key => $category)
                    <tr data-item="{{$category->id}}">
                        @if($sortable)<td>{{$key+ $categories->firstItem()}}</td>@endif
                        <td> {{ $category->name }}</td>
                        <td>
                            <a href='{{ route('Laralum::internet-complain::category::edit', ['category' => $category->id]) }}'
                                class="mini ui icon blue button">
                                <i class="edit icon"></i>Edit
                            </a>

                            @can('ADMIN')<a href="/{{$category->id}}/delete" class="delete mini ui icon red button">
                                <i class="trash alternate outline icon"></i> Delete
                            </a>@endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @can('ADMIN') @if($sortable) <button class="ui green button" type="button" id="applyReOrder"><i
                    class="icon list"></i>Update Order</button><br /><br /> @endif @endcan
            {{ $categories->links() }}
        </div>
    </div>

    <div class="row">
        <div class="sixteen wide column" id="vue-app">
            <form class="ui form" method="POST" action="{{ route('Laralum::internet-complain::category::store') }}">
                <div class="ui very padded segment">
                    {{ csrf_field() }}

                    <div class="field required">
                        <label>Name</label>
                        <input type="text" name="name" placeholder="Name..." required>
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

<script type="text/javascript">
    $(document).ready( function() {

        $('.delete').on("click", function (e) {
            e.preventDefault();
            var _this = $(this);
        
            $('.mini.modal').modal({
                onApprove: function() {
                    window.location.href = "{{route('Laralum::internet-complain::category::list')}}"+_this.attr('href');
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
                    $(this).find('td').first().html(index + {{$categories->firstItem()}})
                });
            }
        });

        $("#applyReOrder").click(function(){
            var data = $("#sortable").sortable('toArray', {attribute: "data-item"});
            axios.get("{{route('Laralum::setting::reorder')}}", {
                params: {
                    data : data,
                    model : 'App\\InternetComplainCategory',
                    field : 'sorting_order',
                    orderStart : {{$categories->firstItem()}}
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