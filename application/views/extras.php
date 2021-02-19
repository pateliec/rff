 <?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php //echo '<pre>'; print_r($extraDetails); echo '</pre>'; //exit; ?>
 <style>
 /*****************extraspage_styles**********************/
.booking .booking_data .booking_table.departures ul li.time {
    width: 25%!important;
}
.booking .booking_data .booking_table.departures ul li.price {
    width: 60%!important;
    text-align: right;
}
.booking .booking_data .booking_table.departures ul li.quantity{
    width: 15%!important;
    text-align: right;
    padding-right: 10px;
}
.booking .booking_data .booking_table.luggage li .unit {
    width: 45%;
}
.booking .booking_data .booking_table.luggage li .unit{
    display:inline-block;
}
.booking .booking_data .booking_table.luggage li .value {
    width: 30%!important;
    text-align: right;
    display:inline-block;
}
.togglebox{
    float: right;
    width: 20%;
}
.togglebox .input-group .form-control{
    background: transparent;
    border: none;
    margin-left:-20%;
    box-shadow: none;
    width: 140%;
}
.quantity-left-minus, .quantity-left-minus:hover, .quantity-left-minus:active{
    background:#01b4e6;
    border:none;
    color:#fff;
    padding: 2px 5px 2px 5px;
    border-top-left-radius: 100%;
    border-top-right-radius: 100%!important;
    border-bottom-right-radius: 100%!important;
    border-bottom-left-radius: 100%;
}
.quantity-right-plus, .quantity-right-plus:hover, .quantity-right-plus:active{
    background:#01b4e6;
    border:none;
    color:#fff;
    padding: 2px 5px 2px 5px;
    border-top-left-radius: 100%!important;
    border-top-right-radius: 100%;
    border-bottom-right-radius: 100%;
    border-bottom-left-radius: 100%!important;
}
.qty-align{
    margin-top: -6%;
    margin-left: 13%;
}
.booking .booking_data .booking_table>li:not(.hasmodal)>ul>li:first-child:nth-last-child(2){
    width:50%;
}
.booking .booking_data .booking_table>li:not(.hasmodal)>ul>li:first-child:nth-last-child(2)~li {
    width: 50%;
}
 </style>
  <header class="title" >
        <div>
        </div>
        <h1>Booking Details</h1>
        <nav class="tabs">
          <ul>
            <li class="item">Time</li>
            <li class="active item">Extras</li>
            <li class="item">Passengers</li>
            <li class="item">Itinerary</li>
            <li>Payment</li>
          </ul>
        </nav>
      </header>
      <form>
        <div class="booking_data">
            <div class="helpers">
              <div class="booking_overview"> 
                <h5>
                  <span>Sub Total: </span>
                  <em>$174.00</em>
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
          <div class="helpers">
              <h2 class="departure_times"><i class="fa fa-ship" aria-hidden="true"></i>Luggage and Freight</h2>
		  </div>
          <ul class="booking_table passengers luggage departures" id="table">
            <li class="head">
              <ul>
                <li class="time">Item</li>
                <li class="price">Cost</li>
                <li class="quantity">Qunatity</li>
              </ul>
            </li>
            <?php if(isset($extraDetails['luggage'])) {
				  $diff = strtotime($extraDetails['luggage']['departureDate']) - strtotime(date('Y-m-d'));
				  $diffDays = abs(round($diff / 86400));
				  if($diffDays > 7)
				  {
					  $luggageTitle = "Early Bird Luggage";
					  $lugType = "E";
				  }
				  else
				  {
					  $luggageTitle = "Standard Luggage";
					  $lugType = "A";
				  }
				  
				  if(isset($extraDetails['luggage']['price']['ResourcePrice']))
					  {
					    foreach($extraDetails['luggage']['price']['ResourcePrice'] as $lugPrice)
						{
							if($diffDays > 7 && $lugPrice['Type'] == "E")
								$luggagePrice = $lugPrice['NetPrice'];
							if($diffDays < 7 && ($lugPrice['Type'] == "A" || $lugPrice['Type'] == "N"))
								$luggagePrice = $lugPrice['NetPrice'];
						}
					  }
				   else
					    $luggagePrice = 0;	  
				?>			
				<li>
				<strong class="unit"><?php echo $luggageTitle; ?></strong>
				<span class="value">
					<span>$<?php echo $luggagePrice; ?>.00</span>
				</span>
				<div class="togglebox">
					<div class="row">
						<div class="col-lg-2 qty-align">
							<div class="input-group">
							  <span class="input-group-btn">
								<button type="button" onclick="minusQty('<?php echo $extraDetails['luggage']['ResourceCode'] ?>');" class="quantity-left-minus btn  btn-number"  data-type="minus" data-field="">
									<span class="glyphicon glyphicon-minus"></span>
							   </button>
							  </span>
							  <input type="text" id="quantity_<?php echo $extraDetails['luggage']['ResourceCode'] ?>" name="quantity" class="form-control input-number" value="0" min="1" max="10">
							  <input type="hidden" id="rowId_<?php echo $extraDetails['luggage']['ResourceCode'] ?>" name="rowId_<?php echo $extraDetails['luggage']['ResourceCode'] ?>" value="0" >
							  <input type="hidden" id="resource_type_<?php echo $extraDetails['luggage']['ResourceCode'] ?>" name="resource_type" value="<?php echo $lugType; ?>" >
							  <span class="input-group-btn">
							  <?php if($extraDetails['luggage']['capacity'] > 0 ) { ?>
							  <button type="button" onclick="plusQty('<?php echo $extraDetails['luggage']['ResourceCode'] ?>');" class="quantity-right-plus btn   btn-number" data-type="plus" data-field="">
								<span class="glyphicon glyphicon-plus"></span>
							  </button>
							  <?php } else { ?>
							  <button type="button" disabled class="quantity-right-plus btn   btn-number" data-type="plus" data-field="">
								<span class="glyphicon glyphicon-plus"></span>
							  </button>
							  <?php } ?>
						   </span>
					   </div>
					  </div>
					 </div>
				</div>
			   </li>
			<?php } ?>
			<?php 
			if(isset($extraDetails['freight'])) { 
			  foreach($extraDetails['freight'] as $freight) {
			?>
			   <li>
				<strong class="unit"><?php echo $freight['Description']; ?></strong>
				<span class="value">
					<span>$<?php if(isset($freight['price']['ResourcePrice']['NetPrice'])) echo $freight['price']['ResourcePrice']['NetPrice']; else echo "0" ?>.00</span>
				</span>
				<div class="togglebox">
					<div class="row">
						<div class="col-lg-2 qty-align">
							<div class="input-group">
							  <span class="input-group-btn">
								<button type="button" onclick="minusQty('<?php echo $freight['ResourceCode'] ?>');" class="quantity-left-minus btn  btn-number"  data-type="minus" data-field="">
									<span class="glyphicon glyphicon-minus"></span>
							   </button>
							  </span>
							  <input type="text" id="quantity_<?php echo $freight['ResourceCode'] ?>" name="quantity" class="form-control input-number" value="0" min="1" max="10">
							  <span class="input-group-btn">
							   <?php if($freight['capacity'] > 0 ) { ?>
							  <button type="button" onclick="plusQty('<?php echo $freight['ResourceCode'] ?>');" class="quantity-right-plus btn   btn-number" data-type="plus" data-field="">
								<span class="glyphicon glyphicon-plus"></span>
							  </button>
							   <?php } else { ?>
							   <button type="button" disabled class="quantity-right-plus btn   btn-number" data-type="plus" data-field="">
								<span class="glyphicon glyphicon-plus"></span>
							  </button>
							   <?php } ?>
						   </span>
					   </div>
					  </div>
					 </div>
				</div>
			   </li>
            <?php } } ?>
			</ul>
			<div class="helpers">
              <h2 class="departure_times"><i class="fa fa-ship" aria-hidden="true"></i>Add Hire Equipment</h2>
		    <div>
			<ul class="booking_table passengers luggage departures" id="table">
            <li class="head">
              <ul>
                <li class="time">Item</li>
                <li class="price">Cost</li>
                <li class="quantity">Qunatity</li>
              </ul>
            </li>
			<?php 
			if(isset($extraDetails['extras']['RFFB'])) { 
			  foreach($extraDetails['extras']['RFFB'] as $rffb) {
			?>
			   <li>
				<strong class="unit"><?php echo $rffb['ResourceDescription']; ?></strong>
				<span class="value">
					<span>$<?php 
					      if(isset($rffb['Prices']['Price'][0]['Value'])) 
							  echo $rffb['Prices']['Price'][0]['Value']; 
						  else if(isset($rffb['Prices']['Price']['Value']))
							  echo $rffb['Prices']['Price']['Value'];
						  else echo "0" ?>.00</span>
				</span>
				<div class="togglebox">
					<div class="row">
						<div class="col-lg-2 qty-align">
							<div class="input-group">
							  <span class="input-group-btn">
								<button type="button" onclick="minusExtrasQty('<?php echo  $rffb['ResourceCode'] ?>');" class="quantity-left-minus btn  btn-number"  data-type="minus" data-field="">
									<span class="glyphicon glyphicon-minus"></span>
							   </button>
							  </span>
							  <input type="text" id="quantity_<?php echo  $rffb['ResourceCode'] ?>" name="quantity" class="form-control input-number" value="0" min="1" max="10">
							  <input type="hidden" id="supplierCode_<?php echo $rffb['ResourceCode'] ?>" name="supplierCode" class="form-control input-number" value="<?php echo $rffb['SupplierCode'] ?>" min="1" max="10">
							  <span class="input-group-btn">
							  <?php if($rffb['Capacity'] > 0 ) { ?>
							  <button type="button" onclick="plusExtrasQty('<?php echo  $rffb['ResourceCode'] ?>');" class="quantity-right-plus btn   btn-number" data-type="plus" data-field="">
								<span class="glyphicon glyphicon-plus"></span>
							  </button>
							  <?php } else { ?>
							   <button type="button" disabled class="quantity-right-plus btn   btn-number" data-type="plus" data-field="">
								<span class="glyphicon glyphicon-plus"></span>
							  <?php } ?>
							  </button>
						   </span>
					   </div>
					  </div>
					 </div>
				</div>
			   </li>
            <?php } } ?>
          </ul>
		  <div class="helpers">
              <h2 class="departure_times"><i class="fa fa-ship" aria-hidden="true"></i>Tour</h2>
		    <div>
			<ul class="booking_table passengers luggage departures" id="table">
            <li class="head">
              <ul>
                <li class="time">Item</li>
                <li class="price">Cost</li>
                <li class="quantity">Qunatity</li>
              </ul>
            </li>
			<?php 
			  foreach($extraDetails['extras'] as $extrsKey=>$extraResources) {
				  if($extrsKey == 'RFFB')
					  continue;
				  foreach($extraResources as $extraResource) {
					  if(!isset($extraResource['SupplierCode']))
						  continue;
			?>
			   <li>
				<strong class="unit"><?php echo $extraResource['ResourceDescription']; ?></strong>
				<span class="value">
					<span>$<?php 
					      if(isset($extraResource['Prices']['Price'][0]['Value'])) 
							  echo $extraResource['Prices']['Price'][0]['Value']; 
						  elseif(isset($extraResource['Prices']['Price']['Value']))
							  echo $extraResource['Prices']['Price']['Value'];
						  else echo "0" ?>.00</span>
				</span>
				<div class="togglebox">
					<div class="row">
						<div class="col-lg-2 qty-align">
							<div class="input-group">
							  <span class="input-group-btn">
								<button type="button" onclick="minusExtrasQty('<?php echo  $extraResource['ResourceCode'] ?>');" class="quantity-left-minus btn  btn-number"  data-type="minus" data-field="">
									<span class="glyphicon glyphicon-minus"></span>
							   </button>
							  </span>
							  <input type="text" id="quantity_<?php echo  $extraResource['ResourceCode'] ?>" name="quantity" class="form-control input-number" value="0" min="1" max="10">
							  <input type="hidden" id="supplierCode_<?php echo $extraResource['ResourceCode'] ?>" name="supplierCode" class="form-control input-number" value="<?php echo $extraResource['SupplierCode'] ?>" min="1" max="10">
							  <span class="input-group-btn">
							  <?php if($extraResource['Capacity'] > 0 ) { ?>
							  <button type="button" onclick="plusExtrasQty('<?php echo  $extraResource['ResourceCode'] ?>');" class="quantity-right-plus btn   btn-number" data-type="plus" data-field="">
								<span class="glyphicon glyphicon-plus"></span>
							  </button>
							  <?php } else { ?>
							  <button type="button" disabled class="quantity-right-plus btn   btn-number" data-type="plus" data-field="">
								<span class="glyphicon glyphicon-plus"></span>
							  </button>
							  <?php } ?>
						   </span>
					   </div>
					  </div>
					 </div>
				</div>
			   </li>
			<?php }  } ?>
          </ul>
           <div class="helpers">
              <h2 class="departure_times"><i class="fa fa-ship" aria-hidden="true"></i>Courtesy Coach</h2>
		  <div>
           <?php if(isset($extraDetails['courtesy_coach']) && $extraDetails['courtesy_coach']==1) {  ?>
           <ul class="booking_table passengers pickups" id="table">
            <li class="head">If you require our courtesy coach pick up, please select your closest pick up point from the list below</li>
            <li class="head">
              <ul>
                <li class="location">Location</li>
                <li class="time">Time</li>
              </ul>
            </li>
            <li class="selected">
              <ul onclick="addBusLocation('NA');">
                <li class="location">Not Required</li>
                <li class="time">N/A</li>
              </ul>
            </li>
            <li data-toggle="" data-target="" class="inactive">
              <ul onclick="addBusLocation('07:55am @ Billabong Backpackers');">
                <li class="location">Billabong Backpackers</li>
                <li class="time">07:55am</li>
              </ul>
            </li>
            <li data-toggle="" data-target="" class="inactive">
              <ul onclick="addBusLocation('08:00am @ Double Tree Northbridge');">
                <li class="location">Double Tree Northbridge</li>
                <li class="time">08:00am</li>
              </ul>
            </li>
            <li data-toggle="" data-target="" class="inactive">
              <ul>
                <li class="location">Coolibah Lodge</li>
                <li class="time">07:55am</li>
              </ul>
            </li>
            <li data-toggle="" data-target="" class="inactive">
              <ul>
                <li class="location">Crowne Plaza</li>
                <li class="time">08:05am</li>
              </ul>
            </li>
            <li data-toggle="" data-target="" class="inactive">
              <ul>
                <li class="location">Crown, Riverside Entrance</li>
                <li class="time">07:40am</li>
              </ul>
            </li>
            <li data-toggle="" data-target="" class="inactive">
              <ul>
                <li class="location">Peppers Kings Square Hotel</li>
                <li class="time">08:25am</li>
              </ul>
            </li>
            <li data-toggle="" data-target="" class="inactive">
              <ul>
                <li class="location">Great Southern Hotel</li>
                <li class="time">08:00am</li>
              </ul>
            </li>
            <li data-toggle="" data-target="" class="inactive">
              <ul>
                <li class="location">Across the road from Holiday Inn</li>
                <li class="time">08:25am</li>
              </ul>
            </li>
            <li data-toggle="" data-target="" class="inactive">
              <ul>
                <li class="location">Karrinyup Waters Resort</li>
                <li class="time">09:10am</li>
              </ul>
            </li>
            <li data-toggle="" data-target="" class="inactive">
              <ul>
              <li class="location">Mantra on Hay (Cnr Hay &amp; Bennett St)</li>
              <li class="time">08:10am</li>
            </ul>
          </li>
          <li data-toggle="" data-target="" class="inactive">
            <ul>
              <li class="location">Corner of Hay and Pier Street</li>
              <li class="time">08:15am</li>
            </ul>
          </li>
          <li data-toggle="" data-target="" class="inactive">
            <ul>
              <li class="location">Pan Pacific</li>
              <li class="time">08:05am</li>
            </ul>
          </li>
          <li data-toggle="" data-target="" class="inactive">
            <ul>
              <li class="location">Ramada Perth, The Outram</li>
              <li class="time">08:35am</li>
            </ul>
          </li>
          <li data-toggle="" data-target="" class="inactive">
            <ul>
              <li class="location">Rendezvous Scarborough</li>
              <li class="time">09:00am</li>
            </ul>
          </li>
          <li data-toggle="" data-target="" class="inactive">
            <ul>
              <li class="location">Rendezvous Hotel Perth Central</li>
              <li class="time">08:30am</li>
            </ul>
          </li>
          <li data-toggle="" data-target="" class="inactive">
            <ul>
              <li class="location">Travelodge (CAT bus stop out front)</li>
              <li class="time">08:15am</li>
            </ul>
          </li>
        </ul>
		   <?php } else { ?>
		   <ul class="booking_table passengers pickups" id="table">
				<li class="head">Our Courtesy Coach Pickup Service is currently unavailable. We apologise for any inconvenience, and encourage guests to instead take advantage of free parking available in Hillarys Boat Harbour.</li>
		   </ul>
		   <?php } ?>
        </div>
        <div class="actions">
          <a class="back_link" href="/"><span class="link_back"></span>  <&nbsp;>Back</a>
          <button class="button">Next</button>
          <input type="hidden" name="departure_route" value="">
        </div>
      </form>
