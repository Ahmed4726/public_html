@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <a class="section" href="{{ route('Laralum::internet-complain::list') }}">Internet Complain List</a>
    <i class="right angle icon divider"></i>
    <div class="active section">Internet Complain Detail</div>
</div>
@endsection
@section('title', 'Internet Complain Detail')
@section('icon', "envelope")
@section('subtitle', $complain->name)
@section('content')

<div class="ui doubling stackable grid container">
    <div class="row">
        <div class="one wide column"></div>
        <div class="seven wide column">
            <div class="ui card full-width">
                <div class="content">
                    <div class="header pagination-centered">{{$complain->name}}</div>
                </div>

                <div class="content">

                    <div class="flex">
                        <label class="text-right c-header">Department / Office</label>
                        <span class="c-value text-left">{{$complain->facultyOrOffice()}}</span>
                    </div>

                    @if (App\InternetComplain::studentID != $complain->user_type_id)
                    <div class="flex">
                        <label class="text-right c-header">Employee ID</label>
                        <span class="c-value text-left">{{$complain->employee_id}}</span>
                    </div>
                    @endif

                    <div class="flex">
                        <label class="text-right c-header">Complain Category</label>
                        <span class="c-value text-left">{{$complain->internetComplainCategory->name}}</span>
                    </div>

                    <div class="flex">
                        <label class="text-right c-header">User Type</label>
                        <span class="c-value text-left">{{optional($complain->userType)->name}}</span>
                    </div>

                    <div class="flex">
                        <label class="text-right c-header">Complain Details</label>
                        <span class="c-value text-left">{{$complain->details}}</span>
                    </div>

                    <div class="flex">
                        <label class="text-right c-header">Phone Number</label>
                        <span class="c-value text-left">{{$complain->phone_no}}</span>
                    </div>

                    <div class="flex">
                        <label class="text-right c-header">Email</label>
                        <span class="c-value text-left">{{$complain->email}}</span>
                    </div>

                    @if ($complain->updated_by && auth()->user()->can('ADMIN'))
                    <div class="flex">
                        <label class="text-right c-header">Updated By</label>
                        <span class="c-value text-left">{{optional($complain->updatedBy)->name}}</span>
                    </div>
                    <div class="flex">
                        <label class="text-right c-header">Updated At</label>
                        <span class="c-value text-left">{{$complain->updated_at->format('Y-m-d h:i:s a')}}</span>
                    </div>
                    @endif
                </div>

                @if($complain->isAssign() && auth()->user()->can('INTERNET-STAFF'))

                <div class="extra content centered ui grid" id="internet-complain">

                    <div class="ui buttons">
                        <a href="#" class="ui red button"
                            @click.prevent="rejectModal('/{{$complain->id}}/mark-rejected')">Rejcted</a>
                        <div class="or"></div>
                        <a href='#' class="ui check positive button"
                            @click.prevent="assignModal('/{{$complain->id}}/assign-team')">
                            Assign Team
                        </a>
                    </div>

                    {{-- Assign Submit Modal Start --}}
                    <form :action="assignUrl" method="get" class="ui mini assign modal">
                        <div class="ui icon header">
                            <div class="ui input">
                                <select class="mr-2" name="team_id" style="padding: .2em .5em;">
                                    <option v-for="team in teams" :value="team.id">
                                        @{{team.name}}</option>
                                </select>
                                {{-- <input class="ml-2" type="text" placeholder="Reason for Reject..." style="padding: .45em 1em;"
                                                name="username" v-model="username" required> --}}
                            </div>
                        </div>

                        <div class="actions">
                            <div class="ui negative button">
                                Cancel
                            </div>
                            <button class="ui positive right labeled icon button" type="submit"><i
                                    class="checkmark icon"></i>
                                Assign
                            </button>
                        </div>
                    </form>
                    {{-- Assign Submit Modal End --}}

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

                @elseif($complain->isAssign() && auth()->user()->can('INTERNET-STAFF'))

                <a href="{{route("Laralum::internet-complain::success", ['complain' => $complain->id])}}"
                    class="ui positive button">Mark Solved</a>

                @elseif($complain->isSuccess() && auth()->user()->can('INTERNET-STAFF'))

                <a href="{{route("Laralum::internet-complain::success", ['complain' => $complain->id, 'only_notify' => 'true'])}}"
                    class="ui positive button">Re-Send Email</a>

                @endif
            </div>
        </div>

        <div class="six wide column">
            <div class="ui card full-width">
                <div class="content">
                    <div class="header pagination-centered">{{$complain->globalStatus->name}}</div>
                </div>
                <div class="content">

                    <div class="flex">
                        <div class=" text-right c-header">Applied At</div>
                        <div class="c-value text-left">{{$complain->created_at->format('Y-m-d h:i:s a')}}
                        </div>
                    </div>

                    @if($complain->isSuccess())
                    <div class="flex">
                        <label class=" text-right c-header">Solved At</label>
                        <span
                            class="c-value text-left">{{optional($complain->solved_at)->format('Y-m-d h:i:s a')}}</span>
                    </div>

                    <div class="flex">
                        <label class=" text-right c-header">Solved By</label>
                        <span class="c-value text-left">{{optional($complain->solvedBy)->name}}</span>
                    </div>

                    <div class="flex">
                        <label class=" text-right c-header">Solved Comment</label>
                        <span class="c-value text-left">{{$complain->solved_comment}}</span>
                    </div>

                    @elseif($complain->isAssign())
                    <div class="flex">
                        <label class=" text-right c-header">Assigned At</label>
                        <span
                            class="c-value text-left">{{optional($complain->assign_at)->format('Y-m-d h:i:s a')}}</span>
                    </div>

                    <div class="flex">
                        <label class=" text-right c-header">Assigned By</label>
                        <span class="c-value text-left">{{optional($complain->assignBy)->name}}</span>
                    </div>

                    <div class="flex">
                        <label class=" text-right c-header">Team</label>
                        <span class="c-value text-left">{{optional($complain->team)->name}}</span>
                    </div>

                    @elseif($complain->isReject())
                    <div class="flex">
                        <label class=" text-right c-header">Rejected At</label>
                        <span
                            class="c-value text-left">{{optional($complain->rejected_at)->format('Y-m-d h:i:s a')}}</span>
                    </div>

                    <div class="flex">
                        <label class=" text-right c-header">Rejected By</label>
                        <span class="c-value text-left">{{optional($complain->rejectedBy)->name}}</span>
                    </div>

                    <div class="flex">
                        <label class=" text-right c-header">Rejected Comment</label>
                        <span class="c-value text-left">{{$complain->rejected_comment}}</span>
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
@include('laralum.include.vue.internet-complain-modal')
@endsection