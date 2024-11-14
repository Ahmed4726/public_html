@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <a class="section" href="{{ route('Laralum::internet-connection::list') }}">Internet Connection Apply List</a>
    <i class="right angle icon divider"></i>
    <div class="active section">Internet Connection Detail</div>
</div>
@endsection
@section('title', 'Internet Connection Detail')
@section('icon', "envelope")
@section('subtitle', $connection->name)
@section('content')

<div class="ui doubling stackable grid container">
    <div class="row">
        <div class="one wide column"></div>
        <div class="seven wide column">
            <div class="ui card full-width">
                <div class="content">
                    <div class="header pagination-centered">{{$connection->name}}</div>
                </div>

                <div class="content">

                    <div class="flex">
                        <div class=" text-right c-header">Designation</div>
                        <div class="c-value text-left">{{$connection->designation}}</div>
                    </div>

                    <div class="flex">
                        <label class="text-right c-header">Department / Office</label>
                        <span class="c-value text-left">{{$connection->facultyOrOffice()}}</span>
                    </div>

                    <div class="flex">
                        <label class="text-right c-header">Address</label>
                        <span class="c-value text-left">{{$connection->address}}</span>
                    </div>

                    <div class="flex">
                        <label class="text-right c-header">Email</label>
                        <span class="c-value text-left">{{$connection->email}}</span>
                    </div>

                    <div class="flex">
                        <label class="text-right c-header">Phone Number</label>
                        <span class="c-value text-left">{{$connection->phone_no}}</span>
                    </div>

                    <div class="flex">
                        <label class="text-right c-header">Comment</label>
                        <span class="c-value text-left">{{$connection->comment}}</span>
                    </div>

                    <div class="flex">
                        <label class="text-right c-header">Preferable Time</label>
                        <span class="c-value text-left">{{$connection->preferable_time}}</span>
                    </div>

                    @if ($connection->updated_by && auth()->user()->can('INTERNET-CONNECTION-MANAGER'))
                    <div class="flex">
                        <label class="text-right c-header">Updated By</label>
                        <span class="c-value text-left">{{optional($connection->updatedBy)->name}}</span>
                    </div>
                    <div class="flex">
                        <label class="text-right c-header">Updated At</label>
                        <span class="c-value text-left">{{$connection->updated_at->format('Y-m-d h:i:s a')}}</span>
                    </div>
                    @endif
                </div>

                @if($connection->isPending() && auth()->user()->can('INTERNET-CONNECTION-MANAGER'))

                <div class="extra content centered ui grid" id="internet-connection">

                    <div class="ui buttons">
                        <a href="#" class="ui red button"
                            @click.prevent="rejectModal('/{{$connection->id}}/mark-rejected')">Rejcted</a>
                        <div class="or"></div>
                        <a href="{{route('Laralum::internet-connection::completed', ['connection' => $connection->id])}}"
                            class="ui check positive button">
                            Completed
                        </a>
                    </div>

                    {{-- Rejected Submit Modal Start --}}
                    <form :action="rejectUrl" method="get" class="ui mini rejected modal">
                        <div class="ui icon header">
                            <div class="ui input">
                                <input type="text" placeholder="Reason for Reject..." style="padding: .45em 1em;"
                                    name="comment" v-model="comment" required>
                            </div>
                        </div>

                        <div class="actions">
                            <div class="ui negative button">
                                Cancel
                            </div>
                            <button class="ui positive right labeled icon button" type="submit" :disabled="!comment"><i
                                    class="checkmark icon"></i>
                                Reject
                            </button>
                        </div>
                    </form>
                    {{-- Rejected Submit Modal End --}}

                </div>

                @elseif($connection->isCompleted() && auth()->user()->can('INTERNET-CONNECTION-MANAGER'))

                <a href="{{route("Laralum::internet-connection::completed-notify", ['email' => $connection->id])}}"
                    class="ui positive button">Re-Send Email</a>

                @endif
            </div>
        </div>

        <div class="six wide column">
            <div class="ui card full-width">
                <div class="content">
                    <div class="header pagination-centered">{{$connection->globalStatus->name}}</div>
                </div>
                <div class="content">

                    <div class="flex">
                        <div class=" text-right c-header">Applied At</div>
                        <div class="c-value text-left">{{$connection->created_at->format('Y-m-d h:i:s a')}}
                        </div>
                    </div>

                    @if($connection->isCompleted())
                    <div class="flex">
                        <label class=" text-right c-header">Completed At</label>
                        <span
                            class="c-value text-left">{{optional($connection->completed_at)->format('Y-m-d h:i:s a')}}</span>
                    </div>

                    <div class="flex">
                        <label class=" text-right c-header">Completed By</label>
                        <span class="c-value text-left">{{optional($connection->completedBy)->name}}</span>
                    </div>

                    <div class="flex">
                        <label class=" text-right c-header">Comment</label>
                        <span class="c-value text-left">{{$connection->completed_comment}}</span>
                    </div>

                    @elseif($connection->isRejected())
                    <div class="flex">
                        <label class=" text-right c-header">Rejected At</label>
                        <span
                            class="c-value text-left">{{optional($connection->rejected_at)->format('Y-m-d h:i:s a')}}</span>
                    </div>

                    <div class="flex">
                        <label class=" text-right c-header">Rejected By</label>
                        <span class="c-value text-left">{{optional($connection->rejectedBy)->name}}</span>
                    </div>

                    <div class="flex">
                        <label class=" text-right c-header">Comment</label>
                        <span class="c-value text-left">{{$connection->rejected_comment}}</span>
                    </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('js')
@include('laralum.include.vue.vue-axios')

<script>
    new Vue({
    el : "#internet-connection",
    data : {
        comment : '',
        rejectUrl : "{{route('Laralum::internet-connection::list')}}",
        deleteUrl : "{{route('Laralum::internet-connection::list')}}",
    },
    
    methods : {
        rejectModal(url) {
            this.comment = '';
            this.rejectUrl += url;
            $('.rejected.modal').modal('show');
        },
        
        deleteModal(url) {
            this.deleteUrl += url;
            $('.delete.modal').modal('show');
        }
    }
});
</script>

@endsection