<script>

function addBusLocation(bus_location)
{
	
	$("#loading").show();
		$.ajax({
			url: "<?php echo site_url(); ?>/processbooking/addUpdateLuggageFreightAjax/?status=new&resources=HIG&resource_type=NA&qty=1&bus_location="+bus_location,
			type: "get",
			dataType: 'json',
			success: function (response) {
				console.log(response);
				if(response.isError == "true")
				{
					alert(response.message);
					window.location.href = '<?php echo site_url(); ?>';
				}
				$("#loading").hide();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
				$("#loading").hide();
			}
		});
}

function plusQty(resource)
{
	var status;
	var resource_type;
	
	if($('#resource_type_'+resource))
		resource_type = $('#resource_type_'+resource).val();
	else
		resource_type = 'NA';
	
	var quantity = parseInt($('#quantity_'+resource).val());
	if(quantity > 0)
		status = 'update';
	else
		status = 'new';
	var latestQty = quantity + 1;
	$('#quantity_'+resource).val(latestQty);
	
	$("#loading").show();
		$.ajax({
			url: "<?php echo site_url(); ?>/processbooking/addUpdateLuggageFreightAjax/?status="+status+"&resources="+resource+"&resource_type="+resource_type+"&qty="+latestQty,
			type: "get",
			dataType: 'json',
			success: function (response) {
				console.log(response);
				if(response.isError == "true")
				{
					alert(response.message);
					window.location.href = '<?php echo site_url(); ?>';
				}
				$("#loading").hide();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
				$("#loading").hide();
			}
		});
}

