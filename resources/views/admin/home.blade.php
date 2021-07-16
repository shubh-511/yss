@extends('admin.layouts.app')

@section('content')
<!-- Default box -->
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Dashbord</h3>
    </div>
    <div class="box-body">
      <div class="row">
        <!--Users Count-->
        <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
        <div class="inner">
        <h3>{{$userCount}}</h3>

        <p>Users</p>
        </div>
        <div class="icon">
        <i class="fa fa-user"></i>
        </div>
        <a href="{{url('login/users')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
        </div>
        <!---->


        <!--Users Count-->
        <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
        <div class="inner">
        <h3>{{$bookingCount}}</h3>

        <p>Bookings</p>
        </div>
        <div class="icon">
        <i class="fa fa-ticket"></i>
        </div>
        <a href="{{url('login/bookings')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
        </div>
        <!---->
         <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
        <div class="inner">
        <h3>€{{$total_revenue}}</h3>

        <p>Total Revenue</p>
        </div>
        <div class="icon">
        <i class="fa fa-money"></i>
        </div>
        <a  class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
        </div>

        
        
<!-- ./col -->
</div>
        <div class="row">

            <div class="col-md-6">
                
             <div id="userChart"></div>
            </div>
            <div class="col-md-6">
             <div id="bookingChart"></div>
            </div>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
      
    </div>
    <!-- /.box-footer-->
  </div>
  <!-- /.box -->

<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://code.highcharts.com/stock/modules/export-data.js"></script>
<!-- User Chart -->
<script type="text/javascript">
    var users =  <?php echo json_encode($users) ?>;
    var array_month =  <?php echo json_encode($users_mon_data) ?>;
    Highcharts.chart('userChart', {
        title: {
            text: 'New Users'
        },
        chart: {
            type: 'area'
        },
        subtitle: {
            text: ''
        },
         xAxis: {
            categories: array_month,
        },
        yAxis: {
            title: {
                text: 'Number of New Users'
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
            name: 'New Users',
            data: users,
            /*tooltip: {
            valueDecimals: 2
          }*/
        }],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
});
</script>
<!-- End User Chart -->

<!-- Booking Chart -->
<script type="text/javascript">
    var users =  <?php echo json_encode($bookings) ?>;
   var booking_array_month =  <?php echo json_encode($booking_data) ?>;
    Highcharts.chart('bookingChart', {
        title: {
            text: 'New Bookings'
        },
        chart: {
            type: 'area'
        },
        subtitle: {
            text: ''
        },
         xAxis: {
            categories: booking_array_month,
        },
        yAxis: {
            title: {
                text: 'Number of New Bookings'
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
            name: 'New Bookings',
            data: users,
            /*tooltip: {
            valueDecimals: 2
          }*/
        }],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
});
</script>
<!-- End Booking Chart -->
    
@endsection
