@extends('admin.layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2></h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ url('login/dashboard') }}"> Back</a>
        </div>
    </div>
</div>



@if(Session::has('err_message'))
<div class="alert alert-danger">
  <strong>{{ Session::get('err_message') }}</strong>
</div>
@endif

<div class="row">
    <div class="col-xs-12">
    <!-- Default box -->
        
            <div class="box-header">

                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">General settings</h4>
                    </div>
                    <div class="modal-body">
                        <form action="{{url('login/settings/update-commission')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Admin Commission:</label>
                                    <input type="text" class="form-control @error('admin_commission') is-invalid @enderror" value="{{$commission->admin_commission}}" name="admin_commission" required> 
                                    @error('admin_commission')
                                    <p style="color:red">{{ $errors->first('admin_commission') }}</p>
                                  @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Counsellor Commission:</label>
                                    <input type="text" class="form-control @error('counsellor_commission') is-invalid @enderror" value="{{$commission->counsellor_commission}}" name="counsellor_commission" required>
                                    @error('counsellor_commission')
                                    <p style="color:red">{{ $errors->first('counsellor_commission') }}</p>
                                  @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Stripe Secret:</label>
                                    <input type="text" class="form-control @error('stripe_secret') is-invalid @enderror" value="{{$commission->stripe_secret}}" name="stripe_secret" required> 
                                    @error('stripe_secret')
                                    <p style="color:red">{{ $errors->first('stripe_secret') }}</p>
                                  @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Stripe Public:</label>
                                    <input type="text" class="form-control @error('stripe_public') is-invalid @enderror" value="{{$commission->stripe_public}}" name="stripe_public" required>
                                    @error('stripe_public')
                                    <p style="color:red">{{ $errors->first('stripe_public') }}</p>
                                  @enderror
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                  <label>Email:</label>
                                    <input type="text" class="form-control" value="{{$commission->email}}" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Logo:</label>
                                    <input type="file" class="form-control @error('input_img') is-invalid @enderror"  value="{{$commission->logo_url}}"  name="input_img">
                                    @error('input_img')
                                    <p style="color:red">{{ $errors->first('input_img') }}</p>
                                  @enderror
                                </div>
                                <span><img src="{{ asset('logo/'.$commission['logo_url']) }}" width="50px" height="50px"></span>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Facebook Url:</label>
                                    <input type="text" class="form-control" value="{{$commission->fb_url}}" name="fb_url" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Google Url:</label>
                                    <input type="text" class="form-control" value="{{$commission->google_url}}" name="google_url" required>
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                  <label>Twitter Url:</label>
                                    <input type="text" class="form-control" value="{{$commission->twitter_url}}" name="twitter_url" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Pagination Value:</label>
                                    <input type="text" class="form-control @error('pagination_value') is-invalid @enderror" value="{{$commission->pagination_value}}" name="pagination_value" required>
                                    @error('pagination_value')
                                    <p style="color:red">{{ $errors->first('pagination_value') }}</p>
                                  @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group">
                                <button style="float:right;" name="editcounsellor" type="submit" class="btn btn-primary ">Update</button>
                              </div>
                            </div>
                            
                           
                        </div>
                        </form>
                    </div>
                </div>



                
                
            </div>
        
    </div>            
</div>


@endsection