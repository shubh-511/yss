@extends('admin.layouts.app')

@section('content')

@push('calander')
<link rel="stylesheet" href="{{asset('css/jsRapCalendar.css')}}" />
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>

@endpush
<style>
    .boxslots
    {
      border: 1px solid #e2e8f0;
      padding: 10px;
      width: 85px;
    }
    
</style>
<div class="row">
  <div class="col-xs-12">
    <!-- Default box -->
      <div class="box">
          <div class="box-header">
            
             
          
          </div>
          <div class="box-body">
            <div class="row">
                <div class="col-md-12">


                    <div class="form-group">
                    <h4 class="control-label nopadding " style="margin-bottom: 25px;">1. Select counsellor and user from droplist you want to make booking:</h4>
                     <div class="col-md-6">
                      <label>Select Counsellor</label>
                         <select id="select-counsellor" class="form-control counsellor-tags">
                          <option value="">Select Counsellor</option>
                          @foreach($counsellors as $counsellor)
                          <option data-counsellor="{{$counsellor->id}}" value="{{$counsellor->email}}">{{$counsellor->email}}</option>
                          @endforeach
                          
                        </select>
                
                      </div>
                      <div class="col-md-6">
                       <label>Select User</label>
                         <select id="select-user" class="form-control user-tags">
                          <option value="">Select User</option>
                          @foreach($users as $user)

                          <option data-user="{{$user->id}}" value="{{$user->email}}">{{$user->email}}</option>

                          @endforeach
                          
                        </select>
                      
                      </div>
                  </div>
                </div>
      
            </div>
<div class="row" id="rowbody" >
      
</div>
<div class="row">
  <div class="col-md-4">
    <div class="demo"></div>
  </div>
  <div class="col-md-8">
    <div style="margin-bottom: 5px;" id="availabilityOn"></div>
    <input type="hidden" id="availabilityDate">
    <input type="hidden" id="packageToBook">
    <a id="bookSlots" style="display: none;" class="btn btn-primary">Proceed to book</a>
    <div id="dateSlots"></div>
  </div>
  
  </div>
</div>
          
          
      </div>

      <!-- /.box -->
    </div>
  </div>
</div>
@endsection

@push('select2')

<script type="text/javascript">
 
$(".counsellor-tags").select2({
  tags: true,
  placeholder: "Select Counsellor"
});
$(".user-tags").select2({
  tags: true,
  placeholder: "Select User"
});


$(document).on('change', '#select-counsellor', function() {
  $( ".demo" ).empty();
  $( ".demo" ).removeClass("rapCalendar");
var counsellorId = $(this).find(':selected').data("counsellor");
    $.ajax({
            url: 'getCounsellorPackage',
            type: 'get',
            data: {counsellorId:counsellorId},
            //dataType: 'json',
            success:function(response){
                //alert(response);
                $('#rowbody').html(response);
                
             }
        });
});


function getAppointment(packageId)
{
  var userId = $( "#select-user" ).find(':selected').data("user");
  var counsellorId = $( "#select-counsellor" ).find(':selected').data("counsellor");

  if(userId == null)
  {
      alert('Select user from the droplist!');
      return false;
  }
  
  
  /*else
  {*/
      $(document).ready(function(){

        $( ".demo" ).empty();
        $( "#dateSlots" ).empty();
        $( "#availabilityDate" ).empty();
        $( "#packageToBook" ).empty();
        $('.demo').jsRapCalendar({
          week:6,
          onClick:function(y,m,d){
            m=m+1;
            if(m<10)
            {
              m='0'+m;
            }
            if(d<10)
            {
              d='0'+d;
            }
            $( "#dateSlots" ).empty();
            //alert(y + '-' + m + '-' + d);
            $( "#availabilityOn" ).html( "<strong>Availability on "+y + '-' + m + '-' + d+"</strong>" );
            $( "#availabilityDate" ).val(y + '-' + m + '-' + d);
            $( "#packageToBook" ).val(packageId);

            $.ajax({
            url: '../api/break-packages',
            type: 'get',
            dataType: 'json',
            data: {counsellor_id:counsellorId, user_id:userId, package_id:packageId, date:y + '-' + m + '-' + d},
            //dataType: 'json',
            success:function(response){
                //alert(JSON.stringify(response));
                  if(response.success == true)
                  {
                      jQuery.each(response.data,function(key,value){
                        console.log(key);
                        jQuery.each(value,function(key,val){
                          console.log(val.slot);
                          if(val.enabled == 1)

                          $( "#dateSlots" ).append( "<div class='boxslots'><input type='checkbox' id='"+val.slot.replace(/:|\s/g,"")+"' class='checklist' name='slots' value='"+val.slot+"' style='display: none;'><label for='"+val.slot.replace(/:|\s/g,"")+"'>"+val.slot+"</label></div>" );  

                          //$( "#dateSlots" ).append( "<input type='checkbox' class='checklist' name='slots' value='"+val.slot+"'><div class='boxslots'>"+val.slot+"</div>" );
                        });

                       });

                  }
                  else
                  {

                  } 
                
              }
            });

          }
        });

      });
  //}
  
}

$(document).on('change', '.checklist', function() {
var checkedNum = $('input.checklist:checked').length
      if (checkedNum > 0) {
          
          $("#bookSlots"). show()
      }
      else
      {
          //$("#bookSlots").css("display", "none"); 
          $("#bookSlots"). hide()  
      }
});

 $(document).ready(function() {
        $("#bookSlots").click(function(){
          var userId = $( "#select-user" ).find(':selected').data("user");
          var date = $('#availabilityDate').val();
          var package = $('#packageToBook').val();

          var counsellorId = $( "#select-counsellor" ).find(':selected').data("counsellor");

            var mySlots = [];
            $.each($("input[name='slots']:checked"), function(){
                mySlots.push($(this).val());
            });
            //alert("" + mySlots);
            if(userId!=null && date!=null && package!=null && counsellorId!=null && mySlots!='')
            {
              if(confirm("Are you sure you want to proceed with booking for the selected slot(s)?"))
              {
                  $.ajax({
                  headers : {
                  'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                  },  
                  url: 'make/custom/booking',
                  type: 'post',
                  dataType: 'json',
                  data: {counsellor_id:counsellorId, user_id:userId, package_id:package, date:date, my_slots:mySlots},
                  //dataType: 'json',
                  success:function(response){
                      //alert(response);
                        if(response == 1)
                        {
                          alert("The booking has been successfull!!")
                          //location.reload();
                          window.location.href = <?php url() ?>"bookings";
                        }
                        else
                        {
                          alert("Something went wrong")
                        } 
                      
                    }
                  });
              }
                
            }
            else
            {
                alert('Some thing went wrong. Please try again!');
            }
        });
    });



function hideUserForm(x) {
   if (x.checked) {
     document.getElementById("userForm").style.display = "none";
     document.getElementById("eventForm").style.display = "block";
   }
  }

  function hideEventForm(x) {
   if (x.checked) {
     document.getElementById("eventForm").style.display = "none";
     document.getElementById("userForm").style.display = "block";
   }
  } 
 
</script>
@endpush
