@extends('admin.layouts.app')
@section('content')
<div class="row">
	        <form method="get" url="{{('/counsellors/revenue/')}}" enctype="multipart/form-data">
               <div class="col-md-4">
                 <label>Select Year</label>
                 <select name="year" class="form-control" onchange="myFunction()" id="revenue">
                 	@php  $this_year = date("Y");
                 	for ($year = $this_year; $year >= $this_year - 1; $year--) 
                 	{
                    echo  '<option value="' . $year . '">' . $year . '</option>';    
                    }
                    @endphp
                  </select>
            </div>
             <div class="col-md-4">
                 <label>Select Month</label>
                 <select name="month" class="form-control">
                 	<option disabled selected value>select</option>
                 	<option value="1">January</option>
                 	<option value="2">February</option>
                 	<option value="3">March</option>
                 	<option value="4">April</option>
                 	<option value="5">May</option>
                 	<option value="6">June</option>
                 	<option value="7">July</option>
                 	<option value="8">August</option>
                 	<option value="9">Sepetember</option>
                 	<option value="10">October</option>
                 	<option value="11">November</option>
                 	<option value="12">December</option>
                 </select>
             </div>
             <br>
             
             <div class="col-md-4">
                  <input type="submit" class="btn btn-primary" value="Filter" onclick="clickFunction()">
                </div>
                </form>
             <br>
             <div class="col-md-12">
                <div class="col-md-4">
                </div>
                <div class="col-md-4">
                 <h5 id="revenue_value"></h5>
                </div>
             <div class="col-md-4">
                </div>
             </div>
            <div class="col-md-12">
                
             <div id="container"></div>
            </div>
        </div>
  <script src="https://code.highcharts.com/stock/highstock.js"></script>
  <script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/stock/modules/export-data.js"></script>
  
      <script type="text/javascript">
   document.addEventListener('DOMContentLoaded', function () {
        const chart = Highcharts.chart('container', {
            chart: {
                type: 'area'
            },
             title: {
                text: 'Counsellors Revenue'
            },
            xAxis: {
                 categories: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
            },
            yAxis: {
                title: {
                    text: 'Counsellors Revenue'
                }
            },
             legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },
        plotOptions: {
            series: {
                allowPointSelect: true
            }
        },
            series: [{
                name: 'Counsellors Revenue',
                data: ['', '', '','','','','', '', '','','','']
            }]
        });
    });
</script>
@endsection
<script type="text/javascript">
    function myFunction() {
  var revenue = $("#revenue").val();
  $("#revenue_value").text(revenue);
}
function clickFunction() {
  var revenue = $("#revenue").val();
  $("#revenue_value").text(revenue);
}
  </script>

  