@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::center::list') }}">Centers / Offices List</a>
        <i class="right angle icon divider"></i>
        <a class="section" href="{{ route("Laralum::center::advance", ['center' => $center->id]) }}">Advanced Option</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Centers / Offices Uploaded Files List</div>
    </div>
@endsection
@section('title', 'Centers / Offices Uploaded Files List')
@section('icon', "list")
@section('subtitle', $center->name)

@section('createButton')
    <a href="{{route('Laralum::center::upload::create', ['center' => $center->id])}}" class='large ui green right floated button white-text'>Upload Center / Office File</a>
@endsection

@section('content')

    <div class="ui one column doubling stackable grid container" id="vue-app" >

        <div class="column">
            <form>
                <div class="ui fluid action input">
                    <input type="text" placeholder="Search by name..." class="" name="search" value="{{ Request::get('search') }}">
                    <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
                </div>
            </form>
            <a class="float-right" href="{{route('Laralum::center::upload::list', ['center'=>$center->id])}}">Clear Search</a>
        </div>

        <div class="column">
            <div class="ui very padded segment">

                <table class="ui selectable striped celled small table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th class="four wide">Name</th>
                        <th class="two wide">Center</th>
                        <th class="three wide">URL</th>
                        <th class="two wide">Date</th>
                        <th class="five wide">Options</th>
                    </tr>
                    </thead>
                    <tbody id="sortable">
                    @foreach($uploads as $key => $upload)
                        <tr data-item="{{$upload->id}}">
                            <td>{{$key+1}}</td>
                            <td>
                                @if(!$upload->listing_enabled)
                                    <i data-position="top center" data-content="Disabled" class="pop orange warning icon"></i>
                                @else
                                    <i data-position="top center" data-content="Enabled" class="pop green checkmark icon"></i>
                                @endif
                                    {{ $upload->name }}
                            </td>
                            <td> @if($upload->center()->exists()) {{ $upload->center->name }}  @endif </td>
                            <td><a href="{{ route("center::file::view", ['center' =>$center->id, 'file' => $upload->id]) }}">{{ route("center::file::view", ['center' =>$center->id, 'file' => $upload->id]) }}</a></td>
                            <td>{{$upload->created_on}}</td>
                            <td>
                                <a href="{{ route('Laralum::center::upload::edit', ['center' => $center->id, 'upload' => $upload->id]) }}" class="mini ui blue icon button"><i class="edit icon"></i> Edit</a>
                                <a href="{{ route("center::file::view", ['center' =>$center->id, 'file' => $upload->id]) }}" class="mini ui green icon button"><i class="download icon"></i> Download</a>
                                <a href="{{ route("Laralum::center::upload::delete", ['center' =>$center->id, 'upload' => $upload->id]) }}" class="delete mini ui red icon button"><i class="delete icon"></i> Delete</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <button class="ui green button" type="button" id="applyReOrder"><i class="icon list"></i>Update Order</button><br/><br/>
                {{ $uploads->links() }}
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
                        window.location.href = _this.attr('href');
                    }
                }).modal('show');
            });

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
                        model : 'App\\CenterFile',
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
