<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//echo "<pre>"; print_r($currentBooking); echo "</pre>";
$bookingRows = array();
if(isset($currentBooking['GetBookingResult']['Booking']['BookingRows']))
	$bookingRows = $currentBooking['GetBookingResult']['Booking']['BookingRows']['BookingRow'];

$totalPriceToPay = $currentBooking['GetBookingResult']['Booking']['TotalPriceToPay'];

//echo "<pre>"; print_r($bookingRows); echo "</pre>";
//echo "<pre>"; print_r($sessionData); echo "</pre>";
?>        
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
		<?php if(!empty($bookingRows))  {
			$bookingData = array();
			foreach($bookingRows as $bookingRow) {
				if($bookingRow['ResourceCode'] == "ADM")
				{
					if(!isset($bookingRow['PassengerDialogs']['PassengerDialog'][1]))
						$passengerDialog[] = $bookingRow['PassengerDialogs']['PassengerDialog'];
					else
						$passengerDialog = $bookingRow['PassengerDialogs']['PassengerDialog'];
					
					foreach($passengerDialog as $pr)
					{
						if(!isset($bookingData['ADM'][$pr['PassengerType']]))
							$bookingData['ADM'][$pr['PassengerType']] = $pr['NetPrice'];
						else
							$bookingData['ADM'][$pr['PassengerType']] = $bookingData['ADM'][$pr['PassengerType']]+ $pr['NetPrice'];
					}
				}
				if(($bookingRow['ResourceCode'] == "PXF" OR $bookingRow['ResourceCode'] == "PAX") AND $bookingRow['SupplierCode'] == "HILROT")
				{

					if(!isset($bookingRow['PassengerDialogs']['PassengerDialog'][1]))
						$passengerDialog[] = $bookingRow['PassengerDialogs']['PassengerDialog'];
					else
						$passengerDialog = $bookingRow['PassengerDialogs']['PassengerDialog'];
					
					foreach($passengerDialog as $hil)
					{
						if(!isset($bookingData['HILROT'][$hil['PassengerType']]))
							$bookingData['HILROT'][$hil['PassengerType']] = $hil['NetPrice'];
						else
							$bookingData['HILROT'][$hil['PassengerType']] = $bookingData['HILROT'][$hil['PassengerType']]+ $hil['NetPrice'];
					}
				}
				if(($bookingRow['ResourceCode'] == "PXF" OR $bookingRow['ResourceCode'] == "PAX") AND $bookingRow['SupplierCode'] == "ROTHIL")
				{
					$passengerDialog = array();
					if(!isset($bookingRow['PassengerDialogs']['PassengerDialog'][1]))
						$passengerDialog[] = $bookingRow['PassengerDialogs']['PassengerDialog'];
					else
						$passengerDialog = $bookingRow['PassengerDialogs']['PassengerDialog'];

					foreach($passengerDialog as $rot)
					{
						if(!isset($bookingData['ROTHIL'][$rot['PassengerType']]))
							$bookingData['ROTHIL'][$rot['PassengerType']] = $rot['NetPrice'];
						else
							$bookingData['ROTHIL'][$rot['PassengerType']] = $bookingData['ROTHIL'][$rot['PassengerType']]+ $rot['NetPrice'];
					}
				}
			}
				
				$admTotal = 0;
				/* echo "<pre>";
				print_r($bookingData);
				exit; */
				foreach($bookingData['ADM'] as $ak=>$ad) {
					$admTotal = $admTotal+$ad;
		?>
				  <tr>
					<td class="table-item item-pass"><?php echo $sessionData['passengerType'][$ak] ?> (x <?php echo $sessionData['passengers'][$ak] ?>)</td>
					<td class="table-item item-pass-price"><span class="money-currency">$</span><span
						data-attribute="adult-price" class="money-value"><?php echo number_format((float)($ad), 2, '.', ''); ?></span></td>
				  </tr>

			<?php } ?>
		  <tr>
			<td colspan="2">
			  <div class="line"></div>
			</td>
		  </tr>
		  <tr>
			<td class="table-item item-subtotal item-last">Subtotal </td>
			<td class="table-item item-price item-subtotal item-last"><span
				class="money-currency">$</span><span class="money-value"><?php echo number_format((float)($admTotal), 2, '.', ''); ?></span></td>
		  </tr>
		<?php } ?>
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
	<?php if(!empty($bookingRows))  { ?>
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
		  <?php
		  $hilTotal = 0;
		  foreach($bookingData['HILROT'] as $hk=>$hd) {
					$hilTotal = $hilTotal + $hd;
		   ?>
			  <tr class="temp-departure-show1">
				<td class="table-item item-pass"><?php echo $sessionData['passengerType'][$hk] ?> (x <?php echo $sessionData['passengers'][$hk] ?>)</td>
				<td class="table-item item-pass-price"><span class="money-currency">$</span><span
					data-attribute="adult-price" class="money-value"><?php echo number_format((float)($hd), 2, '.', ''); ?></span></td>
			  </tr>

			<?php } ?>

		  <tr class="temp-departure-show">
			<td colspan="2">
			  <div class="line"></div>
			</td>
		  </tr>
		  <tr class="temp-departure-show1">
			<td class="table-item item-subtotal item-last">Subtotal</td>
			<td class="table-item item-price item-subtotal item-last"><span
				class="money-currency">$</span><span class="money-value"><?php echo number_format((float)($hilTotal), 2, '.', ''); ?></span></td>
		  </tr>
		</tbody>
        <?php if(isset($bookingData['ROTHIL'])) { ?>
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
		  <?php
		  $rotTotal = 0;
		  foreach($bookingData['ROTHIL'] as $rk => $rd) {
					$rotTotal = $rotTotal + $rd;
		   ?>
			  <tr class="temp-departure-show1">
				<td class="table-item item-pass"><?php echo $sessionData['passengerType'][$rk] ?> (x <?php echo $sessionData['passengers'][$rk] ?>)</td>
				<td class="table-item item-pass-price"><span class="money-currency">$</span><span
					data-attribute="adult-price" class="money-value"><?php echo number_format((float)($rd), 2, '.', ''); ?></span></td>
			  </tr>

			<?php } ?>
		  <tr class="temp-return-show1">
			<td colspan="2">
			  <div class="line"></div>
			</td>
		  </tr>
		  <tr class="temp-return-show1">
			<td class="table-item item-subtotal item-last">Subtotal</td>
			<td class="table-item item-price item-subtotal item-last"><span
				class="money-currency">$</span><span class="money-value"><?php echo number_format((float)($rotTotal), 2, '.', ''); ?></span></td>
		  </tr>
		</tbody>
		<?php } ?>
	  </table>
	<?php } ?>
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
		  <td class="table-item item-total item-price"><span class="money-currency">$</span> <span class="money-value"><?php echo number_format((float)($totalPriceToPay), 2, '.', ''); ?></span></td>
		</tr>
	  </tbody>


	</table>

  </div>
</div>