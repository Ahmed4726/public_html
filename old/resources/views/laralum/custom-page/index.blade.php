@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Custom Page List</div>
    </div>
@endsection
@section('title', 'Custom Page List')
@section('icon', "list")
@section('subtitle', 'Custom Page List')

@section('createButton')
    <a href="{{route('Laralum::custom::page::create')}}" class='large ui green right floated button white-text'>Create Custom Page</a>
@endsection

@section('content')

    <div class="ui one column doubling stackable grid container" id="vue-app" >

        <div class="column">
            <form>
                <div class="ui fluid action input">
                    <input type="text" placeholder="Search by title..." class="" name="search" value="{{ Request::get('search') }}">

                    <select class="ui selection dropdown" name="status">
                        <option value="">Please Select status</option>
                        <option value="1" @if(Request::get('status') == 1) selected @endif>Enable</option>
                        <option value="0" @if(Request::get('status') === 0) selected @endif>Disable</option>
                    </select>

                    <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
                </div>
            </form>
            <a class="float-right" href="{{route('Laralum::custom::page::list')}}">Clear Search</a>
        </div>

        <div class="column">
            <div class="ui very padded segment">

                <table class="ui selectable striped celled small table">
                    <thead>
                    <tr>
                        <th class="six wide">Title</th>
                        <th class="seven wide">URL</th>
                        <th class="three wide">Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($pages as $page)
                        <tr>
                            <td>
                                @if(!$page->enabled)
                                    <i data-position="top center" data-content="Disabled" class="pop orange warning icon"></i>
                                @else
                                    <i data-position="top center" data-content="Enabled" class="pop green checkmark icon"></i>
                                @endif
                                {{ $page->title }}
                            </td>
                            <td>
                                @if($page->slug)
                                    <b>Slug :</b> {{$page->slug}}<br/>
                                    <b>Slug URL:</b> <a href="{{url("custom/slug/$page->slug")}}"?>{{url("custom/slug/$page->slug")}}</a><br/>
                                @endif
                                <b>ID URL:</b> <a href="{{url("custom/slug/$page->id")}}"?>{{url("custom/slug/$page->id")}}</a><br/>
                            </td>
                            <td>
                                <a href="{{ route('Laralum::custom::page::edit', ['page' => $page->id]) }}" class="mini ui blue icon button">
                                    <i class="edit icon"></i> Edit
                                </a>
                                <a href="{{ route('Laralum::custom::page::delete', ['page' => $page->id]) }}" class="delete mini ui red icon button">
                                    <i class="delete icon"></i> Delete
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $pages->links() }}

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

        });

    </script>

@endsection