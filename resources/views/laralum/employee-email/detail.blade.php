@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <a class="section" href="{{ route('Laralum::employee-email::list') }}">Employee Email Apply List</a>
    <i class="right angle icon divider"></i>
    <div class="active section">Employee Email Detail</div>
</div>
@endsection
@section('title', 'Employee Email Detail')
@section('icon', "envelope")
@section('subtitle', $email->name)
@section('content')

<div class="ui doubling stackable grid container">
    <div class="row">
        <div class="one wide column"></div>
        <div class="seven wide column">
            <div class="ui card full-width">
                <div class="content">
                    <div class="header pagination-centered">{{$email->name}}</div>
                </div>

                <div class="content">

                    <div class="flex">
                        <div class=" text-right c-header">ID</div>
                        <div class="c-value text-left">{{$email->employee_id}}</div>
                    </div>

                    <div class="flex">
                        <div class=" text-right c-header">Designation</div>
                        <div class="c-value text-left">{{$email->designation}}</div>
                    </div>

                    <div class="flex">
                        <label class="text-right c-header">Department / Office</label>
                        <span class="c-value text-left">{{$email->facultyOrOffice()}}</span>
                    </div>

                    <div class="flex">
                        <label class="text-right c-header">Phone Number</label>
                        <span class="c-value text-left">{{$email->phone_no}}</span>
                    </div>

                    <div class="flex">
                        <label class="text-right c-header">Current Email</label>
                        <span class="c-value text-left">{{$email->current_email}}</span>
                    </div>

                    <div class="flex">
                        <label class="text-right c-header">Expected Email 1</label>
                        <span
                            class="c-value text-left">{{$email->expected_email_1. App\EmployeeEmail::$emailDomain}}</span>
                    </div>

                    <div class="flex">
                        <label class="text-right c-header">Expected Email 2</label>
                        <span
                            class="c-value text-left">{{$email->expected_email_2. App\EmployeeEmail::$emailDomain}}</span>
                    </div>
                    <div class="flex">
                        <label class="text-right c-header">Expected Email 3</label>
                        <span
                            class="c-value text-left">{{$email->expected_email_3. App\EmployeeEmail::$emailDomain}}</span>
                    </div>

                    @if ($email->username)
                    <div class="flex">
                        <label class="text-right c-header">Username</label>
                        <span class="c-value text-left">{{$email->username . App\EmployeeEmail::$emailDomain}}</span>
                    </div>
                    @endif

                    <div class="flex">
                        <label class="text-right c-header">Password</label>
                        <span class="c-value text-left">{{$email->password}}</span>
                    </div>

                    @if ($email->updated_by && auth()->user()->can('ADMIN'))
                    <div class="flex">
                        <label class="text-right c-header">Updated By</label>
                        <span class="c-value text-left">{{optional($email->updatedBy)->name}}</span>
                    </div>
                    <div class="flex">
                        <label class="text-right c-header">Updated At</label>
                        <span class="c-value text-left">{{$email->updated_at->format('Y-m-d h:i:s a')}}</span>
                    </div>
                    @endif
                </div>

                @if($email->isPending() && auth()->user()->can('ADMIN'))

                <div class="extra content centered ui grid" id="employee-email">

                    <div class="ui buttons">
                        <a href="#" class="ui red button"
                            @click.prevent="rejectModal('/{{$email->id}}/mark-rejected')">Rejcted</a>
                        <div class="or"></div>
                        <a href='#' class="ui check positive button"
                            @click.prevent="completeModal(
                                            '/{{$email->id}}/mark-completed',
                                             ['{{$email->expected_email_1}}', '{{$email->expected_email_2}}', '{{$email->expected_email_3}}'])">Completed</a>
                    </div>

                    {{-- Completed Submit Modal Start --}}
                    <form :action="completeUrl" method="get" class="ui mini complete modal">
                        <div class="ui icon header">
                            <div class="ui input">
                                <select v-model="username" class="mr-2">
                                    <option v-for="currentUsername in currentAllUsernames" :value="currentUsername">
                                        @{{currentUsername}}</option>
                                </select>
                                <input class="ml-2" type="text" placeholder="Reason for Reject..."
                                    style="padding: .45em 1em;" name="username" v-model="username" required>
                            </div>
                        </div>

                        <div class="actions">
                            <div class="ui negative button">
                                Cancel
                            </div>
                            <button class="ui positive right labeled icon button" type="submit"><i
                                    class="checkmark icon"></i>
                                Complete
                            </button>
                        </div>
                    </form>
                    {{-- Completed Submit Modal End --}}

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

                @elseif($email->isCompleted() && auth()->user()->can('ADMIN'))

                <a href="{{route("Laralum::employee-email::completed-notify", ['email' => $email->id])}}"
                    class="ui positive button">Re-Send Email</a>

                @endif
            </div>
        </div>

        <div class="six wide column">
            <div class="ui card full-width">
                <div class="content">
                    <div class="header pagination-centered">{{$email->globalStatus->name}}</div>
                </div>
                <div class="content">

                    <div class="flex">
                        <div class=" text-right c-header">Applied At</div>
                        <div class="c-value text-left">{{$email->created_at->format('Y-m-d h:i:s a')}}
                        </div>
                    </div>

                    @if($email->isCompleted())
                    <div class="flex">
                        <label class=" text-right c-header">Completed At</label>
                        <span
                            class="c-value text-left">{{optional($email->completed_at)->format('Y-m-d h:i:s a')}}</span>
                    </div>

                    <div class="flex">
                        <label class=" text-right c-header">Completed By</label>
                        <span class="c-value text-left">{{optional($email->completedBy)->name}}</span>
                    </div>

                    @elseif($email->isRejected())
                    <div class="flex">
                        <label class=" text-right c-header">Rejected At</label>
                        <span
                            class="c-value text-left">{{optional($email->rejected_at)->format('Y-m-d h:i:s a')}}</span>
                    </div>

                    <div class="flex">
                        <label class=" text-right c-header">Rejected By</label>
                        <span class="c-value text-left">{{optional($email->rejectedBy)->name}}</span>
                    </div>

                    <div class="flex">
                        <label class=" text-right c-header">Comment</label>
                        <span class="c-value text-left">{{$email->comment}}</span>
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
@include('laralum.include.vue.employee-email-modal')
@endsection