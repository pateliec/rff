<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (isset($user)){
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/myaccount.css">
<div>
	<main class="content wrap">
		<section class="account">
			<header class="title">
				<h1>
					<span>Welcome </span>
					<span class="username"><?= $user['Title']." ".$user['FirstName'].' '.$user['LastName'] ?></span>
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
					<a href="<?= base_url().'account/myaccount'?>"><h2 class="profile">My Account</h2></a>
					<a style="float: right;" href="<?= base_url().'account/logout'?>"><h2 class="logout">Logout</h2></a>
				</div>

				<ul class="itinerary customerinfo" id="table">
				    <li class="row-title helpers">
					  <a style="float: right;" href="<?= base_url().'account/edit'?>">
						<h2 class="logout">Edit</h2>
					  </a>
					</li>	
					<li class="row0">
						<ul>
							<li class="row-title">personal information</li>
							<span></span>
							<div class="myaccount-view">
								<li class="label">title</li>
								<li class="value"><?= $user['Title'] ?></li>
							</div>
							<div class="myaccount-view">
								<li class="label">name</li>
								<li class="value"><?= $user['FirstName'].' '.$user['LastName'] ?></li>
							</div>
							<div class="myaccount-view">
								<li class="label">date of birth</li>

								<li class="value"><?= date('F dS Y', strtotime($user['DateOfBirth'])); ?></li>
							</div>
							<span></span>
						</ul>
						<ul>
							<li class="row-title">RFF References</li>
							<span></span>
							<div class="myaccount-view">
								<li class="label">rff number</li>
								<li class="value"><?= $user['CustomerNumber'] ?></li>
							</div>
							<div class="myaccount-view">
								<?php if($user['Gender'] == 'M'): 
										$gender = 'Male'; 
									endif;
									if($user['Gender'] == 'F'): 
										$gender = 'Female'; 
									endif; ?>
								<li class="label">gender</li>
								<li class="value"><?= $gender ?></li>
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
								<li class="value"><?= $user['Email'] ?></li>
							</div>
							<div class="myaccount-view">
								<li class="label">phone number</li>
								<li class="value"><?= $user['MobilePhoneNumber'] ?></li>
							</div>
							<span></span>
						</ul>
						<ul>
							<li class="row-title">Address</li>
							<span></span>
							<div class="myaccount-view">
								<li class="label">street address</li>
								<li class="value"><?= $user['Address'] ?></li>
							</div>
							<div class="myaccount-view">
								<li class="label"></li>
								<li class="value"><?= $user['City'].', '.$user['County'].', '.$user['PostCode'] ?></li>
							</div>
							<span></span>
						</ul>
					</li>
					<li class="row2">
						<ul>
							<li class="row-title">LOYALTY SCHEME</li>
							<span></span>
							<span></span>
						</ul>
						<ul>
							<li class="row-title"></li>
							<span></span>
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