<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php //echo '<pre>'; print_r($routeDetails); echo '</pre>';
?>
<main id="app" class="body-wrapper wizard">
    <div id="booking-section1" class="container">
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
		<?php if(isset($routeDetails)) { ?>
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
			  $d = 0;
			    foreach($routeDetails['depart'] as $depart) 
				{ 
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

                    <a class="d-lg-block d-none toggle-price" data-toggle="collapse" data-target="#pricingDetail<?php echo $d; ?>"
                      aria-expanded="false" aria-controls="pricingDetail<?php echo $d; ?>">
                      Pricing Details <i class="fas fa-angle-down"></i>
                    </a>
                  </div>

                  <div class="col-xl-5 col-lg-5">
                    <div class="row pricing-row align-items-end">
                      <div class="pricing-toggle-show-md col-3 py-2">
                        <a class="toggle-price text-left d-block" data-toggle="collapse" data-target="#pricingDetail<?php echo $d; ?>"
                          aria-expanded="false" aria-controls="pricingDetail<?php echo $d; ?>">
                          Pricing Details&nbsp;<i class="fas fa-angle-down"></i>
                        </a>
                      </div>
                      <div class="col-lg-12 col-9 h-100">
                        <div class="row h-100">
						<?php if($depart['cost'] < $depart['everydayCost']) { ?>
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
						<?php } else { ?>
						   <div class="fare fare-type quokka-saver unavailable p-2">
                              <div class="fare-title text-uppercase">Promotion Fare</div>
                              <div class="unavailable-msg"> NOT AVAILABLE <br> for this time</div>
                           </div>
						<?php } ?>
                          <div class="pricing-wrapper col-6">
                            <div class="fare fare-type everyday-fare p-2">
                              <i class="demo-icon ferry-icon"></i>
                              <div class="fare-title text-uppercase">Everyday Fare</div>
                              <div class="price"><span class="money-currency">$</span><span
                                  class="money-value"><?php echo round(($depart['everydayCost']+$depart['admFee']),2) ?></span> </div>
							  <?php if($depart['cost'] >= $depart['everydayCost']) { ?>
                                   <button class="btn btn-select"> Select</button>
							  <?php } ?>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div><!-- //close md-8-->
                </div>

                <!-- Pricing Toggle -->
                <div id="pricingDetail<?php echo $d; ?>" class="collapse pricing-detail" aria-labelledby="pricingDetail<?php echo $d; ?>">
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
				  <?php if(isset($depart['discounted']) || isset($depart['regular'])) { 
					        foreach($depart['regular'] as $k=>$v)
							{
								if($depart['regular'][$k]['count'] == 0)
									continue;
				  ?>
					  <div class="row pricing-detail-row">
						<div class="col-xl-7 col-lg-7 col-3">
						  <?php echo $passengerType[$k];?> (<?php echo $depart['regular'][$k]['count']; ?>)
						</div>
						<div class="col-xl-5 col-lg-5 col-9">
						  <div class="row align-items-center h-100">
							<div class="col-6 pricing-wrapper">
							  <div class="h-100 shade quokka-saver px-2 text-right">
							  <?php if($depart['discounted'][$k]['count'] > 0) { ?>
								<span class="money-currency">$</span><span class="money-value"
								  data-attribute="adult-price"><?php echo $depart['discounted'][$k]['total']; ?></span>
							  <?php } ?>
							  </div>
							</div>
							<div class="col-6 pricing-wrapper">
							  <div class="h-100 shade everyday-fare px-2 text-right">
							  <?php if($depart['regular'][$k]['count'] > 0) { ?>
								<span class="money-currency">$</span><span class="money-value"
								  data-attribute="adult-price"><?php echo $depart['regular'][$k]['total']; ?></span>
							   <?php } ?>
							  </div>
							</div>
						  </div>
						</div>
					  </div>
					<?php } } if(isset($depart['admFee']) && $depart['admFee'] > 0) { ?>
                  <div class="row pricing-detail-row">
                    <div class="col-xl-7 col-lg-7 col-3">
                      Admission Fee
                    </div>
                    <div class="col-xl-5 col-lg-5 col-9">
                      <div class="row align-items-center h-100">
                        <div class="col-6 pricing-wrapper">
                          <div class="h-100 shade quokka-saver px-2 text-right">
                            <span class="money-currency">$</span><span class="money-value"
                              data-attribute="child-price"><?php echo $depart['admFee']; ?></span>
                          </div>
                        </div>
                        <div class="col-6 pricing-wrapper">
                          <div class="h-100 shade everyday-fare px-2 text-right">
                            <span class="money-currency">$</span><span class="money-value"
                              data-attribute="child-price"><?php echo $depart['admFee']; ?></span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
					<?php } ?>
                  <div class="row pricing-detail-row subtotal align-items-center">
                    <div class="col-xl-7 col-lg-7 col-3 text-uppercase">
                      Subtotal
                    </div>
                    <div class="col-xl-5 col-lg-5 col-9">
                      <div class="row align-items-center h-100">
                        <div class="col-6 pricing-wrapper">
                          <div class="h-100 shade quokka-saver px-2 text-right">
                            <span class="money-currency">$</span><span class="money-value">
							<?php
							  if(!isset($depart['familyNetPrice']))
								  $depart['familyNetPrice'] = 0;
							 echo number_format((float)($depart['familyNetPrice'] + $depart['admFee'] + $depart['cost']), 2, '.', '');
							?>
							</span>
                          </div>
                        </div>
                        <div class="col-6 pricing-wrapper">
                          <div class="h-100 shade everyday-fare px-2 text-right">
                            <span class="money-currency">$</span><span class="money-value">
							<?php
							  if(!isset($depart['familyNetPrice']))
								  $depart['familyNetPrice'] = 0;
							  echo number_format((float)($depart['familyNetPrice'] + $depart['admFee'] + $depart['everydayCost']), 2, '.', '');
							  
							?>
							</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              </div>

			<?php $d++; } ?>
				
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
			  $r = 0;
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


                    <a class="d-lg-block d-none toggle-price" data-toggle="collapse" data-target="#pricingDetail<?php echo $r; ?>"
                      aria-expanded="false" aria-controls="pricingDetail<?php echo $r; ?>">
                      Pricing Details <i class="fas fa-angle-down"></i>
                    </a>
                  </div>

                  <div class="col-xl-5 col-lg-5">
                    <div class="row pricing-row align-items-end">
                      <div class="pricing-toggle-show-md col-3 py-2">
                        <a class="toggle-price text-left d-block" data-toggle="collapse"
                          data-target="#pricingDetail<?php echo $r; ?>" aria-expanded="false" aria-controls="pricingDetail<?php echo $r; ?>">
                          Pricing Details&nbsp;<i class="fas fa-angle-down"></i>
                        </a>
                      </div>
                      <div class="col-lg-12 col-9 h-100">
                        <div class="row h-100">
						<?php if($depart['cost'] < $depart['everydayCost']) { ?>
                          <div class="pricing-wrapper col-6">
                            <div class="fare fare-type fast-fare p-2">
                              <i class="demo-icon icon-fast-fare"></i>
                              <div class="fare-title text-uppercase">Fast Fare</div>
                              <div class="price"><span class="money-currency">$</span><span
                                  class="money-value"><?php echo round(($depart['cost']+$depart['admFee']),2) ?></span> </div>
                              <button class="btn btn-select"> Select</button>
                            </div>
                          </div>
						  <?php } else { ?>
						   <div class="fare fare-type quokka-saver unavailable p-2">
                              <div class="fare-title text-uppercase">Promotion Fare</div>
                              <div class="unavailable-msg"> NOT AVAILABLE <br> for this time</div>
                           </div>
						<?php } ?>
                          <div class="pricing-wrapper col-6">
                            <div class="fare fare-type everyday-fare p-2">
                              <i class="demo-icon ferry-icon"></i>
                              <div class="fare-title text-uppercase">Everyday Fare</div>
                              <div class="price"><span class="money-currency">$</span><span
                                  class="money-value"><?php echo round(($depart['everydayCost']+$depart['admFee']),2) ?></span> </div>
							  <?php if($depart['cost'] >= $depart['everydayCost']) { ?>
								<button class="btn btn-select"> Select</button>
							  <?php } ?>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div><!-- //close md-8-->
                </div>

                <!-- Pricing Toggle -->
                <div id="pricingDetail<?php echo $r; ?>" class="collapse pricing-detail" aria-labelledby="pricingDetail<?php echo $r; ?>">
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
				  <?php if(isset($depart['discounted']) || isset($depart['regular'])) 
				  { 
					        foreach($depart['regular'] as $k=>$v){
								if($depart['regular'][$k]['count'] == 0)
									continue;
				  ?>
				   <div class="row pricing-detail-row">
						<div class="col-xl-7 col-lg-7 col-3">
						  <?php echo $passengerType[$k];?> (<?php echo $depart['regular'][$k]['count']; ?>)
						</div>
						<div class="col-xl-5 col-lg-5 col-9">
						  <div class="row align-items-center h-100">
							<div class="col-6 pricing-wrapper">
							  <div class="h-100 shade quokka-saver px-2 text-right">
							  <?php if($depart['discounted'][$k]['count'] > 0) { ?>
								<span class="money-currency">$</span><span class="money-value"
								  data-attribute="adult-price"><?php echo $depart['discounted'][$k]['total']; ?></span>
							  <?php } ?>
							  </div>
							</div>
							<div class="col-6 pricing-wrapper">
							  <div class="h-100 shade everyday-fare px-2 text-right">
							  <?php if($depart['regular'][$k]['count'] > 0) { ?>
								<span class="money-currency">$</span><span class="money-value"
								  data-attribute="adult-price"><?php echo $depart['regular'][$k]['total']; ?></span>
							   <?php } ?>
							  </div>
							</div>
						  </div>
						</div>
					  </div>

                <?php } }  ?>

                  <div class="row pricing-detail-row subtotal align-items-center">
                    <div class="col-xl-7 col-lg-7 col-3 text-uppercase">
                      Subtotal
                    </div>
					  <div class="col-xl-5 col-lg-5 col-9">
                      <div class="row align-items-center h-100">
                        <div class="col-6 pricing-wrapper">
                          <div class="h-100 shade quokka-saver px-2 text-right">
                            <span class="money-currency">$</span><span class="money-value">
							<?php
							  if(!isset($depart['familyNetPrice']))
								  $depart['familyNetPrice'] = 0;
							 echo number_format((float)($depart['familyNetPrice'] + $depart['admFee'] + $depart['cost']), 2, '.', '');
							?>
							</span>
                          </div>
                        </div>
                        <div class="col-6 pricing-wrapper">
                          <div class="h-100 shade everyday-fare px-2 text-right">
                            <span class="money-currency">$</span><span class="money-value">
							<?php
							  if(!isset($depart['familyNetPrice']))
								  $depart['familyNetPrice'] = 0;
							  echo number_format((float)($depart['familyNetPrice'] + $depart['admFee'] + $depart['everydayCost']), 2, '.', '');
							  
							?>
							</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
			  <?php $r++; } ?>
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
           <!-- Booking Summary-->
			<div id="sidebar" class="col-lg-3 col-md-4">
			</div>
		
		  <?php } else { ?>
				<div class="responseError"><?php echo $routeError; ?></div>
	     <?php  } ?>
		</div>
      </section>
 </div>
</main>