@extends('layouts.admin.panel')

{{-- @section('breadcrumb')
    <div class="ui breadcrumb">
        @if(isset($uri) && $uri == 'department')<a class="section" href="{{ route("Laralum::$uri::list") }}">{{ucfirst($uri)}} List</a>
        <i class="right angle icon divider"></i>
        <a class="section" href="{{ route("Laralum::$uri::advance", [$uri => $uriValue]) }}">Advanced Option</a>
        <i class="right angle icon divider"></i>@endif
        <div class="active section">All Notice List</div>
    </div>
@endsection --}}

@section('title', 'All Activities List')
@section('icon', "list")
@section('subtitle', 'All Activities List')

@section('createButton')
    <a href="{{ route('Laralum::faculty::facultyEventCreate', ['faculty' => $data['faculty_id']]) }}" class='large ui green right floated button white-text'>Create Activities</a>
   
@endsection

@section('content')

    <div class="ui one column doubling stackable grid container" id="vue-app" >
{{-- 
        <div class="column">
            <form>
                <div class="ui fluid action input">
                    <input type="text" placeholder="Search by title..." class="" name="search" value="{{ Request::get('search') }}">

                    <input type="text" id="from" name="from_date" autocomplete="off" readonly placeholder="Search From Date" value="{{ Request::get('from_date') }}">
                    <input type="text" id="to" name="to_date" autocomplete="off" readonly placeholder="Search To Date" value="{{ Request::get('to_date') }}">

                    <select class="ui selection dropdown" name="event_id">
                        <option value="">Please Select a type</option>
                        
                    </select>

                    <select class="ui compact selection dropdown" name="status">
                        <option value="">Please Select a status</option>
                        <option value="1" @if(Request::get('status') == 1) selected @endif>Enabled</option>
                        <option value="disable" @if(Request::get('status') == 'disable') selected @endif>Disabled</option>
                    </select>

                    <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
                </div>
            </form>
            @if(isset($uri)) <a class="float-right" href="{{route("Laralum::$uri::event::list", [$uri => $uriValue])}}">Clear Search</a>
            @else <a class="float-right" href="{{route('Laralum::event::list')}}">Clear Search</a> @endif
    </div> --}}

        <div class="column">
            <div class="ui very padded segment">

                <table class="ui selectable striped celled small table">
                    <thead>
                    <tr>
                        <th class="eight wide">Title</th>
                       
                        <th class="one wide">Type</th>
                        <th class="two wide">Date</th>
                        <th class="four wide">Options</th>
                    </tr>
                    </thead>
                    
                    <tbody>
                        @foreach($data['events'] as $event)
                        <tr>
                            <td>
                                @if(!$event->enabled)
                                <i data-position="top center" data-content="Disabled" class="pop orange warning icon"></i>
                            @else
                                <i data-position="top center" data-content="Enabled" class="pop green checkmark icon"></i>
                            @endif
                                {{ $event->title }}
                            </td>
                            <td>
                                {{ $event->event_name }}
                            </td>
                            <td>
                                {{ $event->publish_date }}
                            </td>
                            <td>
                                 <a href="{{ route('Laralum::faculty::facultyEventEdit', ['faculty' => $event->faculty_id, 'DiscussionID' => $event->id]) }}" class="mini ui blue icon button"><i class="edit icon"></i> Edit</a>
                                
                                <a href="{{ route('Laralum::faculty::facultyEventDelete', ['faculty' => $event->faculty_id, 'DiscussionID' => $event->id]) }}" class="delete mini ui red icon button">
                                    <i class="delete icon"></i> Delete
                                </a>
                                
                                
                            </td>
                        </tr>
                    @endforeach

                    {{-- @foreach($data as $facultydata)
                        <tr>
                            <td>
                                @if(!$facultydata->enabled)
                                    <i data-position="top center" data-content="Disabled" class="pop orange warning icon"></i>
                                @else
                                    <i data-position="top center" data-content="Enabled" class="pop green checkmark icon"></i>
                                @endif
                                {!! $facultydata->title !!}</td>
                            <td><label class="ui basic label">{{ $facultydata->title }}</label></td>
                            
                            <td>{{ $facultydata->publish_date }}</td>
                            <td>
                                <a href="{{ route('Laralum::event::edit', ['discussion' => $discussion->id]) }}" class="mini ui blue icon button"><i class="edit icon"></i> Edit</a>
                                <a href="{{ route('Laralum::event::upload', ['discussion' => $discussion->id]) }}" class="mini ui green icon button"><i class="upload icon"></i> Upload</a>
                                <a href="{{ route('Laralum::event::delete', ['discussion' => $discussion->id]) }}" class="delete mini ui red icon button"><i class="delete icon"></i> Delete</a>
                            </td>
                        </tr>
                    @endforeach --}}

                    </tbody>
                </table>

                {{-- {{ $discussions->links() }} --}}

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

    <script>
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

            $( "#from" ).datepicker({
                dateFormat: 'M dd, yy',
                changeMonth: true,
                changeYear: true,
                onClose: function( selectedDate ) {
                    $( "#to" ).datepicker( "option", "minDate", selectedDate );
                }
            });
            $( "#to" ).datepicker({
                dateFormat: 'M dd, yy',
                changeMonth: true,
                changeYear: true,
                onClose: function( selectedDate ) {
                    $( "#from" ).datepicker( "option", "maxDate", selectedDate );
                }
            });
        });
    </script>
@endsection
