@extends('admin.layouts.login-app')

@section('content')
{{ __('Login') }}



<form method="POST" action="{{ url('login/admin-login') }}">
                        @csrf
      <div class="form-group has-feedback">
        <label for="email">Username</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email" autofocus>
          
            <span class="invalid-feedback" role="alert" style="color:red">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <label for="password">Password</label>
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="current-password">
        
            <span class="invalid-feedback" role="alert" style="color:red">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

            <label class="form-check-label" for="remember">
                {{ __('Remember Me') }}
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">
                                    {{ __('Login') }}
                                </button>
        </div>
        <!-- /.col -->
      </div>
</form>

@if(Session::has('err_message'))
<div class="alert alert-danger">
  <strong>{{ Session::get('err_message') }}</strong>
</div>
@endif

@if (Route::has('password.request'))
    <a class="text-center" href="{{ route('password.request') }}">
        {{ __('Forgot Your Password?') }}
    </a></br>
@endif

@endsection


<style>
  
.login-page .login-logo {
    display: flex;
    flex-direction: column-reverse;
    margin-bottom: 0;
    max-width: 100%;
}
.login-page .login-box .login-logo img{
  margin-bottom: 0.2135em;
  margin: auto;
}
.login-page .login-box-body{
  padding: 5px 20px;
  color: #34558b;
  font-weight: 700;
}
.login-page .login-box-body .checkbox, .radio{
  margin-top: 0;
  margin-bottom: 1.5rem;
}
.login-page .login-box-body form {
    margin: .75rem auto;
}
.login-page .login-box-body .form-group{
  margin: 1.5rem auto;
}
.login-page .login-box-body form label{
  color: #6e6e6e;
}
.login-page .has-feedback label~.form-control-feedback{
  top: 29px;
}
.login-page .login-page {
    background: #f8f8f8;
}
html .login-page {
    background: #f8f8f8;
}
.login-page .login-box{
  width: 90%;
  max-width: 450px;
  background: #fff;
  padding: 1rem 3rem;
  text-align: center;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  margin: 0;
}
.login-page .login-box-body .form-control{
  height: 40.97px;
}
.login-page button.btn.btn-primary.btn-block.btn-flat {
    background: #26416d;
    padding: 0.678em;
}
.login-page .login-box-body .col-xs-8{
  width: 100%;
}
.login-page .login-box-body .col-xs-4{
  width: 100%;
}
.login-page .login-logo b {
    font-size: 1.17rem;
    font-weight: 700;
}
</style>

