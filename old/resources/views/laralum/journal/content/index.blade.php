@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::journal::list') }}">Journal List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Journal Content List</div>
    </div>
@endsection
@section('title', 'Journal Content List')
@section('icon', "list")
@section('subtitle', $journal->title)

@section('createButton')
    <a href="{{route('Laralum::journal::content::create', ['journal' => $journal->id])}}" class='large ui green right floated button'>Create Journal Content</a>
@endsection

@section('content')

    <div class="ui one column doubling stackable grid container" id="vue-app" >

        <div class="column">
            <form>
                <div class="ui fluid action input">
                    <input type="text" placeholder="Search by name, author, co-author..." class="" name="search" value="{{ Request::get('search') }}">
                    <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
                </div>
            </form>
            <a class="float-right" href="{{route('Laralum::journal::content::list', ['journal' => $journal->id])}}">Clear Search</a>
        </div>

        <div class="column">
            <div class="ui very padded segment">

                <table class="ui selectable striped celled small table">
                    <thead>
                    <tr>
                        @if($sortable)<th>#</th>@endif
                        <th>Title</th>
                        <th>Author</th>
                        <th>Co-Author</th>
                        <th>Volume</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody @if($sortable)id="sortable"@endif>
                    @foreach($contents as $key => $content)
                        <tr data-item="{{$content->id}}">
                            @if($sortable)<td>{{$key+ $contents->firstItem()}}</td>@endif
                            <td>{{ $content->title }}</td>
                            <td>{{$content->author}}</td>
                            <td>{{$content->co_author}} </td>
                            <td>{{$content->volume}} </td>
                            <td>
                                <a href="{{ route('Laralum::journal::content::edit', ['journal' => $journal->id, 'content' => $content->id]) }}" class="mini ui blue icon button">
                                    <i class="edit icon"></i> Edit
                                </a>
                                @if($content->path)
                                    <a href="{{route('Frontend::journal::content::download', ['content' => $content->id])}}" class="mini ui green icon button">
                                        <i class="download icon"></i> Download
                                    </a>
                                @endif
                                <a href="{{ route('Laralum::journal::content::delete', ['journal' => $journal->id, 'content' => $content->id]) }}" class="delete mini ui red icon button">
                                    <i class="delete icon"></i> Delete
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if($sortable) <button class="ui green button" type="button" id="applyReOrder"><i class="icon list"></i>Update Order</button><br/><br/> @endif
                {{ $contents->links() }}
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

    @if($sortable)
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
                            $(this).find('td').first().html(index + {{$contents->firstItem()}})
                        });
                    }
                });

                $("#applyReOrder").click(function(){
                    var data = $("#sortable").sortable('toArray', {attribute: "data-item"});
                    axios.get("{{route('Laralum::setting::reorder')}}", {
                        params: {
                            data : data,
                            model : 'App\\JournalContent',
                            field : 'sorting_order',
                            orderStart : {{$contents->firstItem()}}
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