function minusQty(resource)
{
	var status;
	var resource_type;
	if($('#resource_type_'+resource))
		resource_type = $('#resource_type_'+resource).val();
	else
		resource_type = 'NA';
	var quantity = parseInt($('#quantity_'+resource).val());
	if(quantity == 0)
		return;
	else
		status = 'update';
	
	if(quantity > 0){
		var latestQty = quantity - 1;
	}
	else
		var latestQty = 0;
	
	$('#quantity_'+resource).val(latestQty);
	
	$("#loading").show();
		$.ajax({
			url: "<?php echo site_url(); ?>/processbooking/addUpdateLuggageFreightAjax/?status="+status+"&resources="+resource+"&resource_type="+resource_type+"&qty="+latestQty,
			type: "get",
			dataType: 'json',
			success: function (response) {
				console.log(response);
				if(response.isError == "true")
				{
					alert(response.message);
					window.location.href = '<?php echo site_url(); ?>';
				}
				$("#loading").hide();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
				$("#loading").hide();
			}
		});
}

function plusExtrasQty(resource)
{
	var status;
	var supplierCode;

	if($('#resource_type_'+resource))
		supplierCode = $('#supplierCode_'+resource).val();
	else
		supplierCode = 'NA';
	
	var quantity = parseInt($('#quantity_'+resource).val());
	if(quantity > 0)
		status = 'update';
	else
		status = 'new';
	var latestQty = quantity + 1;
	$('#quantity_'+resource).val(latestQty);
	
	$("#loading").show();
		$.ajax({
			url: "<?php echo site_url(); ?>/processbooking/addUpdateExtrasAjax/?status="+status+"&resources="+resource+"&supplierCode="+supplierCode+"&qty="+latestQty,
			type: "get",
			dataType: 'json',
			success: function (response) {
				console.log(response);
				if(response.isError == "true")
				{
					alert(response.message);
					window.location.href = '<?php echo site_url(); ?>';
				}
				$("#loading").hide();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
				$("#loading").hide();
			}
		});
}

function minusExtrasQty(resource)
{
	var status;
	var supplierCode;

	if($('#resource_type_'+resource))
		supplierCode = $('#supplierCode_'+resource).val();
	else
		supplierCode = 'NA';
	
	var quantity = parseInt($('#quantity_'+resource).val());
	if(quantity == 0)
		return;
	else
		status = 'update';
	
	if(quantity > 0){
		var latestQty = quantity - 1;
	}
	else
		var latestQty = 0;
	
	$('#quantity_'+resource).val(latestQty);
	
	$("#loading").show();
		$.ajax({
			url: "<?php echo site_url(); ?>/processbooking/addUpdateExtrasAjax/?status="+status+"&resources="+resource+"&supplierCode="+supplierCode+"&qty="+latestQty,
			type: "get",
			dataType: 'json',
			success: function (response) {
				console.log(response);
				if(response.isError == "true")
				{
					alert(response.message);
					window.location.href = '<?php echo site_url(); ?>';
				}
				$("#loading").hide();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
				$("#loading").hide();
			}
		});
}
    
</script>