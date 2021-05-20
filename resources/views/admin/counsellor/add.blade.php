@extends('admin.layouts.app')

<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> -->

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2></h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ url('login/counsellors') }}"> Back</a>
        </div>
    </div>
</div>


<?php /*@if (count($errors) > 0)
  <div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
       @foreach ($errors->all() as $error)
         <li>{{ $error }}</li>
       @endforeach
    </ul>
  </div>
@endif
*/ ?>

<div class="row">
    <div class="col-xs-12">
    <!-- Default box -->

            <div class="box-header">
              
                <div class="modal-content">
                  <div class="modal-header">
                    
                    <h4 class="modal-title">Create Counsellor</h4>
                  </div>
                  <div class="modal-body">
                    
                    <form action="{{url('login/counsellors/store')}}" method="post" enctype="multipart/form-data">
                    <div class="row">
                        @csrf
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Counsellor name</label>
                              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{old('name')}}">
                              @error('name')
                                <p style="color:red">{{ $errors->first('name') }}</p>
                              @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Counsellor email</label>
                              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{old('email')}}">
                              @error('email')
                                <p style="color:red">{{ $errors->first('email') }}</p>
                              @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Password</label>
                              <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                              @error('password')
                                <p style="color:red">{{ $errors->first('password') }}</p>
                              @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Confirm Password</label>
                              <input type="password" name="confirm-password" class="form-control @error('confirm-password') is-invalid @enderror">
                              @error('confirm-password')
                                <p style="color:red">{{ $errors->first('confirm-password') }}</p>
                              @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Listing name</label>
                              <input type="text" name="listing_name" class="form-control @error('listing_name') is-invalid @enderror" value="{{old('listing_name')}}">
                               @error('listing_name')
                                <p style="color:red">{{ $errors->first('listing_name') }}</p>
                              @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Location</label>
                              <input type="text"  id="autocomplete" name="location" class="form-control @error('location') is-invalid @enderror" value="{{old('location')}}">
                              @error('location')
                                <p style="color:red">{{ $errors->first('location') }}</p>
                              @enderror
                            </div>
                        </div>
                        <input type="hidden" name="longitude" value="{{old('longitude')}}" id="longitude" class="form-control">
                        <input type="hidden" id="latitude" value="{{old('latitude')}}" name="latitude" class="form-control">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Contact Email/URL</label>
                              <input type="text" name="contact_email_or_url" value="{{old('contact_email_or_url')}}" class="form-control @error('contact_email_or_url') is-invalid @enderror">
                               @error('contact_email_or_url')
                                <p style="color:red">{{ $errors->first('contact_email_or_url') }}</p>
                              @enderror
                            </div>
                        </div>
                        
                          <div class="col-md-6">
                                <div class="form-group">
                                  <label>Category</label>
                                  <select name="listing_category" class="form-control @error('listing_category') is-invalid @enderror">
                                    <option>select</option>
                                       @foreach($list_category as $category)
                                       <option value="{{$category->id}}">{{$category->category_name}}</option>
                                       @endforeach
                                  </select>
                                   @error('listing_category')
                                <p style="color:red">{{ $errors->first('listing_category') }}</p>
                              @enderror
                                   </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Label</label>
                                  <select name="listing_label[]" class="form-control">
                                    <option>select</option>
                                       @foreach($list_label as $label)
                                       <option value="{{$label->id}}">{{$label->label_name}}</option>
                                       @endforeach
                                  </select>
                                   </div>
                            </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label>Region</label>
                                  <select name="listing_region" class="form-control @error('listing_region') is-invalid @enderror" value="{{old('listing_region')}}">
                                       <option>select</option>
                                       @foreach($list_region as $region)
                                       <option value="{{$region->id}}">{{$region->region_name}}</option>
                                       @endforeach
                                  </select>
                                   @error('listing_region')
                                <p style="color:red">{{ $errors->first('listing_region') }}</p>
                              @enderror
                                   </div>
                            </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Cover Image</label>
                              <input type="file" name="cover_img" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Gallery Image</label>
                              <input type="file" name="gallery_images[]"  class="form-control" multiple>
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                              <label>Website Url</label>
                              <input type="text" name="website"  value="{{old('website')}}" class="form-control @error('website') is-invalid @enderror">
                              @error('website')
                                <p style="color:red">{{ $errors->first('website') }}</p>
                              @enderror
                            </div>
                        </div>
                          
                         <div class="col-md-6">
                            <div class="form-group">
                              <label>Phone Number</label>
                              <input type="text" name="phone" value="{{old('phone')}}" class="form-control">
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                              <label>Video Url</label>
                              <input type="text" name="video_url" value="{{old('video_url')}}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Timezone</label>
                              <select name="timezone" id="coun-timezone-tags" value="{{old('timezone')}}" class="form-control @error('timezone') is-invalid @enderror"><option value="">Select</option><option value="Etc/GMT+12">(GMT-12:00) International Date Line West</option><option value="Pacific/Midway">(GMT-11:00) Midway Island, Samoa</option><option value="Pacific/Honolulu">(GMT-10:00) Hawaii</option><option value="US/Alaska">(GMT-09:00) Alaska</option><option value="America/Los_Angeles">(GMT-08:00) Pacific Time (US &amp; Canada)</option><option value="America/Tijuana">(GMT-08:00) Tijuana, Baja California</option><option value="US/Arizona">(GMT-07:00) Arizona</option><option value="America/Chihuahua">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option><option value="US/Mountain">(GMT-07:00) Mountain Time (US &amp; Canada)</option><option value="America/Managua">(GMT-06:00) Central America</option><option value="US/Central">(GMT-06:00) Central Time (US &amp; Canada)</option><option value="America/Mexico_City">(GMT-06:00) Guadalajara, Mexico City, Monterrey</option><option value="Canada/Saskatchewan">(GMT-06:00) Saskatchewan</option><option value="America/Bogota">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option><option value="US/Eastern">(GMT-05:00) Eastern Time (US &amp; Canada)</option><option value="US/East-Indiana">(GMT-05:00) Indiana (East)</option><option value="Canada/Atlantic">(GMT-04:00) Atlantic Time (Canada)</option><option value="America/Caracas">(GMT-04:00) Caracas, La Paz</option><option value="America/Manaus">(GMT-04:00) Manaus</option><option value="America/Santiago">(GMT-04:00) Santiago</option><option value="Canada/Newfoundland">(GMT-03:30) Newfoundland</option><option value="America/Sao_Paulo">(GMT-03:00) Brasilia</option><option value="America/Argentina/Buenos_Aires">(GMT-03:00) Buenos Aires, Georgetown</option><option value="America/Godthab">(GMT-03:00) Greenland</option><option value="America/Montevideo">(GMT-03:00) Montevideo</option><option value="America/Noronha">(GMT-02:00) Mid-Atlantic</option><option value="Atlantic/Cape_Verde">(GMT-01:00) Cape Verde Is.</option><option value="Atlantic/Azores">(GMT-01:00) Azores</option><option value="Africa/Casablanca">(GMT+00:00) Casablanca, Monrovia, Reykjavik</option><option value="Europe/London">(GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London</option><option value="Europe/Amsterdam">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option><option value="Europe/Belgrade">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option><option value="Europe/Brussels">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option><option value="Europe/Sarajevo">(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option><option value="Africa/Lagos">(GMT+01:00) West Central Africa</option><option value="Asia/Amman">(GMT+02:00) Amman</option><option value="Europe/Athens">(GMT+02:00) Athens, Bucharest, Istanbul</option><option value="Asia/Beirut">(GMT+02:00) Beirut</option><option value="Africa/Cairo">(GMT+02:00) Cairo</option><option value="Africa/Harare">(GMT+02:00) Harare, Pretoria</option><option value="Europe/Helsinki">(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option><option value="Asia/Jerusalem">(GMT+02:00) Jerusalem</option><option value="Europe/Minsk">(GMT+02:00) Minsk</option><option value="Africa/Windhoek">(GMT+02:00) Windhoek</option><option value="Asia/Kuwait">(GMT+03:00) Kuwait, Riyadh, Baghdad</option><option value="Europe/Moscow">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option><option value="Africa/Nairobi">(GMT+03:00) Nairobi</option><option value="Asia/Tbilisi">(GMT+03:00) Tbilisi</option><option value="Asia/Tehran">(GMT+03:30) Tehran</option><option value="Asia/Muscat">(GMT+04:00) Abu Dhabi, Muscat</option><option value="Asia/Baku">(GMT+04:00) Baku</option><option value="Asia/Yerevan">(GMT+04:00) Yerevan</option><option value="Asia/Kabul">(GMT+04:30) Kabul</option><option value="Asia/Yekaterinburg">(GMT+05:00) Yekaterinburg</option><option value="Asia/Karachi">(GMT+05:00) Islamabad, Karachi, Tashkent</option><option value="Asia/Calcutta">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option><option value="Asia/Calcutta">(GMT+05:30) Sri Jayawardenapura</option><option value="Asia/Katmandu">(GMT+05:45) Kathmandu</option><option value="Asia/Almaty">(GMT+06:00) Almaty, Novosibirsk</option><option value="Asia/Dhaka">(GMT+06:00) Astana, Dhaka</option><option value="Asia/Rangoon">(GMT+06:30) Yangon (Rangoon)</option><option value="Asia/Bangkok">(GMT+07:00) Bangkok, Hanoi, Jakarta</option><option value="Asia/Krasnoyarsk">(GMT+07:00) Krasnoyarsk</option><option value="Asia/Hong_Kong">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option><option value="Asia/Kuala_Lumpur">(GMT+08:00) Kuala Lumpur, Singapore</option><option value="Asia/Irkutsk">(GMT+08:00) Irkutsk, Ulaan Bataar</option><option value="Australia/Perth">(GMT+08:00) Perth</option><option value="Asia/Taipei">(GMT+08:00) Taipei</option><option value="Asia/Tokyo">(GMT+09:00) Osaka, Sapporo, Tokyo</option><option value="Asia/Seoul">(GMT+09:00) Seoul</option><option value="Asia/Yakutsk">(GMT+09:00) Yakutsk</option><option value="Australia/Adelaide">(GMT+09:30) Adelaide</option><option value="Australia/Darwin">(GMT+09:30) Darwin</option><option value="Australia/Brisbane">(GMT+10:00) Brisbane</option><option value="Australia/Canberra">(GMT+10:00) Canberra, Melbourne, Sydney</option><option value="Australia/Hobart">(GMT+10:00) Hobart</option><option value="Pacific/Guam">(GMT+10:00) Guam, Port Moresby</option><option value="Asia/Vladivostok">(GMT+10:00) Vladivostok</option><option value="Asia/Magadan">(GMT+11:00) Magadan, Solomon Is., New Caledonia</option><option value="Pacific/Auckland">(GMT+12:00) Auckland, Wellington</option><option value="Pacific/Fiji">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option><option value="Pacific/Tongatapu">(GMT+13:00) Nuku'alofa</option></select>
                              @error('timezone')
                                <p style="color:red">{{ $errors->first('timezone') }}</p>
                              @enderror
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                              <label>Description</label>
                              <textarea class="form-control ckeditor @error('description') is-invalid @enderror" value="{{old('description')}}" name="description"></textarea>
                              @error('description')
                                <p style="color:red">{{ $errors->first('description') }}</p>
                              @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                                <div class="form-group">
                                  <label>Counsellor Type</label>
                                  <select name="counsellor_type" class="form-control ">
                                    <option>select</option>
                                       <option value="0">Inside Counsellor</option>
                                       <option value="1">Outside Counsellor</option>
                                  </select>
                                   </div>
                            </div>

                        <div class="col-md-12">
                          <div class="form-group">
                            <button style="float: right;" name="addcounsellor" type="submit" class="btn btn-primary ">Save</button>
                          </div>
                        </div>
                        
                  </div>
                </form>

                  </div>

                      <div class="modal-footer">
                        
                      </div>
                 
                </div>

                          

            </div>
        
    </div>
</div>


@endsection
<script type="text/javascript">
    function update_status(id,value)
    {     
        if(confirm("Are you sure you want to proceed with the refund?"))
        {
            $.ajax({
            type: 'GET',
            data: {'id':id,'value':value},
            url: "../../tickets/updateTicketStatus",
            success: function(result){
              //alert( 'Update Action Completed.');
              location.reload();

            }});
        }
      
    }
</script>
@push('select2')

<script type="text/javascript">
    $("#coun-timezone-tags").select2({
  tags: false
});
</script>
@endpush

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
    <script type="text/javascript">
    $(document).ready(function() {
       $('.ckeditor').ckeditor();
    });
</script>