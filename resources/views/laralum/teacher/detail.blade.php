@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <a class="section" href="{{ route('Laralum::teacher::list') }}">Teacher List</a>
    <i class="right angle icon divider"></i>
    <div class="active section">Teacher Detail</div>
</div>
@endsection
@section('title', 'Teacher Detail')
@section('icon', "envelope")
@section('subtitle', $teacher->name)
@section('content')

<div class="ui doubling stackable grid container">
    <div class="row">
        <div class="one wide column"></div>
        <div class="seven wide column">
            <div class="ui card full-width">
                <div class="content">
                    <div class="header pagination-centered">{{$teacher->name}}</div>
                </div>

                <div class="content">

                    <div class="flex">
                        <div class=" text-right c-header">ID</div>
                        <div class="c-value text-left">{{$teacher->employee_id}}</div>
                    </div>

                    <div class="flex">
                        <div class=" text-right c-header">Designation</div>
                        <div class="c-value text-left">{{optional($teacher->designationInfo)->name}}</div>
                    </div>

                    <div class="flex">
                        <label class="text-right c-header">Department</label>
                        <span class="c-value text-left">{{optional($teacher->department)->name}}</span>
                    </div>

                    <div class="flex">
                        <label class="text-right c-header">Phone Number</label>
                        <span class="c-value text-left">{{$teacher->cell_phone}}</span>
                    </div>

                    <div class="flex">
                        <label class="text-right c-header">Research Interest</label>
                        <span class="c-value text-left">{{strip_tags($teacher->research_interest)}}</span>
                    </div>

                    <div class="flex">
                        <label class="text-right c-header">Email</label>
                        <span class="c-value text-left">{{$teacher->email ?: $teacher->additional_emails}}</span>
                    </div>

                    @if ($teacher->updated_by != -1 && auth()->user()->can('ADMIN'))
                    <div class="flex">
                        <label class="text-right c-header">Updated By</label>
                        <span class="c-value text-left">{{optional($teacher->updatedBy)->name}}</span>
                    </div>
                    <div class="flex">
                        <label class="text-right c-header">Updated At</label>
                        <span class="c-value text-left">{{$teacher->updated_on->format('Y-m-d h:i:s a')}}</span>
                    </div>
                    @endif

                    <div class="flex">
                        <label class="text-right c-header">Image</label>
                        <span class="c-value text-left">
                            <img src="{{asset("storage/image/teacher/$teacher->image_url")}}" class="image-preview">
                        </span>
                    </div>

                </div>

                @if($teacher->is_applied && $teacher->isPending() && auth()->user()->can('ADMIN'))

                <div class="extra content centered ui grid" id="teacherDetail">

                    <div class="ui buttons">
                        <a href="#" class="ui red button"
                            @click.prevent="rejectModal('/{{$teacher->id}}/mark-rejected')">Rejcted</a>

                        @if($teacher->email)
                        <div class="or"></div>
                        <a href="{{route('Laralum::teacher::mark-activate', ['teacher' => $teacher->id])}}"
                            class="ui check positive button">Activated</a>
                        @endif
                    </div>

                    {{-- Reject modal start --}}
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
                    {{-- Reject modal end --}}

                </div>

                @elseif($teacher->isActive() && auth()->user()->can('ADMIN'))

                <a href="{{route("Laralum::teacher::activate-notify", ['teacher' => $teacher->id])}}"
                    class="ui positive button">Re-Send Email</a>

                @endif
            </div>
        </div>

        <div class="six wide column">
            <div class="ui card full-width">
                <div class="content">
                    <div class="header pagination-centered">{{$teacher->statusInfo->name}}</div>
                </div>
                <div class="content">

                    <div class="flex">
                        <div class=" text-right c-header">Applied At</div>
                        <div class="c-value text-left">{{$teacher->created_on->format('Y-m-d h:i:s a')}}
                        </div>
                    </div>

                    @if($teacher->isActive())
                    <div class="flex">
                        <label class=" text-right c-header">Completed At</label>
                        <span
                            class="c-value text-left">{{optional($teacher->completed_at)->format('Y-m-d h:i:s a')}}</span>
                    </div>

                    <div class="flex">
                        <label class=" text-right c-header">Completed By</label>
                        <span class="c-value text-left">{{optional($teacher->activatedBy)->name}}</span>
                    </div>

                    @elseif($teacher->isRejected())
                    <div class="flex">
                        <label class=" text-right c-header">Rejected At</label>
                        <span
                            class="c-value text-left">{{optional($teacher->rejected_at)->format('Y-m-d h:i:s a')}}</span>
                    </div>

                    <div class="flex">
                        <label class=" text-right c-header">Rejected By</label>
                        <span class="c-value text-left">{{optional($teacher->rejectedBy)->name}}</span>
                    </div>

                    <div class="flex">
                        <label class=" text-right c-header">Comment</label>
                        <span class="c-value text-left">{{$teacher->comment}}</span>
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
        el : "#teacherDetail",
        data : {
            comment : '',
            rejectUrl : "{{route('Laralum::teacher::list')}}",
        },
        methods : {
            rejectModal(url) {
                this.comment = '';
                this.rejectUrl += url;
                $('.rejected.modal').modal('show');
            }
        }
    });
</script>
@endsection