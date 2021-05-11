
  <div class="col-xs-12">
    <!-- Default box -->
      <section>

        <!--Grid row-->
        <div class="row">

          <!--Grid column-->
          @foreach($packages as $package)
          <div class="col-lg-4 col-md-12 mb-4">
            
            <!-- Card -->
            <div class="card">

              <div class="card-body">

                <a href=""><p class="text-uppercase small mb-2"><strong>{{$package->package_name}}</strong></p></a>
                <h5 class="font-weight-bold mb-0">
                  <strong>Amount: </strong>{{$package->amount}}
                  <small class="text-success ml-2">
                    <i class="fas fa-arrow-up fa-sm pr-1"></i>13,48%</small>
                </h5>
                <p class="small mb-2"><strong>Duration: </strong>{{$package->session_hours}}:{{$package->session_minutes}} Hours</p>

                <hr>

              </div>

            </div>
            <!-- Card -->
           
          </div>
          @endforeach
          <!--Grid column-->

          

        </div>
        <!--Grid row-->

      </section>
  </div>