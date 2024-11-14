@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Teacher Publication Type</div>
    </div>
@endsection
@section('title', 'Teacher Publication Type')
@section('icon', "list")
@section('subtitle', 'Teacher Publication Type')
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="sixteen wide column">
                <table class="ui selectable striped celled small table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody id="sortable">
                    @foreach($types as $key => $type)
                        <tr data-item="{{$type->id}}">
                            <td>{{$key+ $types->firstItem()}}</td>
                            <td> {{ $type->name }}</td>
                            <td>
                                <a href='{{ route('Laralum::teacher::publication::type::edit', ['type' => $type->id]) }}' class="item">
                                    <i class="edit icon"></i>Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <button class="ui green button" type="button" id="applyReOrder"><i class="icon list"></i>Update Order</button><br/><br/>
                {{ $types->links() }}
            </div>
        </div>

        <div class="row">
            <div class="sixteen wide column" id="vue-app">
                <form class="ui form" method="POST" action="{{ route('Laralum::teacher::publication::type::create') }}">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field required">
                            <label>Name</label>
                            <input type="text"  name="name" placeholder="Name..." required>
                        </div>
                        <br/>

                        <button type="submit" class="ui blue submit button">Save</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection

@section('js')

    @include('laralum.include.jquery-ui')
    @include('laralum.include.pnotify')
    @include('laralum.include.vue.vue-axios')

    <script type="text/javascript">

        $(document).ready( function() {
            $('td, th', '#sortable').each(function () {
                var cell = $(this);
                cell.width(cell.width());
            });

            $( "#sortable" ).sortable( {
                update: function( event, ui ) {
                    $(this).children().each(function(index) {
                        $(this).find('td').first().html(index + {{$types->firstItem()}})
                    });
                }
            });

            $("#applyReOrder").click(function(){
                var data = $("#sortable").sortable('toArray', {attribute: "data-item"});
                axios.get("{{route('Laralum::setting::reorder')}}", {
                    params: {
                        data : data,
                        model : 'App\\TeacherPublicationType',
                        field : 'sorting_order',
                        orderStart : {{$types->firstItem()}}
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
