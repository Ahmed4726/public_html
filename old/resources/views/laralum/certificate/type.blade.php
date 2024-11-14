@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <div class="active section">NOC & GO Type</div>
</div>
@endsection
@section('title', 'NOC & GO Type')
@section('icon', "list")
@section('subtitle', 'NOC & GO Type')
@section('content')

<div class="ui doubling stackable grid container">
    <div class="row">
        <div class="sixteen wide column">
            <table class="ui selectable striped celled small table">
                <thead>
                    <tr>
                        <th class="eight wide">Name</th>
                        <th class="eight wide">Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($types as $type)
                    <tr>
                        <td> {{ $type->name }}</td>
                        <td>
                            <a href='{{ route('Laralum::certificate::type::edit', ['type' => $type->id]) }}'
                                class="mini ui blue icon button">
                                <i class="edit icon"></i> Edit
                            </a>
                            <a href="/{{$type->id}}/delete" class="delete mini ui icon red button">
                                <i class="trash alternate outline icon"></i> Delete
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="sixteen wide column" id="vue-app">
            <form class="ui form" method="POST" action="{{ route('Laralum::certificate::type::create') }}">
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

{{-- Script For Delete Interaction Start --}}
<script type="text/javascript">
    $(document).ready( function() {

            $('.delete').on("click", function (e) {
                e.preventDefault();
                var _this = $(this);

                $('.mini.modal').modal({
                    onApprove: function() {
                        window.location.href = "{{route('Laralum::certificate::type::list')}}"+_this.attr('href');
                    }
                }).modal('show');
            });

            $('.ui.dropdown').dropdown();
            $('.pop').popup();
        });

</script>
{{-- Script For Delete Interaction End --}}

@endsection