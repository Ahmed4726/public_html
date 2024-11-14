@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <a class="section" href="{{ route('Laralum::internet-complain::list') }}">Internet Complain List</a>
    <i class="right angle icon divider"></i>
    <div class="active section">Internet Complain Edit</div>
</div>
@endsection
@section('title', 'Internet Complain Edit')
@section('icon', "edit")
@section('subtitle', $complain->name)
@section('content')

<div class="ui doubling stackable grid container">
    <div class="row">
        <div class="two wide column"></div>
        <div class="twelve wide column">
            <form class="ui form" method="POST">
                <div class="ui very padded segment">
                    {{ csrf_field() }}

                    <div class="field required">
                        <label>Name</label>
                        <input type="text" name="name" placeholder="Name..." value="{{old('name', $complain->name)}}"
                            required>
                    </div>


                    <div id="internet-complain" class="field">

                        <div class="field required">
                            <label>User Type</label>
                            <select name="user_type_id" required v-model="user_type_id">
                                @foreach($userTypes as $type)
                                <option value="{{ $type->id }}">
                                    {{ $type->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field required" v-if="user_type_id!={{App\InternetComplain::studentID}}">
                            <label>Employee ID</label>
                            <input type="text" name="employee_id" placeholder="Employee ID..." required
                                value="{{old('employee_id', $complain->employee_id)}}">
                        </div>

                    </div>


                    <x-admin.faculty-or-office :faculties="$faculties" :model="$complain" />

                    {{-- <div class="field">
                        <label>Faculty</label>
                        <select name="faculty_id" v-model="faculty_id" @change="getDepartmentList()">
                            @foreach($faculties as $key => $faculty)
                            <option value="{{ $faculty->id }}">
                    {{ $faculty->name }}
                    </option>
                    @endforeach
                    </select>
                </div>

                <div class="field required">
                    <label>Department / Office</label>
                    <select name="department_id" required v-model="department_id">
                        <option v-for="(department, index) in departments.data" v-cloak :value="department.id">
                            @{{department.name}}
                        </option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="field required" v-if="department_id == 'other'">
                    <label>Other</label>
                    <input type="text" name="other" placeholder="Other..." value="{{old('other', $complain->other)}}"
                        required>
                </div> --}}

                <div class="field required">
                    <label>Complain Category</label>
                    <select name="internet_complain_category_id" required>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" @if ( old('internet_complain_category_id', $complain->
                            internet_complain_category_id) == $category->id)
                            selected
                            @endif>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label>Complain Details</label>
                    <textarea name="details">{{old('details', $complain->details)}}</textarea>
                </div>

                <div class="field required">
                    <label>Phone Number</label>
                    <input type="text" name="phone_no" placeholder="Phone Number..."
                        value="{{old('phone_no', $complain->phone_no)}}" required>
                </div>

                <div class="field required">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Email..." value="{{old('email', $complain->email)}}"
                        required>
                </div>

                <div class="field required">
                    <label>Status</label>
                    <select name="global_status_id" required>
                        @foreach($statuses as $status)
                        <option value="{{ $status->id }}" @if((old('global_status_id', $complain->global_status_id))
                            ==
                            $status->id) selected @endif>
                            {{ $status->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <br>
                <button type="submit" class="ui blue submit button">Update</button>
        </div>
        </form>
    </div>

</div>
</div>
@endsection

@push('js')

<script>
    new Vue({
        el : "#internet-complain",
        data : {
            user_type_id : "{{old('user_type_id', $complain->user_type_id)}}"
        },
    });
</script>

@endpush