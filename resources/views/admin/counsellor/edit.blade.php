@extends('admin.layouts.app')


@section('content')
<!-- <div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2></h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ url('login/counsellors') }}"> Back</a>
        </div>
    </div>
</div> -->

<div class="box-header">
    <div class="pull-left">
        <h3 class="box-title">Edit Counsellor: #{{$user->id}}</h3>
    </div>
    <div class="pull-right">
        <a class="btn btn-primary" href="{{ url('login/counsellors') }}"> Back</a>
    </div>
</div>


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
        <div class="box">
            <!-- <div class="box-header">
              <h3 class="box-title">Edit Counsellor: #{{$user->id}}</h3>
            </div> -->
            <div class="box-body">
              <form action="{{url('login/counsellors/update',[$user->id])}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Counsellor Type:</label>
                                   <select name="counsellor_type" class="form-control ">
                                       @if($user->counsellor_type==1)
                                        <option value="1" selected>Outside Counsellor</option>
                                        <option value="0">Inside Counsellor</option>
                                        @else
                                        <option value="1">Outside Counsellor</option>
                                        <option value="0" selected>Inside Counsellor</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Name:</label>
                                    <input type="text" class="form-control" value="{{$user->name}}" name="name" required placeholder="Name"> 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Email:</label>
                                    <input type="email" class="form-control" value="{{$user->email}}" disabled name="email" required placeholder="Email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>First Name:</label>
                                    <input type="text" class="form-control" value="{{$user->first_name}}" name="first_name" placeholder="First Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Middle Name:</label>
                                    <input type="text" class="form-control" value="{{$user->middle_name}}" name="middle_name" placeholder="Middle Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Last Name:</label>
                                    <input type="text" class="form-control" value="{{$user->last_name}}" name="last_name" placeholder="Last Name">
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                  <label>Country code:</label>
                                    <label class="form-control">{{$user->country_code}}</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                  <label>Phone:</label>
                                    <label class="form-control">{{$user->phone}}</label>
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
                     
                            <div class="col-md-12">
                              <div class="form-group">
                                <button name="editcounsellor" type="submit" class="btn btn-primary ">Update</button>
                              </div>
                            </div>
                            
                           
                        </div>
                        </form>
            </div>
                <!-- <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Counsellor: #{{$user->id}}</h4>
                    </div>
                    <div class="modal-body">
                        <form action="{{url('login/counsellors/update',[$user->id])}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Counsellor Type:</label>
                                   <select name="counsellor_type" class="form-control ">
                                       @if($user->counsellor_type==1)
                                        <option value="1" selected>Outside Counsellor</option>
                                        <option value="0">Inside Counsellor</option>
                                        @else
                                        <option value="1">Outside Counsellor</option>
                                        <option value="0" selected>Inside Counsellor</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Name:</label>
                                    <input type="text" class="form-control" value="{{$user->name}}" name="name" required placeholder="Name"> 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Email:</label>
                                    <input type="email" class="form-control" value="{{$user->email}}" disabled name="email" required placeholder="Email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>First Name:</label>
                                    <input type="text" class="form-control" value="{{$user->first_name}}" name="first_name" placeholder="First Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Middle Name:</label>
                                    <input type="text" class="form-control" value="{{$user->middle_name}}" name="middle_name" placeholder="Middle Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label>Last Name:</label>
                                    <input type="text" class="form-control" value="{{$user->last_name}}" name="last_name" placeholder="Last Name">
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                  <label>Country code:</label>
                                    <label class="form-control">{{$user->country_code}}</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                  <label>Phone:</label>
                                    <label class="form-control">{{$user->phone}}</label>
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
                     
                            <div class="col-md-12">
                              <div class="form-group">
                                <button style="float:right;" name="editcounsellor" type="submit" class="btn btn-primary ">Update</button>
                              </div>
                            </div>
                            
                           </div>
                        </form>
                    </div>
                </div> -->
</div>


                
                
            </div>
        
    </div>            
</div>


@endsection
@push('select2')

<script type="text/javascript">
    $("#coun-timezone-tags").select2({
  tags: false
});
</script>
@endpush