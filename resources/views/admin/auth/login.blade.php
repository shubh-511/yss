@extends('admin.layouts.login-app')

@section('content')
{{ __('Login') }}



<form method="POST" action="{{ url('login/admin-login') }}">
                        @csrf
      <div class="form-group has-feedback">
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email" autofocus>
          
            <span class="invalid-feedback" role="alert" style="color:red">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
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

