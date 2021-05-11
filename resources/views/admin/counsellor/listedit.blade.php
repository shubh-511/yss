@extends('admin.layouts.app')


@section('content')
@if (count($errors) > 0)
  <div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
       @foreach ($errors->all() as $error)
         <li>{{ $error }}</li>
       @endforeach
    </ul>
  </div>
@endif
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
                        <h4 class="modal-title">Edit List: #{{$list_data->user_id}}</h4>
                    </div>
                    <div class="modal-body">
                        <form action="{{url('login/counsellors/list/update',[$list_data->user_id])}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Listing Name:</label>
                                    <input type="text" class="form-control" value="{{$list_data->listing_name}}" name="listing_name" required placeholder="Listing Name"> 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Location:</label>
                                    <input type="text" id="autocomplete" class="form-control" value="{{$list_data->location}}" name="location" required placeholder="Location">
                                </div>
                            </div>
                            <input type="hidden" name="longitude" id="longitude" class="form-control">
                        <input type="hidden" id="latitude" name="latitude" class="form-control">
                        
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Contact Email Or Url:</label>
                                    <input type="text" class="form-control" value="{{$list_data->contact_email_or_url}}" name="contact_email_or_url" placeholder="Contact Email Or Url">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Listing Category:</label>
                                   <select name="listing_category" class="form-control ">
                                    @foreach($list_category as $key =>$category)
                                       <option value="{{$category->id}} " {{ ( $category->id == $list_data->listing_category) ? 'selected' : '' }}>{{$category->category_name}}</option>
                                       @endforeach
                                   </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Listing Region:</label>
                                  <select name="listing_region" class="form-control ">
                                       @foreach($list_region as $key =>$region)
                                       <option value="{{$region->id}}" {{ ($region->id == $list_data->listing_region) ? 'selected' : '' }}>{{$region->region_name}}</option>
                                       @endforeach
                                  </select>
                                   </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Listing label:</label>
                                  <select name="listing_label" class="form-control ">
                                       @foreach($list_label as $key => $label)
                                       <option value="{{$label->id}}" {{ ( $label->id == $list_data->listing_label) ? 'selected' : '' }}>{{$label->label_name}}</option>
                                       @endforeach
                                  </select>
                                   </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Status:</label>
                                  <select name="status" class="form-control ">
                                      
                                       <option value="1">Active</option>
                                       <option value="0">InActive</option>
                                
                                  </select>
                                   </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Website:</label>
                                    <input type="text" class="form-control" value="{{$list_data->website}}" name="website" required placeholder="Website">
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                  <label>Phone:</label>
                                    <input type="text" class="form-control" value="{{$list_data->phone}}" name="phone" required placeholder="Location">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Video Url:</label>
                                    <input type="text" class="form-control" value="{{$list_data->video_url}}" name="video_url" required placeholder="Location">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Description:</label>
                                  <textarea class="form-control " name="description" id="exampleFormControlTextarea2"  rows="3" placeholder="Description">{{$list_data->description}}</textarea>

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

   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    
        <script src='http://maps.googleapis.com/maps/api/js?v=3&sensor=false&amp;libraries=places&key=AIzaSyCzf8RXQS27SPKYkdq6UMdZV0JctvWNFv0'></script>

    <script>
        $(document).ready(function () {
            $("#latitudeArea").addClass("d-none");
            $("#longtitudeArea").addClass("d-none");
        });
    </script>
    <script>
        google.maps.event.addDomListener(window, 'load', initialize);

        function initialize() {
            var input = document.getElementById('autocomplete');
            var autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
                $('#latitude').val(place.geometry['location'].lat());
                $('#longitude').val(place.geometry['location'].lng());

                $("#latitudeArea").removeClass("d-none");
                $("#longtitudeArea").removeClass("d-none");
            });
        }
    </script>