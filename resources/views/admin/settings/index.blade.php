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
                        <form action="{{url('login/settings/update-commission')}}" method="post">
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