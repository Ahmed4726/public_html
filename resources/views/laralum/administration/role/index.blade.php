@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Administrative Role List</div>
    </div>
@endsection
@section('title', 'Administrative Role List')
@section('icon', "list")
@section('subtitle', 'Administrative Role List')

@section('createButton')
    <a href="{{route('Laralum::administration::role::create')}}" class='large ui green right floated button white-text'>Create Administrative Role</a>
@endsection

@section('content')

    <div class="ui one column doubling stackable grid container" id="vue-app" >

        <div class="column">
            <div class="ui very padded segment">

                <table class="ui selectable striped celled small table">
                    <thead>
                    <tr>
                        @if($sortable)<th>#</th>@endif
                        <th>Name</th>
                        <th>Description</th>
                        <th>View</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody @if($sortable)id="sortable"@endif>
                    @foreach($roles as $key => $role)
                        <tr data-item="{{$role->id}}">
                            @if($sortable)<td>{{$key+ $roles->firstItem()}}</td>@endif
                            <td>{{ $role->name }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($role->description, 20) }}</td>
                            <td>
                                <label class="ui basic label">{{ $role->preview }}</label>
                                @if($role->preview == 'CUSTOM')
                                    <br/>
                                    Page URL : <strong>{{$role->page_url}}</strong>
                                @endif
                            </td>
                            <td>
                                <div class="ui blue top icon left pointing dropdown button">
                                    Edit
                                    <div class="menu">
                                        <div class="header">{{ trans('laralum.editing_options') }}</div>
                                        <a href="{{ route('Laralum::administration::role::edit', ['role' => $role->id]) }}" class="item">
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
                {{ $roles->links() }}

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
                            $(this).find('td').first().html(index + {{$roles->firstItem()}})
                        });
                    }
                });

                $("#applyReOrder").click(function(){
                    var data = $("#sortable").sortable('toArray', {attribute: "data-item"});
                    axios.get("{{route('Laralum::setting::reorder')}}", {
                        params: {
                            data : data,
                            model : 'App\\AdministrativeRole',
                            field : 'sorting_order',
                            orderStart : {{$roles->firstItem()}}
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
