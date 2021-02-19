<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php //echo '<pre>'; print_r($routeDetails); echo '</pre>'; exit;
?>

<!-- Search Header -->
      <section class="search-header py-lg-4 py-2 text-center">
        <h1>Rottnest Ferries</h1>
        <a href="<?php echo base_url(); ?>" class="edit-search">Change Search<i class="fas fa-pencil-alt ml-2"></i></a>
      </section>

      <!-- Steps Guide -->
      <section class="booking-navigation py-lg-4 py-2">
        <div class="d-flex flex-row step-groups">
          <a href="#" class="p-2 step step-5 active">
            <div class="d-flex flex-md-row flex-column align-items-center justify-content-center">
              <div class="d-md-block d-none step-jumbo mr-2">1</div>
              <div class="text-uppercase step-title">
                <i class="demo-icon ferry-icon"> </i> <br>
                Ferries
              </div>
            </div>
          </a>

          <a href="#" class="p-2 step step-5 disabled">
            <div class="d-flex flex-md-row flex-column align-items-center justify-content-center">
              <div class="d-md-block d-none step-jumbo mr-2">2</div>
              <div class="text-uppercase step-title">
                <i class="demo-icon packages-icon"></i> <br>
                Luggage
              </div>
            </div>
          </a>

          <a href="#" class="p-2 step step-5 disabled">
            <div class="d-flex flex-md-row flex-column align-items-center justify-content-center">
              <div class="d-md-block d-none step-jumbo mr-2">3</div>
              <div class="text-uppercase step-title">
                <i class="demo-icon extras-icon"></i> <br>
                Extras
              </div>
            </div>
          </a>

          <a href="#" class="p-2 step step-5 disabled">
            <div class="d-flex flex-md-row flex-column align-items-center justify-content-center">
              <div class="d-md-block d-none step-jumbo mr-2">4</div>
              <div class="text-uppercase step-title">
                <i class="fas fa-user "></i> <br>
                Passenger Details
              </div>
            </div>
          </a>

          <a href="#" class="p-2 step step-5 disabled">
            <div class="d-flex flex-md-row flex-column align-items-center justify-content-center">
              <div class="d-md-block d-none step-jumbo mr-2">5</div>
              <div class="text-uppercase step-title">
                <i class="demo-icon dollar-icon"></i> <br>
                Payment
              </div>
            </div>
          </a>
        </div>
      </section>

      <!-- Step 1: Ferry-->
      <section class="ferry-options py-3">
        <div class="row">
          <div class="col-lg-9 col-md-8">
            <h2 class="inline-heading">Choose Your Ferries</h2>

            <div class="ferry-from pb-2">
              <h3>Departing Ferry</h3>
              <div class="row ferry-place-date-wrapper">
                <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12">
                  <h5 class=ferry-place-date><i class="demo-icon ferry-icon"> </i> <?php echo $routeDetails['depart'][0]['StartPortDesc']." to ".$routeDetails['depart'][0]['EndPortDesc']; ?> - <span
                      class="date"><?php echo date("F j, Y", strtotime($routeDetails['depart'][0]['DepartureDate'])); ?></span></h5>
                </div>
                <div class="col-xl-5 col-md-5 d-lg-block d-none">
                  <div class="row pricing-row">
                    <div class="col-sm-6 pricing-wrapper">
                      <div class="fare-header quokka">
                        Promotion Fare <i class="demo-icon icon-awesome-info-circle-icon" data-toggle="tooltip"
                          title="This fare option may incur additonal charges should you need to change your travel date or time. Standard Terms & Connditions apply."
                          data-placement="top" tab-index="0"></i>
                      </div>
                    </div>
                    <div class="col-sm-6 pricing-wrapper">
                      <div class="fare-header everyday">
                        Everyday Fare
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php
			    foreach($routeDetails['depart'] as $depart) { 
				   if(isset($depart['familyRowId']))
						$updateData = $depart['RowId']."-".$depart['DepartureTime']."-".$depart['ticketType']."-".$depart['familyRowId']."-".$depart['familyTicketType'];
				   else
					   $updateData = $depart['RowId']."-".$depart['DepartureTime']."-".$depart['ticketType'];
			  ?>
              <div class="time-options border">
                <div class="row">
                  <div class="time-row col-xl-7 col-lg-7 col-12 py-2">
                    <div class="row">
                      <div class="col-md-3 col-4 time depart-time"><?php echo $depart['DepartureTime'] ?></div>

                      <div class="col-md-6 col-4 ferry-duration">
                        <div class="row mx-0">
                          <div class="col-md-1 col-2 px-0">
                            <i class="demo-icon ferry-icon "></i>
                          </div>
                          <div class="col-md-10 col-9 px-0">
                            <div class="journey-line"></div>
                          </div>
                          <div class="col-1 px-0">
                            <i class="fas fa-map-marker-alt "></i>
                          </div>
                          <!---->
                        </div>
                      </div>

                      <div class="col-md-3 col-4 text-right time"><?php echo $depart['ArrivalTime'] ?></div>
                    </div>

                    <div class="row">
                      <div class="col-6">
                        <span class="origin"> <?php echo $depart['StartPortDesc'] ?> - Departure</span>
                      </div>

                      <div class="col-6 text-right">
                        <span class="origin"> <?php echo $depart['EndPortDesc'] ?> - Arrival</span>
                      </div>
                    </div>

                    <a class="d-lg-block d-none toggle-price" data-toggle="collapse" data-target="#pricingDetailOne"
                      aria-expanded="false" aria-controls="pricingDetailOne">
                      Pricing Details <i class="fas fa-angle-down"></i>
                    </a>
                  </div>

                  <div class="col-xl-5 col-lg-5">
                    <div class="row pricing-row align-items-end">
                      <div class="pricing-toggle-show-md col-3 py-2">
                        <a class="toggle-price text-left d-block" data-toggle="collapse" data-target="#pricingDetailOne"
                          aria-expanded="false" aria-controls="pricingDetailOne">
                          Pricing Details&nbsp;<i class="fas fa-angle-down"></i>
                        </a>
                      </div>
                      <div class="col-lg-12 col-9 h-100">
                        <div class="row h-100">
                          <div class="pricing-wrapper col-6">
                            <div class="fare fare-type quokka-saver p-2">
                              <img src="../../assets/img/quokka.svg"
                                alt="quokka saver icon - quokka head by Rottnest Fast Ferries"
                                class="quokka-icon fare-icon">
                              <div class="fare-title text-uppercase">Quokka Saver</div>
                              <div class="price"><span class="money-currency">$</span><span
                                  class="money-value"><?php echo round(($depart['cost']+$depart['admFee']),2) ?></span> </div>
                              <button class="btn btn-select"> Select</button>
                            </div>
                          </div>
                          <div class="pricing-wrapper col-6">
                            <div class="fare fare-type everyday-fare p-2">
                              <i class="demo-icon ferry-icon"></i>
                              <div class="fare-title text-uppercase">Everyday Fare</div>
                              <div class="price"><span class="money-currency">$</span><span
                                  class="money-value"><?php echo round(($depart['everydayCost']+$depart['admFee']),2) ?></span> </div>
                              <button class="btn btn-select"> Select</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div><!-- //close md-8-->
                </div>

                <!-- Pricing Toggle -->
                <div id="pricingDetailOne" class="collapse pricing-detail" aria-labelledby="pricingDetailOne">
                  <!-- Pricing Header -->
                  <div class="row pricing-detail-row border-top">
                    <div class="col-xl-7 col-lg-7 col-3">
                    </div>
                    <div class="col-xl-5 col-lg-5 col-9">
                      <div class="row align-items-end h-100">
                        <div class="col-6 pricing-wrapper">
                          <div class="h-100 shade quokka-saver px-2 pt-2 text-right text-uppercase">
                            Quokka Saver
                          </div>
                        </div>
                        <div class="col-6 pricing-wrapper">
                          <div class="shade everyday-fare px-2 pt-2 text-right text-uppercase">
                            Everyday Fare
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Pricing Row -->
                  <div class="row pricing-detail-row">
                    <div class="col-xl-7 col-lg-7 col-3">
                      Adult (x1)
                    </div>
                    <div class="col-xl-5 col-lg-5 col-9">
                      <div class="row align-items-center h-100">
                        <div class="col-6 pricing-wrapper">
                          <div class="h-100 shade quokka-saver px-2 text-right">
                            <span class="money-currency">$</span><span class="money-value"
                              data-attribute="adult-price">27.00</span>
                          </div>
                        </div>
                        <div class="col-6 pricing-wrapper">
                          <div class="h-100 shade everyday-fare px-2 text-right">
                            <span class="money-currency">$</span><span class="money-value"
                              data-attribute="adult-price">34.50</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row pricing-detail-row">
                    <div class="col-xl-7 col-lg-7 col-3">
                      Child (x1)
                    </div>
                    <div class="col-xl-5 col-lg-5 col-9">
                      <div class="row align-items-center h-100">
                        <div class="col-6 pricing-wrapper">
                          <div class="h-100 shade quokka-saver px-2 text-right">
                            <span class="money-currency">$</span><span class="money-value"
                              data-attribute="child-price">18.00</span>
                          </div>
                        </div>
                        <div class="col-6 pricing-wrapper">
                          <div class="h-100 shade everyday-fare px-2 text-right">
                            <span class="money-currency">$</span><span class="money-value"
                              data-attribute="child-price">20.50</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row pricing-detail-row subtotal align-items-center">
                    <div class="col-xl-7 col-lg-7 col-3 text-uppercase">
                      Subtotal
                    </div>
                    <div class="col-xl-5 col-lg-5 col-9">
                      <div class="row align-items-center h-100">
                        <div class="col-6 pricing-wrapper">
                          <div class="h-100 shade quokka-saver px-2 text-right">
                            <span class="money-currency">$</span><span class="money-value">45.00</span>
                          </div>
                        </div>
                        <div class="col-6 pricing-wrapper">
                          <div class="h-100 shade everyday-fare px-2 text-right">
                            <span class="money-currency">$</span><span class="money-value">55.00</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              </div>

			<?php } ?>
				
              <div class="info-bar pb-2">
                <p>Our Promotion Fare (Fast Fare and Quokka Fare) are only available at limited seats. This fare option may incur additonal charges should you need to change your travel date or time. Standard Terms & Connditions apply.</p>
              </div>
            </div>

            <div class="ferry-to pb-2">
              <h3>Returning Ferry</h3>
              <div class="row ferry-place-date-wrapper">
                <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12">
					<h5 class=ferry-place-date><i class="demo-icon returning-ferry-icon"> </i> <?php echo $routeDetails['return'][0]['StartPortDesc']." to ".$routeDetails['return'][0]['EndPortDesc']; ?> - <span
                      class="date"><?php echo date("F j, Y", strtotime($routeDetails['return'][0]['DepartureDate'])); ?></span></h5>
                </div>
                <div class="col-xl-5 col-md-5 d-lg-block d-none">
                  <div class="row pricing-row">
                    <div class="col-sm-6 pricing-wrapper">
                      <div class="fare-header quokka">
                        Promotion Fare <i class="demo-icon icon-awesome-info-circle-icon" data-toggle="tooltip"
                          title="This fare option may incur additonal charges should you need to change your travel date or time. Standard Terms & Connditions apply."
                          data-placement="top" tab-index="0"></i>
                      </div>
                    </div>
                    <div class="col-sm-6 pricing-wrapper">
                      <div class="fare-header everyday">
                        Everyday Fare
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php
			  foreach($routeDetails['return'] as $depart) { 
				   if(isset($depart['familyRowId']))
						$updateData = $depart['RowId']."-".$depart['DepartureTime']."-".$depart['ticketType']."-".$depart['familyRowId']."-".$depart['familyTicketType'];
				   else
					   $updateData = $depart['RowId']."-".$depart['DepartureTime']."-".$depart['ticketType'];
			  ?>
              <div class="time-options border">
                <div class="row">
                  <div class="time-row col-xl-7 col-lg-7 col-12 py-2">
                    <div class="row">
                      <div class="col-md-3 col-4 time return-time"><?php echo $depart['DepartureTime'] ?></div>

                      <div class="col-md-6 col-4 ferry-duration">
                        <div class="row mx-0">
                          <div class="col-md-1 col-2 px-0">
                            <i class="demo-icon ferry-icon "></i>
                          </div>
                          <div class="col-md-10 col-9 px-0">
                            <div class="journey-line"></div>
                          </div>
                          <div class="col-1 px-0">
                            <i class="fas fa-map-marker-alt "></i>
                          </div>
                          <!---->
                        </div>
                      </div>

                      <div class="col-md-3 col-4 time text-right"><?php echo $depart['ArrivalTime'] ?></div>
                    </div>

                    <div class="row">
                      <div class="col-6 origin">
                        <?php echo $depart['StartPortDesc'] ?> - Departure
                      </div>
                      <div class="col-6 origin text-right">
                        <?php echo $depart['EndPortDesc'] ?> - Arrival
                      </div>
                    </div>


                    <a class="d-lg-block d-none toggle-price" data-toggle="collapse" data-target="#pricingDetailThree"
                      aria-expanded="false" aria-controls="pricingDetailThree">
                      Pricing Details <i class="fas fa-angle-down"></i>
                    </a>
                  </div>

                  <div class="col-xl-5 col-lg-5">
                    <div class="row pricing-row align-items-end">
                      <div class="pricing-toggle-show-md col-3 py-2">
                        <a class="toggle-price text-left d-block" data-toggle="collapse"
                          data-target="#pricingDetailThree" aria-expanded="false" aria-controls="pricingDetailThree">
                          Pricing Details&nbsp;<i class="fas fa-angle-down"></i>
                        </a>
                      </div>
                      <div class="col-lg-12 col-9 h-100">
                        <div class="row h-100">
                          <div class="pricing-wrapper col-6">
                            <div class="fare fare-type fast-fare p-2">
                              <i class="demo-icon icon-fast-fare"></i>
                              <div class="fare-title text-uppercase">Fast Fare</div>
                              <div class="price"><span class="money-currency">$</span><span
                                  class="money-value"><?php echo round(($depart['cost']+$depart['admFee']),2) ?></span> </div>
                              <button class="btn btn-select"> Select</button>
                            </div>
                          </div>
                          <div class="pricing-wrapper col-6">
                            <div class="fare fare-type everyday-fare p-2">
                              <i class="demo-icon ferry-icon"></i>
                              <div class="fare-title text-uppercase">Everyday Fare</div>
                              <div class="price"><span class="money-currency">$</span><span
                                  class="money-value"><?php echo round(($depart['everydayCost']+$depart['admFee']),2) ?></span> </div>
                              <button class="btn btn-select"> Select</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div><!-- //close md-8-->
                </div>

                <!-- Pricing Toggle -->
                <div id="pricingDetailThree" class="collapse pricing-detail" aria-labelledby="pricingDetailThree">
                  <!-- Pricing Header -->
                  <div class="row pricing-detail-row border-top">
                    <div class="col-xl-7 col-lg-7 col-3">
                    </div>
                    <div class="col-xl-5 col-lg-5 col-9">
                      <div class="row align-items-end h-100">
                        <div class="col-6 pricing-wrapper">
                          <div class="h-100 shade fast-fare px-2 pt-2 text-right text-uppercase">
                            Fast Fare
                          </div>
                        </div>
                        <div class="col-6 pricing-wrapper">
                          <div class="shade everyday-fare px-2 pt-2 text-right text-uppercase">
                            Everyday Fare
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Pricing Row -->
                  <div class="row pricing-detail-row">
                    <div class="col-xl-7 col-lg-7 col-3">
                      Adult (x1)
                    </div>
                    <div class="col-xl-5 col-lg-5 col-9">
                      <div class="row align-items-center h-100">
                        <div class="col-6 pricing-wrapper">
                          <div class="h-100 shade fast-fare px-2 text-right">
                            <span class="money-currency">$</span><span class="money-value"
                              data-attribute="adult-price">15.00</span>
                          </div>
                        </div>
                        <div class="col-6 pricing-wrapper">
                          <div class="h-100 shade everyday-fare px-2 text-right">
                            <span class="money-currency">$</span><span class="money-value"
                              data-attribute="adult-price">34.50</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row pricing-detail-row">
                    <div class="col-xl-7 col-lg-7 col-3">
                      Child (x1)
                    </div>
                    <div class="col-xl-5 col-lg-5 col-9">
                      <div class="row align-items-center h-100">
                        <div class="col-6 pricing-wrapper">
                          <div class="h-100 shade fast-fare px-2 text-right">
                            <span class="money-currency">$</span><span class="money-value"
                              data-attribute="child-price">10.00</span>
                          </div>
                        </div>
                        <div class="col-6 pricing-wrapper">
                          <div class="h-100 shade everyday-fare px-2 text-right">
                            <span class="money-currency">$</span><span class="money-value"
                              data-attribute="child-price">20.50</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row pricing-detail-row subtotal align-items-center">
                    <div class="col-xl-7 col-lg-7 col-3 text-uppercase">
                      Subtotal
                    </div>
                    <div class="col-xl-5 col-lg-5 col-9">
                      <div class="row align-items-center h-100">
                        <div class="col-6 pricing-wrapper">
                          <div class="h-100 shade fast-fare px-2 text-right">
                            <span class="money-currency">$</span><span class="money-value">27.00</span>
                          </div>
                        </div>
                        <div class="col-6 pricing-wrapper">
                          <div class="h-100 shade everyday-fare px-2 text-right">
                            <span class="money-currency">$</span><span class="money-value">34.50</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
			  <?php } ?>
             <div class="info-bar pb-4">
                <p>Our Promotion Fare (Fast Fare and Quokka Fare) are only available at limited seats. This fare option may incur additonal charges should you need to change your travel date or time. Standard Terms & Connditions apply.</p>
              </div>
            </div>


            <!-- @pickup @Bus pickup question-->
            <div id="busPopupTrigger" class="alert alert-info pb-4" role="alert" style="display: none;">
              <h3>Bus pickup</h3>
              <div>Free coach transfers are available from selected hotels to our ferry terminal at Hillarys Boat
                Harbour.</div>
              <div class="bus-cta">
                <a id="busModalTrigger" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#busModal">Add
                  Bus Pickup</a>
                <a id="removeBusPickup" class="btn btn-remove btn-xs" style="display: none;"
                  v-on:click="removeBus">Remove bus pickup</a>
              </div>
            </div>

            <!-- Pickup Modal -->
            <!-- Bus Modal -->
            <div class="modal fade" id="busModal" tabindex="-1" role="dialog" aria-labelledby="busModal"
              aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Bus pickup</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <label>Select your closest pickup point from the list below</label>
                    <v-select :options="morningPickups" v-model="pickupPoint" placeholder="Select a pickup point"
                      id="morningPickups"></v-select :value="pickupPoint.label">
                    <v-select :options="lateMorningPickups" v-model="pickupPoint" placeholder="Select a pickup point"
                      id="lateMorningPickups"></v-select :value="pickupPoint.label">
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-cancel" data-dismiss="modal">CANCEL</button>
                    <button id="saveBus" type="button" class="btn btn-sm btn-primary"
                      v-bind:class="{ disabled: !pickupPoint }" v-on:click="saveBus">SAVE</button>
                  </div>
                </div>
              </div>
            </div>


          </div> <!-- col-md-9-->



          <!-- Sidebar ; Booking Summary-->
          <div class="col-lg-3 col-md-4">
            <!-- Booking Summary-->
            <div class="booking-summary-wrapper">
              <h2> Booking Summary</h2>
            </div>

            <div class="summary-accordion" id="accordionExample">
              <div class="card">
                <div class="card-header">
                  <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#admissionFee"
                    aria-expanded="true" aria-controls="admissionFee">
                    <i class="demo-icon icon-title icon-ticket"></i>
                    Admission Fee <i data-toggle="tooltip" title="" data-placement="top" tab-index="0"
                      data-original-title="The entrance fee to an A-Class nature reserve and contributes to the conservation of the island. This is a government tax payable by all visitors to the island and is collected by the ferry companies on behalf of the Rottnest Island Authority"
                      class="demo-icon icon-awesome-info-circle-icon ml-2 text-white"></i>
                    <div class="icon-toggle">
                      <i class="fas fa-chevron-up"></i>
                    </div>
                  </button>
                </div>

                <div id="admissionFee" class="booking-body collapse show" aria-labelledby="admissionFee">
                  <table class="booking-table">
                    <tbody class="summary-item">
                      <tr>
                        <td class="table-item item-pass">Adult (x1)</td>
                        <td class="table-item item-pass-price"><span class="money-currency">$</span><span
                            data-attribute="adult-price" class="money-value">19.50</span></td>
                      </tr>
                      <tr>
                        <td class="table-item item-pass">Kid (x1)</td>
                        <td class="table-item item-pass-price"><span class="money-currency">$</span><span
                            data-attribute="child-price" class="money-value">7.00</span></td>
                      </tr>
                      <tr>
                        <td colspan="2">
                          <div class="line"></div>
                        </td>
                      </tr>
                      <tr>
                        <td class="table-item item-subtotal item-last">Subtotal </td>
                        <td class="table-item item-price item-subtotal item-last"><span
                            class="money-currency">$</span><span class="money-value">26.50</span></td>
                      </tr>
                    </tbody>

                  </table>
                </div>
              </div>

              <!-- @Ferries-->
              <div class="card">
                <div class="card-header">
                  <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#ferries" aria-expanded="true" aria-controls="ferries">
                    <i class="demo-icon icon-title ferry-icon"></i>
                    Ferries
                    <div class="icon-toggle">
                      <i class="fas fa-chevron-up"></i>
                    </div>
                  </button>
                </div>
                <div id="ferries" class="booking-body collapse show" aria-labelledby="ferries">
                  <table class="booking-table">
                    <tbody class="summary-item">
                      <tr>
                        <td colspan="2" class="table-item item-heading">Hillarys to Rottnest Ferry Ticket</td>
                      </tr>
                      <tr>
                        <td colspan="2" class="table-item item-subheading">Sun, 03 Jan 2021 <span
                            class="write-depart-time"></span></td>
                      </tr>
                      <tr class="temp-departure-show">
                        <td colspan="2" class="table-item item-fare-type item-depart-fare-type">
                          <span class="quokka-saver">Quokka Saver</span></td>
                      </tr>
                      <tr class="temp-departure-show">
                        <td class="table-item item-pass">Adult (x1)</td>
                        <td class="table-item item-pass-price"><span class="money-currency">$</span><span
                            data-attribute="adult-price" class="money-value">0.00</span></td>
                      </tr>
                      <tr class="temp-departure-show">
                        <td class="table-item item-pass">Child (x1)</td>
                        <td class="table-item item-pass-price"><span class="money-currency">$</span><span
                            data-attribute="child-price" class="money-value">0.00</span></td>
                      </tr>
                      <tr class="temp-departure-show">
                        <td colspan="2">
                          <div class="line"></div>
                        </td>
                      </tr>
                      <tr class="temp-departure-show">
                        <td class="table-item item-subtotal item-last">Subtotal</td>
                        <td class="table-item item-price item-subtotal item-last"><span
                            class="money-currency">$</span><span class="money-value">0.00</span></td>
                      </tr>
                    </tbody>

                    <tbody class="summary-item">
                      <tr>
                        <td colspan="2" class="table-item item-heading">Rottnest to Hillarys Ferry Ticket</td>
                      </tr>
                      <tr>
                        <td colspan="2" class="table-item item-subheading">Tue, 05 Jan 2021 <span
                            class="write-return-time"></span></td>
                      </tr>
                      <tr class="temp-return-show">
                        <td colspan="2" class="table-item item-fare-type item-return-fare-type">
                          <span class="everyday-fare"> Everyday Fare</span></td>
                      </tr>
                      <tr class="temp-return-show">
                        <td class="table-item item-pass">Adult (x1)</td>
                        <td class="table-item item-pass-price"><span class="money-currency">$</span><span
                            data-attribute="adult-price" class="money-value">0.00</span></td>
                      </tr>
                      <tr class="temp-return-show">
                        <td class="table-item item-pass">Child (x1)</td>
                        <td class="table-item item-pass-price"><span class="money-currency">$</span><span
                            data-attribute="child-price" class="money-value">0.00</span></td>
                      </tr>
                      <tr class="temp-return-show">
                        <td colspan="2">
                          <div class="line"></div>
                        </td>
                      </tr>
                      <tr class="temp-return-show">
                        <td class="table-item item-subtotal item-last">Subtotal</td>
                        <td class="table-item item-price item-subtotal item-last"><span
                            class="money-currency">$</span><span class="money-value">55.00</span></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>


              <!-- @BusPickup-->
              <div class="card temp-bus-show">
                <div class="card-header">
                  <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#bus"
                    aria-expanded="true" aria-controls="bus">
                    <i class="demo-icon icon-title icon-bus"></i>
                    Bus pickup
                    <div class="icon-toggle">
                      <i class="fas fa-chevron-down"></i>
                    </div>
                  </button>
                </div>
                <div id="bus" class="booking-body collapse show" aria-labelledby="bus">
                  <table class="booking-table">
                    <tbody class="summary-item">
                      <tr>
                        <td class="table-item item-subheading print-pickup-point" colspan="2"></td>
                      </tr>
                      <tr>
                        <td class="table-item item-pass">Adult (x1)</td>
                        <td class="table-item item-pass-price"> <span class="money-currency">$</span><span
                            class="money-value" data-attribute="adult-price">0.00</span></td>
                      </tr>
                      <tr>
                        <td class="table-item item-pass">Child (x1)</td>
                        <td class="table-item item-pass-price"> <span class="money-currency">$</span><span
                            class="money-value" data-attribute="child-price">0.00</span></td>
                      </tr>
                      <tr>
                        <td colspan="2">
                          <div class="line"></div>
                        </td>
                      </tr>
                      <tr>
                        <td class="table-item item-subtotal item-last">Subtotal</td>
                        <td class="table-item item-price item-subtotal item-last"> <span
                            class="money-currency">$</span><span class="money-value">0.00</span></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>


              <!-- @Coupon-->
              <div class="card">
                <div class="card-header">
                  <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#coupon" aria-expanded="true" aria-controls="coupon">
                  <i class="icon-title fas fa-tag"></i>
                  Coupon Discount
                    <div class="icon-toggle">
                      <i class="fas fa-chevron-up"></i>
                    </div>
                  </button>
                </div>

                <div id="coupon" class="booking-body collapse show" aria-labelledby="ferries">
                  <table class="booking-table">
                    <tbody class="summary-coupon">
                      <tr>
                        <td class="table-item item-last item-heading">
                          Coupon Code
                        </td>
                        <td id="couponRemove" class="table-item item-last text-right item-heading">
                          <button id="removeCouponBtn" class="btn btn-primary btn-xs" style="display: none;">Remove</button>
                        </td>
                      </tr>
                      <tr class="coupon-input-row">
                        <td colspan="2" class="table-item coupon-input-col form-group item-last"><span class="input-group"><input type="text" id="couponInput" placeholder="Coupon Code" aria-label="Coupon Code" aria-describedby="Coupon Code" class="text-uppercase form-control">
                            <button id="couponBtn" class="btn btn-primary btn-xs">Apply</button></span>
                          </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- @Total-->
              <div class="grand-total">
                <table class="booking-table">
                  <tbody>
                    <tr>
                      <td class="table-item item-total">Current Total</td>
                      <td class="table-item item-total item-price"><span class="money-currency">$</span> <span class="money-value">26.50</span></td>
                    </tr>
                  </tbody>


                </table>

              </div>

          </div>

        </div>
      </section>



