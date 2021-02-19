<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php //echo '<pre>'; print_r($routeDetails); echo '</pre>'; exit;
?>
<header class="title">
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
			<ul class="date_ranges">
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
		
	<?php } else { ?>
				<div class="responseError"><?php echo $routeError; ?></div>
	<?php  } ?>
	</div>
	<input type="hidden" id="current_position" value="depart" />
	<div class="actions">
		<a class="back_link" href="javascript:void(0);" onclick="prev()">
		  <span class="link_back"></span>  < Back
		</a>
		<button  class="button" onclick="next(); return false;">Next</button>
	</div>
</form>
<script>
function updateBookingRow(data, cartUpdateflag) {
	$("#loading").show();
    $.ajax({
        url: "<?php echo site_url(); ?>/processbooking/updateBookingRowAjax/?updateData="+data,
        type: "get",
		dataType: 'json',
        success: function (response) {
            if(response.isError)
			{
				alert(response.message);
				window.location.href = '<?php echo site_url(); ?>';
			}
			else
			{
				console.log(response);
				if(cartUpdateflag == "true" || $(".booking_overview").is(':visible'))
				{
					$(".booking_overview").show();
					$("#amountToPay").html(response.result.GetBookingResult.Booking.AmountToPay);
				}
			}
			$("#loading").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
			$("#loading").hide();
        }
    });
}

function selectRow(type, id)
{
	$("li[id^='"+type+"_']").removeClass('selectedRow');
	$("#"+type+"_"+id).addClass('selectedRow');
}
</script>