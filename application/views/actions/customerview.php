<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="myaccount-dashboard"></div>
<script>
$(document).ready(function () {
	$("#loading").show();
    $.ajax({
        url: "<?php echo site_url(); ?>/account/view",
        type: "get",
        success: function (response) {
			$("#loading").hide();
            $("#myaccount-dashboard").html(response);
        },
        error: function (jqXHR, textStatus, errorThrown) {
			$("#loading").hide();
            console.log(textStatus, errorThrown);
        }
    });
});
</script>