<!------------------------------------------------------>
<!--<header class="title">
	<div></div>
	<h1>Booking Details</h1>
	<nav class="tabs">
		<ul>
			<li class="active item">Time</li>
			<li class="item">Extras</li>
			<li class="item">Passengers</li>
			<li class="item">Itinerary</li>
			<li>Payment</li>
		</ul>
	</nav>
</header>
<form>
	<div class="booking_data">
	<?php if(isset($routeDetails)) { ?>
		<div class="helpers">
			<h2 class="departure_times" id="dep_head">
			   <i class="fa fa-ship" aria-hidden="true"></i>Departure Times
			</h2>
			<h2 class="departure_times" id="ret_head" style="display:none;">
			   <i class="fa fa-ship" aria-hidden="true"></i>Return Times 
			</h2>
			<div class="booking_overview" style="display:none;"> 
				<h5>
					<span>Sub Total: </span>
					<em>$<span id="amountToPay"></span></em>
				</h5>
				<div class="summary">
					<h3>Booking Summary</h3>
					<ul class="summary_table">
						<li class="head">
							<ul>
								<li>Item</li>
								<li>
									<span>Admission Fees </span>
									<div class="tooltip">
										<span class="tooltip_title">Info</span>
										<div class="modal admission_fee">
											<div class="modal_content">
												<h2 class="modal_title">Admission Fee Information</h2>
												<a class="modal-close" href="/">Close</a>
												<div class="info">
													<p>The Rottnest Island Admission Fee is the entrance fee to an A-Class nature reserve and contributes to the conservation of the island. This is a government tax payable by all visitors to the island and is collected by the ferry companies on behalf of the Rottnest Island Authority</p>
												</div>
											</div>
										</div>
									</div>
								</li>
								<li>Cost</li>
							</ul>
						</li>
						<li>
							<ul>
								<li>
									<strong>
										<span>2x</span>
										<span> Passengers</span>
									</strong>
								</li>
								<li>$38.00</li>
								<li>$136.00</li>
							</ul>
						</li>
					</ul>
					<ul class="totals">
						<li>
							<span>Subtotal </span>
							<span>$174.00</span>
						</li>
					</ul>
				</div>
			</div> 
		</div>
		<div id="departure_area"> 
		<!---Departure Start---->
			<!--<ul class="date_ranges">
			    <?php for($d = -2; $d <= 2; $d++) {
					  if($d==0)
						$class="active";
				     else
						$class="inactive";
					
					$currentDate = strtotime(date('Y-m-d'));
					$bookingDate = strtotime($routeDetails['depart'][0]['DepartureDate']. ' '.$d.' day');
					if($bookingDate > $currentDate){
				?>
					<li class="enabled">
						<a class="<?=$class; ?>"  href="javascript:void(0);" onclick="getDeparture('<?php echo date("Y-m-d", strtotime($routeDetails['depart'][0]['DepartureDate']. ' '.$d.' day')); ?>','false')">
							<?php echo date("l jS", strtotime($routeDetails['depart'][0]['DepartureDate']. ' '.$d.' day')); ?>
						</a>
					</li>
				<?php } else { ?>
					<li class="enabled nobooking">
							<?php echo date("l jS", strtotime($routeDetails['depart'][0]['DepartureDate']. ' '.$d.' day')); ?>
					</li>
				<?php }} ?>
			</ul>
			<ul class="booking_table departures" id="table">
				<li class="head"><?php echo $routeDetails['depart'][0]['StartPortDesc']." - ".$routeDetails['depart'][0]['EndPortDesc']; ?></li>
				<li class="head">
					<ul>
						<li class="time">Depart</li>
						<li class="time">Arrive</li>
						<li class="cost">Everyday Cost</li>
						<li class="cost">Cost</li>
						<li class="fee">
						  <span>Admission Fee<span class="glyphicon glyphicon-info-sign"></span>   </span>
						  <div class="tooltip">
							<span class="tooltip_title">Info</span>
						  </div>
						</li>
						<li>Total</li>
					</ul>
				</li>
				<?php 
				/* echo "<pre>";
				print_r($routeDetails);
				exit; */
				$di = 0;
				foreach($routeDetails['depart'] as $depart) { 
					if(isset($depart['familyRowId']))
						$updateData = $depart['RowId']."-".$depart['DepartureTime']."-".$depart['ticketType']."-".$depart['familyRowId']."-".$depart['familyTicketType'];
				   else
					   $updateData = $depart['RowId']."-".$depart['DepartureTime']."-".$depart['ticketType'];
				?>						
					<li class="inactive" id="depart_<?php echo $depart['RowId']; ?>_<?php echo $di ?>" onclick="updateBookingRow('<?php echo $updateData ; ?>', 'false'); selectRow('depart', '<?php echo $depart['RowId']; ?>_<?php echo $di ?>')">
					 <ul>
						<li class="time">
						  <span><?php echo $depart['DepartureTime'] ?></span>
						  <span></span>
						</li>
						<li class="time"><?php echo $depart['ArrivalTime'] ?></li>
						<li class="cost">
						  <span>$<?php echo $depart['everydayCost'] ?></span>
						  <span></span>
						</li>
						<li class="cost">
						  <span><?php  if($depart['cost'] < $depart['everydayCost']) echo "$".$depart['cost']; else echo "NA"; ?></span>
						  <span></span>
						</li>
						<li class="fee">$<?php echo $depart['admFee'] ?></li>
						<li>
						  <span></span>
						  <span>$<?php echo round(($depart['cost']+$depart['admFee']),2) ?></span>
						</li>
					  </ul>
					</li>
				<?php $di++; } ?>	
			</ul>
		</div>
		<!--Departure End ---->
		<!-- Return Start --->
		<!--<div id="return_area" style="display:none;"> 
			<ul class="date_ranges">
			    <?php for($r = -2; $r <=2; $r++) { 
				  if($r==0)
					  $class="active";
				  else
					  $class="inactive";

				  $departDate = strtotime($routeDetails['depart'][0]['DepartureDate']);
				  $rtnDate = strtotime($routeDetails['return'][0]['DepartureDate']. ' '.$r.' day');
					if($rtnDate >= $departDate){
				?>
					<li class="enabled">
						<a class="<?=$class; ?>"  href="javascript:void(0);" onclick="getDeparture('<?php echo date("Y-m-d", strtotime($routeDetails['return'][0]['DepartureDate']. ' '.$r.' day')); ?>','true')">
							<?php echo date("l jS", strtotime($routeDetails['return'][0]['DepartureDate']. ' '.$r.' day')); ?>
						</a>
					</li>
				<?php } else { ?>
					<li class="enabled nobooking">
							<?php echo date("l jS", strtotime($routeDetails['return'][0]['DepartureDate']. ' '.$r.' day')); ?>
					</li>
				<?php } }?>
				
			</ul>
			<ul class="booking_table departures" id="table">
				<li class="head"><?php echo $routeDetails['return'][0]['StartPortDesc']." - ".$routeDetails['return'][0]['EndPortDesc']; ?></li>
				<li class="head">
					<ul>
						<li class="time">Depart</li>
						<li class="time">Arrive</li>
						<li class="cost">Everyday Cost</li>
						<li class="cost">Cost</li>
						<li class="fee">
						  <span>Admission Fee<span class="glyphicon glyphicon-info-sign"></span>   </span>
						  <div class="tooltip">
							<span class="tooltip_title">Info</span>
						  </div>
						</li>
						<li>Total</li>
					</ul>
				</li>
				<?php 
				$ri = 0;
				foreach($routeDetails['return'] as $depart) { 
				   if(isset($depart['familyRowId']))
						$updateData = $depart['RowId']."-".$depart['DepartureTime']."-".$depart['ticketType']."-".$depart['familyRowId']."-".$depart['familyTicketType'];
				   else
					   $updateData = $depart['RowId']."-".$depart['DepartureTime']."-".$depart['ticketType'];
				?>						
					<li class="inactive" id="return_<?php echo $depart['RowId']; ?>_<?php echo $ri ?>" onclick="updateBookingRow('<?php echo $updateData ; ?>', 'true'); selectRow('return', '<?php echo $depart['RowId']; ?>_<?php echo $ri ?>')">
					 <ul>
						<li class="time">
						  <span><?php echo $depart['DepartureTime'] ?></span>
						  <span></span>
						</li>
						<li class="time"><?php echo $depart['ArrivalTime'] ?></li>
						<li class="cost">
						  <span>$<?php echo $depart['everydayCost'] ?></span>
						  <span></span>
						</li>
						<li class="cost">
						  <span><?php  if($depart['cost'] < $depart['everydayCost']) echo "$".$depart['cost']; else echo "NA"; ?></span>
						  <span></span>
						</li>
						<li class="fee">$<?php echo $depart['admFee'] ?></li>
						<li>
						  <span></span>
						  <span>$<?php echo round(($depart['cost']+$depart['admFee']),2) ?></span>
						</li>
					  </ul>
				</li>
				<?php $ri++; } ?>	
			</ul>
		</div>
		<!--Return End -->
	<?php } else { ?>
				<div class="responseError"><?php echo $routeError; ?></div>
	<?php  } ?>
	<!--</div>
	<input type="hidden" id="current_position" value="depart" />
	<div class="actions">
		<a class="back_link" href="javascript:void(0);" onclick="prev()">
		  <span class="link_back"></span>  < Back
		</a>
		<button  class="button" onclick="next(); return false;">Next</button>
	</div>
</form>-->