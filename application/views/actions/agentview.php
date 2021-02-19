<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="agent-myaccount-dashboard"></div>
<script>
$(document).ready(function () {
	$("#loading").show();
    $.ajax({
        url: "<?php echo site_url(); ?>/agent/view",
        type: "get",
        success: function (response) {
            $("#agent-myaccount-dashboard").html(response);
			$("#loading").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
			$("#loading").hide();
            console.log(textStatus, errorThrown);
        }
    });
});
</script>
