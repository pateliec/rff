<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="cta-wrapper">
    <div class="container">
      <div class="cta-row">
        <div class="cta-item">
          <a href="../index.html" class="back-btn"><i class="fas fa-chevron-left mr-1"></i><?= 'Start again' ?></a>
        </div>

        <div class="cta-item cta-total text-uppercase text-center">
          <?= 'Current total : ' ?><span class="cta-current-total"><span class="money-currency"><?= '$' ?></span>
            <span class="money-value"><?= '26.50' ?></span></span>
        </div>
        <div class="cta-item cta-button text-md-right text-center nextbutton">
        	<button id="gotToLuggage" class="btn cta-btn disabled"><?= 'Continue to Luggage' ?></button>
        </div>
      </div>
    </div>
  </div>
 <footer>
    <div class="container">
      <nav class="footer-nav">
        <a href="/contact-us" target="_blank"><?= 'Need Help? Contact us' ?></a>
        <a href="/term-conditions" target="_blank"><?= 'Terms & Conditions' ?></a>
      </nav>
    </div>
  </footer>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/vendor/vue-select.js"></script> 
  <script src="<?php echo base_url(); ?>assets/js/main.js"></script>
 <script>
$(document).ready(function () {
	$("#loading").show();
    $.ajax({
        url: "<?php echo site_url(); ?>/processbooking/returnBookingAjax",
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
        url: "<?php echo site_url(); ?>/processbooking/returnBookingAjax?bookingDate="+date+"&isReturn="+isReturn,
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
		jQuery("#current_position").val("return");
		jQuery("#return_area").show();
		jQuery("#departure_area").hide();
		jQuery("#dep_head").hide();
		jQuery("#ret_head").show();
	}
	if(current_position == "return")
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
function loadSidebar()
{
	$.ajax({
			url: "<?php echo site_url(); ?>/processbooking/sidebar",
			type: "get",
			success: function (response) {
				if($("#sidebar"))
					$("#sidebar").html(response);
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
			}
		});
}
loadSidebar();
</script>
</body>

</html>