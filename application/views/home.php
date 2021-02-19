<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$routeArray = array();
$cruiseArray = array();
$packageArray = array();

foreach($routes as $listRoutes){
if($listRoutes['outwardPortCode'] != 'CR' && $listRoutes['outwardPortCode'] != 'ADM'){ 
      $routeArray[] = $listRoutes;
}
if($listRoutes['outwardPortCode'] == 'CR')
      $cruiseArray[]  = $listRoutes;
}

?>
<?php
foreach($pkgroutes as $pkgRoute){
$listPkgRoutes = json_decode(json_encode($pkgRoute), true);
	foreach($listPkgRoutes as $listRoutePkg){
		unset($listRoutePkg['Description']);
		$packageArray[] = $listRoutePkg;
	}
}
?>

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
            <form id="myForm">
              <div class="form-row align-items-start">
                <div class="form-group col-md-3">
                  <label for="From">From</label>
                  <div id="from" class="btn-group custom-btn-group">
                    <button type="button" value="hillarys" class="btn btn-light">Hillarys</button>
                    <button type="button" value="rottnest" class="btn btn-light">Rottnest</button>
                  </div>
                </div>

                <div class="form-row col-md-5 align-items-end justify-content-md-center">
                  <div class="form-group col-8">
                    <label for="inputTo">To</label>
                    <input type="text" class="form-control" id="inputTo" disabled>
                  </div>
                  <div class="form-check form-group">
                    <input class="form-hceck-input" type="checkbox" id="return" checked>
                    <label class="form-check-label"> Return</label>
                  </div>

                </div>
                


                <div class="col-md-4">
                    <div id="hideThis" class="relative">
                      <div class="form-row align-items-end justify-content-md-center flex-nowrap">
                          <div class="form-group w-100">
                              <label for="departureFerry">Departure Date</label>
                              <input type="text" name="departureFerry" id="datepicker1" class="form-control" value="" />
                          </div>
                          <div class="form-group w-100 return-ferry-group">
                              <label for="returnFerry">Return Date</label>
                              <input type="text" name="returnFerry" id="datepicker2" class="form-control" value="" />
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
                    <input id="adultFerry" name="adultFerry" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="childFerry">Child (4-12)</label>
                    <input id="childFerry" name="childFerry" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="toddlerFerry">Toddler (2-3)</label>
                    <input id="toddlerFerry" name="toddlerFerry" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="infantFerry">Infant (U2)</label>
                    <input id="infantFerry" name="infantFerry" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="consessionFerry">Concession <i class="demo-icon icon-awesome-info-circle-icon"
                        data-toggle="tooltip" title="Student Card Holders, Australian Seniors and Pension Card Holders"
                        data-placement="top" tab-index="0"></i> </label>
                    <input id="consessionFerry" name="consessionFerry" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="familyPassFerry">Family Pass <i class="demo-icon icon-awesome-info-circle-icon"
                        data-toggle="tooltip" title="2 Adults & 2 Children" data-placement="top" tab-index="0"></i>
                    </label>
                    <input id="familyPassFerry" name="familyPassFerry" type="number" class="form-control number-input" value="0">
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="d-flex align-items-end justify-content-start ">
                    <div class="form-group flex-fill">
                      <label for="couponCodeFerry">Coupon code</label>
                      <input id="couponCodeFerry" name="couponCodeFerry" class="text-uppercase form-control">
                    </div>

                    <div class="form-group ml-2">
                      <a href="ferry/select-ferries.html" class="btn btn-primary">SEARCH</a>
                    </div>
                  </div>
                </div>

              </div>
            </form>

          </div>

          <!-- Start Tours -->

          <div class="tab-pane" id="tours" role="tabpanel" aria-labelledby="tours-tab">
            <form>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="tourPackage">Tour / Package</label>
                  <select id="tourPackage" name="tourPackage" class="form-control">
                    <option selected>Select a tour or a package...</option>
                    <option value="bayseeker-island-package">Bayseeker Island Package</option>
                    <option value="bike-ferry-combo">Bike & Ferry Combo</option>
                    <option value="bus-ferry-combo">Bus Pass & Ferry Combo</option>
                    <option value="grand-island-package">Grand Island Package</option>
                    <option value="historical-tour-train">Historical Train & Tunnel Tour</option>
                    <option value="rottnest-seafood">Rottnest Wild Seafood Experience Tour</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="departureDateTour">Departure Date</label>
                    <input type="text" name="departureDateTour" id="departureDateTour" class="departure-date form-control" value="" />
                  </div>
                </div>
              </div>

              <div class="form-row">
                <div class="col-md-7 quantity-container">
                  <div class="quanitty-row">
                    <div class="form-group quantity-item">
                      <label for="adultTour">Adult</label>
                      <input id="adultTour" name="adultTour" type="number" class="form-control number-input" value="0">
                    </div>

                    <div class="form-group quantity-item">
                      <label for="childTour">Child (4-12)</label>
                      <input id="childTour" name="childTour" type="number" class="form-control number-input" value="0">
                    </div>

                    <div class="form-group quantity-item">
                      <label for="toddlerTour">Toddler (2-3)</label>
                      <input id="toddlerTour" name="toddlerTour" type="number" class="form-control number-input"
                        value="0">
                    </div>
                  </div>

                  <div class="quanitty-row-type-two">
                    <div class="form-group quantity-item">
                      <label for="infantTour">Infant (U2)</label>
                      <input id="infantTour" name="infantTour" type="number" class="form-control number-input" value="0">
                    </div>

                    <div class="form-group quantity-item">
                      <label for="consessionTour">Concession <i class="demo-icon icon-awesome-info-circle-icon"
                          data-toggle="tooltip" title="Student Card Holders, Australian Seniors and Pension Card Holders"
                          data-placement="top" tab-index="0"></i> </label>
                      <input id="consessionTour" name="consessionTour" type="number" class="form-control number-input"
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
                      <a href="pages/select-ferries.html" class="btn btn-primary" id="searchTour">SEARCH</a>
                    </div>
                  </div>
                </div>
              </div>


            </form>
          </div>



          <div class="tab-pane" id="cruises" role="tabpanel" aria-labelledby="cruises-tab">
            <form>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="cruise">Cruise</label>
                  <select id="cruise" name="cruise" class="form-control" disabled>
                    <option value="grand-island-package" selected >Coastal Cruise</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="departureDateCruise">Departure Date</label>
                    <input type="text" name="departureDateCruise" id="departureDateCruise" class="departure-date form-control" value="" />
                  </div>
                </div>
              </div>

              <div class="form-row">
                <div class="col-md-8 form-row">
                  <div class="form-group col-md-2 col-sm-4 col-4 ">
                    <label for="adultCruise">Adult</label>
                    <input id="adultCruise" name="adultCruise" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="childCruise">Child (4-12)</label>
                    <input id="childCruise" name="childCruise" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="toddlerCruise">Toddler (2-3)</label>
                    <input id="toddlerCruise" name="toddlerCruise" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="infantCruise">Infant (U2)</label>
                    <input id="infantCruise" name="infantCruise" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="consessionCruise">Concession <i class="demo-icon icon-awesome-info-circle-icon"
                        data-toggle="tooltip" title="Student Card Holders, Australian Seniors and Pension Card Holders"
                        data-placement="top" tab-index="0"></i> </label>
                    <input id="consessionCruise" name="consessionCruise" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="familyPassCruise">Family Pass <i class="demo-icon icon-awesome-info-circle-icon"
                        data-toggle="tooltip" title="2 Adults & 2 Children" data-placement="top" tab-index="0"></i>
                    </label>
                    <input id="familyPassCruise" name="familyPassCruise" type="number" class="form-control number-input" value="0">
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="d-flex align-items-end justify-content-start ">
                    <div class="form-group flex-fill">
                      <label for="couponCodeCruise">Coupon code</label>
                      <input id="couponCodeCruise" name="couponCodeCruise" class="text-uppercase form-control">
                    </div>

                    <div class="form-group ml-2">
                      <a href="pages/select-ferries.html" class="btn btn-primary">SEARCH</a>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>



          <div class="tab-pane" id="whale" role="tabpanel" aria-labelledby="whale-tab">
            <form>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="whaleWatching">Whale Watching</label>
                  <select id="whaleWatching" name="whale-watching" class="form-control" disabled>
                    <option value="grand-island-package" selected>2 Hours Whale Watching Cruises</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="departureDateWhale">Departure Date</label>
                    <input type="text" name="departureDateWhale" id="departureDateWhale" class="departure-date form-control" value="" />
                  </div>
                </div>
              </div>

              <div class="form-row">
                <div class="col-md-8 form-row">
                  <div class="form-group col-md-2 col-sm-4 col-4 ">
                    <label for="adultWhale">Adult</label>
                    <input id="adultWhale" name="adult" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="childWhale">Child (4-12)</label>
                    <input id="childWhale" name="childWhale" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="toddlerWhale">Toddler (2-3)</label>
                    <input id="toddlerWhale" name="toddlerWhale" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="infantWhale">Infant (U2)</label>
                    <input id="infantWhale" name="infantWhale" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="consessionWhale">Concession <i class="demo-icon icon-awesome-info-circle-icon"
                        data-toggle="tooltip" title="Student Card Holders, Australian Seniors and Pension Card Holders"
                        data-placement="top" tab-index="0"></i> </label>
                    <input id="consessionWhale" name="consessionWhale" type="number" class="form-control number-input" value="0">
                  </div>

                  <div class="form-group col-md-2 col-sm-4 col-4">
                    <label for="familyPassWhale">Family Pass <i class="demo-icon icon-awesome-info-circle-icon"
                        data-toggle="tooltip" title="2 Adults & 2 Children" data-placement="top" tab-index="0"></i>
                    </label>
                    <input id="familyPassWhale" name="familyPassWhale" type="number" class="form-control number-input" value="0">
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="d-flex align-items-end justify-content-start ">
                    <div class="form-group flex-fill">
                      <label for="couponCodeWhale">Coupon code</label>
                      <input id="couponCodeWhale" name="couponCodeWhale" class="text-uppercase form-control">
                    </div>

                    <div class="form-group ml-2">
                      <a href="pages/select-ferries.html" class="btn btn-primary">SEARCH</a>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>