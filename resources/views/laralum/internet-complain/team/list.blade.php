@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <div class="active section">Internet Complain Team</div>
</div>
@endsection
@section('title', 'Internet Complain Team')
@section('icon', "list")
@section('subtitle', 'Internet Complain Team')
@section('content')

<div class="ui doubling stackable grid container">
    <div class="row">
        <div class="sixteen wide column">
            <table class="ui selectable striped celled small table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teams as $key => $team)
                    <tr>
                        <td> {{ $team->name }}</td>
                        <td>
                            <a href='{{ route('Laralum::internet-complain::team::edit', ['team' => $team->id]) }}'
                                class="mini ui icon blue button">
                                <i class="edit icon"></i>Edit
                            </a>

                            @can('ADMIN')<a href="/{{$team->id}}/delete" class="delete mini ui icon red button">
                                <i class="trash alternate outline icon"></i> Delete
                            </a>@endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="sixteen wide column" id="vue-app">
            <form class="ui form" method="POST" action="{{ route('Laralum::internet-complain::team::store') }}">
                <div class="ui very padded segment">
                    {{ csrf_field() }}

                    <div class="field required">
                        <label>Name</label>
                        <input type="text" name="name" placeholder="Name..." required>
                    </div>

                    <div class="field">
                        <label>Internet Staff</label>
                        <select name="user_id">
                            <option value="">Please select a internet staff</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
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
                    window.location.href = "{{route('Laralum::internet-complain::team::list')}}"+_this.attr('href');
                }
            }).modal('show');
        });
        
    });
</script>
@endsection