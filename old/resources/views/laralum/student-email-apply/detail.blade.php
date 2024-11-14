@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <a class="section" href="{{ route('Laralum::student-email-apply::list') }}">Student Email Apply List</a>
    <i class="right angle icon divider"></i>
    <div class="active section">Email Apply Edit</div>
</div>
@endsection
@section('title', 'Email Apply Detail')
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
                        <div class=" text-right c-header">Admission Session</div>
                        <div class="c-value text-left">{{optional($email->admissionSession)->name}}</div>
                    </div>
                    <div class="flex">
                        <label class="text-right c-header">Registration Number</label>
                        <span class="c-value text-left">{{$email->registration_number}}</span>
                    </div>
                    <div class="flex">
                        <label class="text-right c-header">Student Name</label>
                        <span class="c-value text-left">{{$email->name}}</span>
                    </div>
                    <div class="flex">
                        <label class="text-right c-header">Email</label>
                        <span class="c-value text-left">{{$email->username}}@juniv.edu</span>
                    </div>
                    <div class="flex">
                        <label class="text-right c-header">Password</label>
                        <span class="c-value text-left">{{$email->password}}</span>
                    </div>
                    <div class="flex">
                        <label class="text-right c-header">Department</label>
                        <span class="c-value text-left">{{optional($email->department)->name}}</span>
                    </div>
                    <div class="flex">
                        <label class="text-right c-header">Program Name</label>
                        <span class="c-value text-left">{{optional($email->program)->name}}</span>
                    </div>
                    <div class="flex">
                        <label class="text-right c-header">Hall Name</label>
                        <span class="c-value text-left">{{optional($email->hall)->name}}</span>
                    </div>
                    <div class="flex">
                        <label class="text-right c-header">Contact Number</label>
                        <span class="c-value text-left">{{$email->contact_phone}}</span>
                    </div>
                    <div class="flex">
                        <label class="text-right c-header">Existing Email</label>
                        <span class="c-value text-left">{{$email->existing_email}}</span>
                    </div>
                    <div class="flex">
                        <label class="text-right c-header">Front-Side of ID Card</label>
                        <span class="c-value text-left">
                            <a href="{{asset("storage/image/student-email-apply/$email->image")}}" target="_blank">
                                <img src="{{asset("storage/image/student-email-apply/$email->image")}}" width="200px"
                                    height="200px" class="image-preview">
                            </a>
                        </span>
                    </div>

                    @if ($email->updated_by && auth()->user()->can('ADMIN'))
                    <div class="flex">
                        <label class="text-right c-header">Updated By</label>
                        <span class="c-value text-left">{{optional($email->updatedBy)->name}}</span>
                    </div>
                    <div class="flex">
                        <label class="text-right c-header">Updated At</label>
                        <span
                            class="c-value text-left">{{optional($email->completed_at)->format('Y-m-d h:i:s a')}}</span>
                    </div>
                    @endif
                </div>

                @if($email->isPending() && auth()->user()->can('STUDENT-EMAIL-MANAGE'))
                <div class="extra content centered ui grid" id="vue-app">
                    <div class="ui buttons">
                        <a href="#" class="ui red button"
                            @click.prevent="modalShow('/{{$email->id}}/mark-rejected')">Rejcted</a>
                        <div class="or"></div>
                        <a href='/{{$email->id}}/mark-checked' class="ui check positive button">Checked</a>
                    </div>

                    {{-- Rejected Submit Modal Start --}}
                    <form :action="url" method="get" class="ui mini rejected modal">
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

                    {{-- Check Confirmation Modal Start --}}
                    <div class="ui mini checked modal">
                        <div class="ui icon header">
                            <i class="archive icon"></i>
                            Are you sure you want to checked this item?
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
                    {{-- Check Confirmation Modal End --}}
                </div>

                @elseif($email->isChecked() && auth()->user()->can('STUDENT-EMAIL-MANAGE'))

                <a href="{{route("Laralum::student-email-apply::mark::completed", ['email' => $email->id])}}"
                    class="ui positive button">Completed</a>

                @elseif($email->isCompleted() && auth()->user()->can('STUDENT-EMAIL-MANAGE'))

                <a href="{{route("Laralum::student-email-apply::mark::completed", ['email' => $email->id, 'update' => false])}}"
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
                        <div class="c-value text-left">{{optional($email->created_at)->format('Y-m-d h:i:s a')}}
                        </div>
                    </div>

                    @if($email->isCompleted())
                    <div class="flex">
                        <label class=" text-right c-header">Checked At</label>
                        <span class="c-value text-left">{{optional($email->checked_at)->format('Y-m-d h:i:s a')}}</span>
                    </div>

                    <div class="flex">
                        <label class=" text-right c-header">Checked By</label>
                        <span class="c-value text-left">{{optional($email->checkedBy)->name}}</span>
                    </div>
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

                    @elseif($email->isChecked())
                    <div class="flex">
                        <label class=" text-right c-header">Checked At</label>
                        <span class="c-value text-left">{{optional($email->checked_at)->format('Y-m-d h:i:s a')}}</span>
                    </div>

                    <div class="flex">
                        <label class=" text-right c-header">Checked By</label>
                        <span class="c-value text-left">{{$email->checkedBy->name ?? 'Automatically'}}</span>
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
<script type="text/javascript">
    new Vue({
        el : "#vue-app",
        data : {
            comment : '',
            url : ""
        },
        
        methods : {
            modalShow(id) {
                this.comment = '';
                this.url = "{{route('Laralum::student-email-apply::list')}}"+id;
                $('.rejected.modal').modal('show');
            }
        }
    });

    $(document).ready( function() {
        $('.check').on("click", function (e) {
            e.preventDefault();
            var _this = $(this);
            $('.checked.modal').modal({
                onApprove: function() {
                    window.location.href = "{{route('Laralum::student-email-apply::list')}}"+_this.attr('href');
                }
            }).modal('show');
        });
    });
</script>
@endsection