@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <a class="section" href="{{ route('Laralum::employee-email::list') }}">Employee Email Apply List</a>
    <i class="right angle icon divider"></i>
    <div class="active section">Email Apply Edit</div>
</div>
@endsection
@section('title', 'Email Apply Edit')
@section('icon', "edit")
@section('subtitle', $email->name)
@section('content')

<div class="ui doubling stackable grid container">
    <div class="row">
        <div class="two wide column"></div>
        <div class="twelve wide column" id="employee-email">
            <form class="ui form" method="POST">
                <div class="ui very padded segment">
                    {{ csrf_field() }}

                    <div class="field required">
                        <label>Name</label>
                        <input type="text" name="name" placeholder="Name..." value="{{old('name', $email->name)}}"
                            required>
                    </div>

                    <div class="field required">
                        <label>Employee ID</label>
                        <input type="text" name="employee_id" placeholder="Employee ID..."
                            value="{{old('employee_id', $email->employee_id)}}" required>
                    </div>

                    <x-admin.faculty-or-office :faculties="$faculties" :model="$email" />

                    {{-- <div class="field required">
                        <label>Designation</label>
                        <select name="employee_type_id" required>
                            @foreach($types as $type)
                            <option value="{{ $type->id }}" @if( (old('employee_type_id', $email->employee_type_id))
                    == $type->id) selected @endif>
                    {{ $type->name }}
                    </option>
                    @endforeach
                    </select>
                </div>

                <div class="field required">
                    <label>Department / Office</label>
                    <select name="faculty_office" v-model="faculty_office" required @change="getFacultyOrOfficeList()">
                        @foreach (Helper::facutlyOrOffice() as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field" v-if="faculty_office == 'faculty'">
                    <label>Faculty</label>
                    <select name="faculty_id" v-model="faculty_id" @change="getDepartmentList()">
                        @foreach($faculties as $key => $faculty)
                        <option value="{{ $faculty->id }}">
                            {{ $faculty->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="field required" v-if="faculty_office == 'faculty'">
                    <label>Department</label>
                    <select name="department_id" required v-model="department_id">
                        <option v-for="(department, index) in departments.data" v-cloak :value="department.id">
                            @{{department.name}}
                        </option>
                    </select>
                </div>

                <div class="field required" v-if="faculty_office == 'institute'">
                    <label>Institute</label>
                    <select name="department_id" required v-model="department_id">
                        <option v-for="(department, index) in departments.data" v-cloak :value="department.id">
                            @{{department.name}}
                        </option>
                    </select>
                </div>

                <div class="field required" v-if="faculty_office == 'office'">
                    <label>Office</label>
                    <select name="office_id" required v-model="office_id">
                        <option v-for="office in offices" v-cloak :value="office.id">
                            @{{office.name}}
                        </option>
                    </select>
                </div>

                <div class="field required" v-if="faculty_office == 'other'">
                    <label>Other</label>
                    <input type="text" name="other" placeholder="Other..." value="{{old('other', $email->other)}}"
                        required>
                </div> --}}

                <div class="field required">
                    <label>Designation</label>
                    <input type="text" name="designation" placeholder="Designation..."
                        value="{{old('designation', $email->designation)}}" required>
                </div>

                <div class="field required">
                    <label>Phone Number</label>
                    <input type="text" name="phone_no" placeholder="Phone Number..."
                        value="{{old('phone_no', $email->phone_no)}}" required>
                </div>

                <div class="field required">
                    <label>Current Email</label>
                    <input type="email" name="current_email" placeholder="Current Email..."
                        value="{{old('current_email', $email->current_email)}}" required>
                </div>

                <div class="field required">
                    <label>Expected Email 1</label>
                    <div class="ui right labeled input">
                        <input type="text" name="expected_email_1" placeholder="Expected Email 1..."
                            value="{{old('expected_email_1', $email->expected_email_1)}}" required>
                        <div class="ui label">
                            {{$domain}}
                        </div>
                    </div>
                </div>

                <div class="field required">
                    <label>Expected Email 2</label>
                    <div class="ui right labeled input">
                        <input type="text" name="expected_email_2" placeholder="Expected Email 2..."
                            value="{{old('expected_email_2', $email->expected_email_2)}}">
                        <div class="ui label">
                            {{$domain}}
                        </div>
                    </div>
                </div>

                <div class="field required">
                    <label>Expected Email 3</label>
                    <div class="ui right labeled input">
                        <input type="text" name="expected_email_3" placeholder="Expected Email 3..."
                            value="{{old('expected_email_3', $email->expected_email_3)}}">
                        <div class="ui label">
                            {{$domain}}
                        </div>
                    </div>
                </div>

                <div class="field required">
                    <label>Password</label>
                    <input type="text" name="password" placeholder="Password..."
                        value="{{old('password', $email->password)}}" required>
                </div>

                <div class="field required">
                    <label>Status</label>
                    <select name="global_status_id" required>
                        @foreach($statuses as $status)
                        <option value="{{ $status->id }}" @if((old('global_status_id', $email->global_status_id)) ==
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