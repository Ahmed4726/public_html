@extends('layouts.main')
@section('content')
    <div class="card-body" style="padding-bottom:0px;">
        <h3 style="text-align: center;">Reset Password</h3>
        <hr style="margin-bottom: 0px; padding-bottom: 0px;">
        <div class="row" style="padding: 15px;">
            <form style="width: 100%;" id="login_form" method="POST" action="{{ url('/password/reset') }}">
                {{ csrf_field() }}
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="col-12 col-sm-12 col-md-12 form-group">
                    <label>* Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Email..." value="{{ $email ?? old('email') }}" required autofocus>
                </div>

                <div class="col-12 col-sm-12 col-md-12 form-group">
                    <label>* Password</label>
                    <input id="pass" name='password' type="password" class="form-control" placeholder="Password..." required>
                </div>

                <div class="col-12 col-sm-12 col-md-12 form-group">
                    <label>* Password Confirmation</label>
                    <input id="pass" name='password_confirmation' type="password" class="form-control" placeholder="Password Confirmation..." required>
                </div>

                <div class="" style="text-align:center">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection