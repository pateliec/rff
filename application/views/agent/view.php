<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//echo "aaaa"; echo '<pre>'; print_r($agent); echo '</pre>'; 
if (isset($agent)){
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/myaccount.css">
<div>
	<main class="content wrap">
		<section class="account">
			<header class="title">
				<h1>
					<span>Welcome </span>
					<span class="username"><?= $agent['Name'] ?></span>
				</h1>
				<nav class="tabs">
					<ul>
						<li class="active item account"><i class="fa fa-user-circle" aria-hidden="true"></i>Account</li>
						<li class="item bookings"><i class="fa fa-user-circle" aria-hidden="true"></i>Bookings</li>
						<li class="item giftcards"><i class="fa fa-user-circle" aria-hidden="true"></i>Gift Cards</li>
					</ul>
				</nav>
			</header>
			<div class="booking_data">
				<div class="helpers ">
					<a href="<?= base_url().'agent/myaccount'?>"><h2 class="profile">My Account</h2></a>
					<a style="float: right;" href="<?= base_url().'agent/logout'?>"><h2 class="logout">Logout</h2></a>
				</div>

				<ul class="itinerary customerinfo" id="table">
					<li class="row0">
						<ul>
							<li class="row-title">Agent</li>
							<span></span>
							<div class="myaccount-view">
								<li class="label">Name</li>
								<li class="value"><?= $agent['Name'] ?></li>
							</div>
							
							<span></span>
						</ul>
						<ul>
							<li class="row-title">ACCOUNT DETAILS</li>
							<span></span>
							<div class="myaccount-view">
								<li class="label">Initials</li>
								<li class="value"><?= $agent['Initials'] ?></li>
							</div>
							<div class="myaccount-view">
								<li class="label">Agent Number</li>
								<li class="value"><?= $agent['AgentNumber'] ?></li>
							</div>
							<div class="myaccount-view">
								<li class="label">User Name</li>
								<li class="value"><?= $agent['UserName'] ?></li>
							</div>
							
							<span></span>
						</ul>
						
					</li>
					<li class="row1">
						<ul>
							<li class="row-title">contact information</li>
							<span></span>
							<div class="myaccount-view">
								<li class="label">email address</li>
								<li class="value"><?= $agent['Email'] ?></li>
							</div>
							<div class="myaccount-view">
								<li class="label">phone number</li>
								<li class="value"><?= $agent['MobilePhoneNumber'] ?></li>
							</div>
							<span></span>
						</ul>
						<ul>
							<li class="row-title">Address</li>
							<span></span>
							<div class="myaccount-view">
								<li class="label">street address</li>
								<li class="value">NA</li>
							</div>
							
							<span></span>
						</ul>
					</li>
			
				</ul>
			</div>
			<span></span>
			<span></span>
		</section>
	</main>
</div>
<?php
// }
}
?>
	<!-- <script type="text/javascript">
function myFunction() {
  var x = document.getElementById("myLinks");
  if (x.style.display === "block") {
    x.style.display = "none";
  } else {
    x.style.display = "block";
  }

}
</script> -->