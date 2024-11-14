@extends('layouts.main')
@section('content')
    <div class="card-body" style="padding-bottom:0px;">
        <h3 style="text-align: center;">Login</h3>
        <hr style="margin-bottom: 0px; padding-bottom: 0px;">
        <div class="row" style="padding: 15px;">
            <form style="width: 100%;" id="login_form" method="POST">
                {{ csrf_field() }}

                <div class="col-12 col-sm-12 col-md-12 form-group">
                    <label>* Email</label>
                    <input id="userid" name='email' type="email" class="form-control" placeholder="Email..." required autofocus>
                </div>

                <div class="col-12 col-sm-12 col-md-12 form-group">
                    <label>* Password</label>
                    <input id="pass" name='password' type="password" class="form-control" placeholder="Password" required>
                </div>

                <div class="col-12 col-sm-12 col-md-12 form-group">
                    <input type="checkbox" name="remember"> Remember Me<br>
                    <a class="small" href="{{ url('password/reset') }}"
                       style="color: #007bff">{{ trans('laralum.forgot_password') }}</a>
                </div>

                <div class="" style="text-align:center">
                    <button type="submit" class="btn btn-success">Login</button>
                </div>
            </form>
        </div>
    </div>
@endsection