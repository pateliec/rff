<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php //echo '<pre>'; print_r($routeDetails); echo '</pre>';
?>
<div class="main-content firstpage">
  	<main class="content wrap">
    	<section class="booking" id="booking-section">
      		
		</section>
	</main>
</div>
<script>
$(document).ready(function () {
	$("#loading").show();
    $.ajax({
        url: "<?php echo site_url(); ?>/processbooking/onewayBookingAjax",
        type: "get",
        success: function (response) {
            $("#booking-section").html(response);
			$("#loading").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
			$("#loading").hide();
        }
    });
});

function getDeparture(date, isReturn)
{
	$("#loading").show();
    $.ajax({
        url: "<?php echo site_url(); ?>/processbooking/onewayBookingAjax?bookingDate="+date,
        type: "get",
        success: function (response) {
            $("#booking-section").html(response);
			$("#loading").hide();
			if(isReturn == "true")
		           next();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
			$("#loading").hide();
        }
    });
	
}
function next()
{
	var current_position = jQuery("#current_position").val();
	if(current_position == "depart")
	{
		$("#loading").show();
		$.ajax({
			url: "<?php echo site_url(); ?>/processbooking/extraAjax",
			type: "get",
			success: function (response) {
				$("#booking-section").html(response);
				$("#loading").hide();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
				$("#loading").hide();
			}
		});
	}
}
function prev()
{
	var current_position = jQuery("#current_position").val();
	if(current_position == "return")
	{
		jQuery("#current_position").val("depart");
		jQuery("#return_area").hide();
		jQuery("#departure_area").show();
		jQuery("#dep_head").show();
		jQuery("#ret_head").hide();
		
	}
	if(current_position == "depart")
	{
		window.location.href = '<?= base_url() ?>';
	}
}
</script>