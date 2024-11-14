@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <a class="section" href="{{ route('Laralum::internet-connection::list') }}">Internet Connection Apply List</a>
    <i class="right angle icon divider"></i>
    <div class="active section">Email Apply Edit</div>
</div>
@endsection
@section('title', 'Internet Connection Apply Edit')
@section('icon', "edit")
@section('subtitle', $connection->name)
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
                        <input type="text" name="name" placeholder="Name..." value="{{old('name', $connection->name)}}"
                            required>
                    </div>

                    <x-admin.faculty-or-office :faculties="$faculties" :model="$connection" />

                    {{-- <div class="field required">
                        <label>Designation</label>
                        <select name="employee_type_id" required>
                            @foreach($types as $type)
                            <option value="{{ $type->id }}" @if( (old('employee_type_id', $connection->
                    employee_type_id)) == $type->id)
                    selected
                    @endif>
                    {{ $type->name }}
                    </option>
                    @endforeach
                    </select>
                </div>

                <div class="field">
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
                    <input type="text" name="other" placeholder="Other..." value="{{old('other', $connection->other)}}"
                        required>
                </div> --}}

                <div class="field required">
                    <label>Designation</label>
                    <input type="text" name="designation" placeholder="Designation..."
                        value="{{old('designation', $connection->designation)}}" required>
                </div>

                <div class="field required">
                    <label>Address</label>
                    <textarea name="address" id="address" required>{{old('address', $connection->address)}}</textarea>
                </div>

                <div class="field required">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Email..." value="{{old('email', $connection->email)}}"
                        required>
                </div>

                <div class="field required">
                    <label>Phone Number</label>
                    <input type="text" name="phone_no" placeholder="Phone Number..."
                        value="{{old('phone_no', $connection->phone_no)}}" required>
                </div>

                <div class="field ">
                    <label>Preferable Time</label>
                    <input type="text" id="preferable_time" name="preferable_time" placeholder="Preferable Time..."
                        value="{{old('preferable_time', $connection->preferable_time)}}">
                </div>

                <div class="field required">
                    <label>Status</label>
                    <select name="global_status_id" required>
                        @foreach($statuses as $status)
                        <option value="{{ $status->id }}" @if((old('global_status_id', $connection->
                            global_status_id)) ==
                            $status->id) selected @endif>
                            {{ $status->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="field ">
                    <label>Comment</label>
                    <textarea name="comment" id="comment">{{old('comment', $connection->comment)}}</textarea>
                </div>

                <br>
                <button type="submit" class="ui blue submit button">Update</button>
        </div>
        </form>
    </div>

</div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#preferable_time", {minDate: "today", enableTime: true, dateFormat: "Y-m-d H:i" });
</script>
@endsection