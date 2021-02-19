<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//echo "<pre>";
//print_r($routes);
//print_r($pkgroutes);
?>
  <!-- Home -->
  <main id="homeBody" class="body-wrapper bg-image d-flex flex-column justify-content-md-center">
   <div class="container center-this pt-3">
      <h1 class="text-white text-center heading-jumbo"> Book Online now with Rottnest Fast Ferries </h1>
      <h4 class="text-white text-center"> Lightning fast ocean crossing in air-conditioned comfort on one of our high
        speed ferries</h4>
      <div class="widget-wrapper">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
          <li class="nav-item tab-item">
            <a class="widget-link nav-link active" id="rottnestFerries-tab" data-toggle="tab" href="#rottnestFerries"
              role="tab" aria-controls="rottnestFerries" aria-selected="true"><i class="demo-icon ferry-icon  mr-2">
              </i>Rottnest Ferries</a>
          </li>
          <li class="nav-item tab-item">
            <a class="widget-link nav-link " id="tours-tab" data-toggle="tab" href="#tours" role="tab"
              aria-controls="tours" aria-selected="false"><i class="demo-icon packages-icon mr-2"></i>Tours &
              Packages</a>
          </li>
          <li class="nav-item tab-item">
            <a class="widget-link nav-link" id="cruises-tab" data-toggle="tab" href="#cruises" role="tab"
              aria-controls="cruises" aria-selected="false"><i class="demo-icon ferry-icon mr-2"> </i>Cruises</a>

          </li>
          <li class="nav-item tab-item">
            <a class="widget-link nav-link" id="whale-tab" data-toggle="tab" href="#whale" role="tab"
              aria-controls="whale" aria-selected="false"><i class="demo-icon whale-icon mr-2"></i>Whale Watching</a>
          </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
          <!-- Start Rottnest Ferries -->
          <div class="tab-pane active" id="rottnestFerries" role="tabpanel" aria-labelledby="rottnestFerries-tab">
            <form id="myForm" action="<?php echo site_url(); ?>processbooking/returnBooking/">
              <div class="form-row align-items-start">
                <div class="form-group col-md-3">
                  <label for="From">From</label>
                  <div id="from" class="btn-group custom-btn-group">
                    <button onclick="sourceDestination('HIL')" type="button" value="hillarys" id="hill_button" class="btn btn-light">Hillarys</button>
                    <button onclick="sourceDestination('ROT')" type="button" value="rottnest" id="rot_button" class="btn btn-light">Rottnest</button>
					<input type="hidden" id="route" name="route" value="HILROT" />
					<input type="hidden" id="outward" name="outward" value="HIL" />
					<input type="hidden" id="arrival" name="arrival" value="ROT" />
					<input type="hidden" id="tickettype" name="tickettype" value="return" />
                  </div>
                </div>

                <div class="form-row col-md-5 align-items-end justify-content-md-center">
                  <div class="form-group col-8">
                    <label for="inputTo">To</label>
                    <input type="text" name class="form-control" id="inputTo" disabled>
                  </div>
                  <div class="form-check form-group">
                    <input onclick="changeAction(this);" class="form-hceck-input" type="checkbox" id="return" checked>
                    <label class="form-check-label"> Return</label>
                  </div>

                </div>
                


                <div class="col-md-4">
                    <div id="hideThis" class="relative">
                      <div class="form-row align-items-end justify-content-md-center flex-nowrap">
                          <div class="form-group w-100">
                              <label for="dept-date">Departure Date</label>
                              <input type="text" name="dept-date" id="datepicker1" class="form-control" value="" />
                          </div>
                          <div class="form-group w-100 return-ferry-group">
                              <label for="ret-date">Return Date</label>
                              <input type="text" name="ret-date" id="datepicker2" class="form-control" value="" />
                          </div>
                      </div>
                      <div id="triggerCalendar"></div>
                    </div>
                    
                    <div id="singleDate" class="relative" style="display: none;">
                      <div class="form-row align-items-end justify-content-md-center flex-nowrap">
                          <div class="form-group w-100">
                              <label for="singleFerryDate">Departure Date</label>
                              <input type="text" name="singleFerryDate" id="singleFerryDate" class="form-control" value="" />
                          </div>
                       
                      </div>
                    </div>
                </div>

                <!--
                <div class="col-md-4">
                  <div class="form-row align-items-end justify-content-md-center flex-nowrap">
                    <div class="form-group w-100">
                      <label for="departureFerry">Departure Date</label>
                      <input type="text" name="departureFerry" id="departureFerry" class="form-control" value="" />
                    </div>
                    <div class="form-group w-100 return-ferry-group">
                      <label for="departureFerry">Return Date</label>
                      <input type="text" name="returnFerry" id="returnFerry" class="form-control" value="" />
                    </div>
                  </div>
                </div>
                -->
               
              </div>

              <div class="form-row">
                <div class="col-md-8 form-row">
                  <div class="form-group col-md-2 col-sm-4 col-4 ">
                    <label for="adultFerry">Adult</label>
                    <input id="adultFerry" name="adults" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="childFerry">Child (4-12)</label>
                    <input id="childFerry" name="child" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="toddlerFerry">Toddler (2-3)</label>
                    <input id="toddlerFerry" name="toddler" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="infantFerry">Infant (U2)</label>
                    <input id="infantFerry" name="infants" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="consessionFerry">Students <i class="demo-icon icon-awesome-info-circle-icon"
                        data-toggle="tooltip" title="Student Card Holders"
                        data-placement="top" tab-index="0"></i> </label>
                    <input id="consessionFerry" name="students" type="number" class="form-control number-input" value="0">
                  </div>
				  
				  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="consessionFerry">Seniors <i class="demo-icon icon-awesome-info-circle-icon"
                        data-toggle="tooltip" title="Australian Seniors and Pension Card Holders"
                        data-placement="top" tab-index="0"></i> </label>
                    <input id="seniors" name="seniors" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="familyPassFerry">Family Pass <i class="demo-icon icon-awesome-info-circle-icon"
                        data-toggle="tooltip" title="2 Adults & 2 Children" data-placement="top" tab-index="0"></i>
                    </label>
                    <input id="familyPassFerry" name="family" type="number" class="form-control number-input" value="0">
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="d-flex align-items-end justify-content-start ">
                    <div class="form-group flex-fill">
                      <label for="couponCodeFerry">Coupon code</label>
                      <input id="couponCodeFerry" name="couponCodeFerry" class="text-uppercase form-control">
                    </div>

                    <div class="form-group ml-2">
					  <input type="submit" id="regular-submit" class="btn btn-primary" name="regular-submit" value="SEARCH" />
                      <!--<a href="javascript:void(0);" onclick="this.form.submit();" class="btn btn-primary">SEARCH</a>-->
                    </div>
                  </div>
                </div>

              </div>
            </form>

          </div>

          <!-- Start Tours -->

          <div class="tab-pane" id="tours" role="tabpanel" aria-labelledby="tours-tab">
            <form id="pkgForm" action="<?php echo site_url(); ?>processbooking/packageBooking/">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="tourPackage">Tour / Package</label>
                  <select id="tourPackage" name="packagecode" class="form-control">
                    <option selected>Select a tour or a package...</option>
					<?php foreach($pkgroutes['Description'] as $pkg) { ?>
                    <option value="<?php echo $pkg['PackageCode']; ?>"><?php echo $pkg['Name']; ?></option>
					<?php } ?>
                  </select>
				  <input type="hidden" name="tickettype" value="PACK" />
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="departureDateTour">Departure Date</label>
                    <input type="text" name="dept-date" id="departureDateTour" class="departure-date form-control" value="" />
                  </div>
                </div>
              </div>

              <div class="form-row">
                <div class="col-md-7 quantity-container">
                  <div class="quanitty-row">
                    <div class="form-group quantity-item">
                      <label for="adultTour">Adult</label>
                      <input id="adultTour" name="adults" type="number" class="form-control number-input" value="0">
                    </div>

                    <div class="form-group quantity-item">
                      <label for="childTour">Child (4-12)</label>
                      <input id="childTour" name="child" type="number" class="form-control number-input" value="0">
                    </div>

                    <div class="form-group quantity-item">
                      <label for="toddlerTour">Toddler (2-3)</label>
                      <input id="toddlerTour" name="toddler" type="number" class="form-control number-input"
                        value="0">
                    </div>
                  </div>

                  <div class="quanitty-row-type-two">
                    <div class="form-group quantity-item">
                      <label for="infantTour">Infant (U2)</label>
                      <input id="infantTour" name="infants" type="number" class="form-control number-input" value="0">
                    </div>

                    <div class="form-group quantity-item">
                      <label for="studentsTour">Students <i class="demo-icon icon-awesome-info-circle-icon"
                          data-toggle="tooltip" title="Student Card Holders, Australian Seniors and Pension Card Holders"
                          data-placement="top" tab-index="0"></i> </label>
                      <input id="studentsTour" name="students" type="number" class="form-control number-input"
                        value="0">
                    </div>
					 <div class="form-group quantity-item">
                      <label for="seniorsTour">Seniors <i class="demo-icon icon-awesome-info-circle-icon"
                          data-toggle="tooltip" title="Student Card Holders, Australian Seniors and Pension Card Holders"
                          data-placement="top" tab-index="0"></i> </label>
                      <input id="seniorsTour" name="seniors" type="number" class="form-control number-input"
                        value="0">
                    </div>
                  </div>
                </div>

                <div class="col-md-5">
                  <div class="d-flex align-items-end justify-content-start ">
                    <div class="form-group flex-fill">
                      <label for="couponCodeTour">Coupon code</label>
                      <input id="couponCodeTour" name="couponCodeTour" class="text-uppercase form-control">
                    </div>

                    <div class="form-group ml-2">
                      <input type="submit" id="pkg-submit" class="btn btn-primary" name="regular-submit" value="SEARCH" />
                    </div>
                  </div>
                </div>
              </div>


            </form>
          </div>



          <div class="tab-pane" id="cruises" role="tabpanel" aria-labelledby="cruises-tab">
            <form id="cruiseForm" action="<?php echo site_url(); ?>processbooking/onewayBooking/">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="cruise">Cruise</label>
                  <select id="cruise" name="cruise" class="form-control" disabled>
                    <option value="grand-island-package" selected >Coastal Cruise</option>
                  </select>
				  <input type="hidden" name="route" value="CRCOAST" />
				  <input type="hidden" name="tickettype" value="cruises" />
				  <input type="hidden" name="arrival" value="COAST" />
				  <input type="hidden" name="outward" value="" />
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="departureDateCruise">Departure Date</label>
                    <input type="text" name="dept-date" id="departureDateCruise" class="departure-date form-control" value="" />
                  </div>
                </div>
              </div>

              <div class="form-row">
                <div class="col-md-8 form-row">
                  <div class="form-group col-md-2 col-sm-4 col-4 ">
                    <label for="adultCruise">Adult</label>
                    <input id="adultCruise" name="adults" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="childCruise">Child (4-12)</label>
                    <input id="childCruise" name="child" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="toddlerCruise">Toddler (2-3)</label>
                    <input id="toddlerCruise" name="toddler" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="infantCruise">Infant (U2)</label>
                    <input id="infantCruise" name="infants" type="number" class="form-control number-input" value="0">
                  </div>
				   <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="consessionCruise">Students <i class="demo-icon icon-awesome-info-circle-icon"
                        data-toggle="tooltip" title="Student Card Holders"
                        data-placement="top" tab-index="0"></i> </label>
                    <input id="consessionCruise" name="students" type="number" class="form-control number-input" value="0">
                  </div>
				  
				  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="seniorsCruise">Seniors <i class="demo-icon icon-awesome-info-circle-icon"
                        data-toggle="tooltip" title="Australian Seniors and Pension Card Holders"
                        data-placement="top" tab-index="0"></i> </label>
                    <input id="seniorsCruise" name="seniors" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="familyPassCruise">Family Pass <i class="demo-icon icon-awesome-info-circle-icon"
                        data-toggle="tooltip" title="2 Adults & 2 Children" data-placement="top" tab-index="0"></i>
                    </label>
                    <input id="familyPassCruise" name="family" type="number" class="form-control number-input" value="0">
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="d-flex align-items-end justify-content-start ">
                    <div class="form-group flex-fill">
                      <label for="couponCodeCruise">Coupon code</label>
                      <input id="couponCodeCruise" name="couponCodeCruise" class="text-uppercase form-control">
                    </div>

                    <div class="form-group ml-2">
                      <input type="submit" id="cruise-submit" class="btn btn-primary" name="cruise-submit" value="SEARCH" />
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>



          <div class="tab-pane" id="whale" role="tabpanel" aria-labelledby="whale-tab">
            <form id="whaleForm" action="<?php echo site_url(); ?>processbooking/onewayBooking/">

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="whaleWatching">Whale Watching</label>
                  <select id="whaleWatching" name="whale-watching" class="form-control" disabled>
                    <option value="grand-island-package" selected>2 Hours Whale Watching Cruises</option>
                  </select>
				  <input type="hidden" name="route" value="CRWHALE" />
				  <input type="hidden" name="tickettype" value="cruises" />
				  <input type="hidden" name="arrival" value="WHALE" />
				  <input type="hidden" name="outward" value="" />
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="departureDateWhale">Departure Date</label>
                    <input type="text" name="dept-date" id="departureDateWhale" class="departure-date form-control" value="" />
                  </div>
                </div>
              </div>

              <div class="form-row">
                <div class="col-md-8 form-row">
                  <div class="form-group col-md-2 col-sm-4 col-4 ">
                    <label for="adultWhale">Adult</label>
                    <input id="adultWhale" name="adults" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="childWhale">Child (4-12)</label>
                    <input id="childWhale" name="child" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="toddlerWhale">Toddler (2-3)</label>
                    <input id="toddlerWhale" name="toddler" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="infantWhale">Infant (U2)</label>
                    <input id="infantWhale" name="infants" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="consessionWhale">Students <i class="demo-icon icon-awesome-info-circle-icon"
                        data-toggle="tooltip" title="Student Card Holders"
                        data-placement="top" tab-index="0"></i> </label>
                    <input id="consessionWhale" name="students" type="number" class="form-control number-input" value="0">
                  </div>
				  
				  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="seniorsWhale">Seniors <i class="demo-icon icon-awesome-info-circle-icon"
                        data-toggle="tooltip" title="Australian Seniors and Pension Card Holders"
                        data-placement="top" tab-index="0"></i> </label>
                    <input id="seniorsWhale" name="seniors" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="familyPassWhale">Family Pass <i class="demo-icon icon-awesome-info-circle-icon"
                        data-toggle="tooltip" title="2 Adults & 2 Children" data-placement="top" tab-index="0"></i>
                    </label>
                    <input id="familyPassWhale" name="family" type="number" class="form-control number-input" value="0">
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="d-flex align-items-end justify-content-start ">
                    <div class="form-group flex-fill">
                      <label for="couponCodeWhale">Coupon code</label>
                      <input id="couponCodeWhale" name="couponCodeWhale" class="text-uppercase form-control">
                    </div>

                    <div class="form-group ml-2">
                      <input type="submit" id="cruise-submit" class="btn btn-primary" name="cruise-submit" value="SEARCH" />
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
	<div id="app"></div>
  </main>