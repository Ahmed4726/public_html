@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <a class="section" href="{{ route('Laralum::internet-complain::list') }}">Create Internet Complain</a>
    <i class="right angle icon divider"></i>
    <div class="active section">Create Internet Complain</div>
</div>
@endsection
@section('title', 'Create Internet Complain')
@section('icon', "edit")
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
                        <input type="text" name="name" placeholder="Name..." required value="{{old('name')}}">
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
                                value="{{old('employee_id')}}">
                        </div>

                    </div>

                    <x-admin.faculty-or-office :faculties="$faculties" :model="new App\InternetComplain" />

                    <div class="field required">
                        <label>Complain Category</label>
                        <select name="internet_complain_category_id" required>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" @if ( old('internet_complain_category_id')==$category->
                                id)
                                selected
                                @endif>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label>Complain Details</label>
                        <textarea name="details">{{old('details')}}</textarea>
                    </div>

                    <div class="field required">
                        <label>Phone Number</label>
                        <input type="text" name="phone_no" placeholder="Phone Number..." value="{{old('phone_no')}}"
                            required>
                    </div>

                    <div class="field required">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Email..." value="{{old('email')}}" required>
                    </div>

                    <br>
                    <button type="submit" class="ui blue submit button">Create</button>
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
            user_type_id : "{{old('user_type_id')}}"
        },
    });
</script>

@endpush