<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//echo "<pre>";
//print_r($customerData);
//print_r($countries);
$dob_day = (int)date("d", strtotime($customerData['DateOfBirth']));
$dob_month = (int)date("m", strtotime($customerData['DateOfBirth']));
$dob_year = (int)date("Y", strtotime($customerData['DateOfBirth']));
$currentYear = (int)date("Y");

?>
<div>
    <main class="content wrap">
            <section class="registrationpage">
                <section class="intro">
                    <h1><span>Edit Your Details</span></h1>
                </section>
                <div class="active">
                    <div class="modal_content boxcontent">
                        <span><h3 class="modal_title">Account Details</h3></span>
                        <form class="haslabels registerform" id="form_register" action="<?= base_url().'account/editPost'?>" method="post">
                            <h4>Born on</h4>
                            <ul class="form form-group born">
                                <li class="expand active date">
                                    <div class="form-group srd">
                                        <select class="form-control modal-date" id="date" placeholder="Date" name="day">
                                            <option value="" disabled selected>Day</option>
                                            <?php for ($i=1; $i <=31; $i++) { ?>
                                                <option <?php if($dob_day == $i) echo "selected"; ?> value="<?= $i ?>"><?= $i ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </li>
                                <li class="expand active month">
                                    <div class="form-group srd">
                                        <select class="form-control modal-month" id="month" placeholder="Month" name="month">
                                            <option  value="" disabled selected>Month</option>
                                            <?php for ($i=1;  $i <=12; $i++) { ?>
                                                <option <?php if($dob_day == $i) echo "selected"; ?> value="<?= $i ?>"><?= $i ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </li>
                                <li class="expand active year">
                                    <div class="form-group srd">
                                        <select class="form-control modal-year" id="ddlYears" placeholder="Year" name="year">
                                            <option value="" disabled selected>Year</option>
											<?php for ($i=1950;  $i <=$currentYear; $i++) { ?>
                                                <option <?php if($dob_year == $i) echo "selected"; ?> value="<?= $i ?>"><?= $i ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </li>
                            </ul>
                            <h4>Contact Information</h4>
                            <ul class="form form-group contact" style="display: block; width: 100%; height: 100%; float: left;">
                                <li class="expand active titlecard">
                                    <div class="form-group srd">
                                        <select name="title" id="title" class="form-control modal-address" id="exampleFormControlSelect1" placeholder="Title">
                                            <option value="" disabled selected>Title</option>
                                            <option <?php if(strtolower($customerData['Title']) == "mr.") echo "selected"; ?> <?php if($customerData['Title'] == "Mr.") echo "selected"; ?> value="Mr.">Mr.</option>
                                            <option <?php if(strtolower($customerData['Title']) == "mrs.") echo "selected"; ?> <?php if($customerData['Title'] == "Mrs.") echo "selected"; ?> value="Mrs.">Mrs.</option>
                                            <option <?php if(strtolower($customerData['Title']) == "miss") echo "selected"; ?> <?php if($customerData['Title'] == "Miss.") echo "selected"; ?> value="Miss">Miss</option>
                                            <option <?php if(strtolower($customerData['Title']) == "ms") echo "selected"; ?> <?php if($customerData['Title'] == "Ms.") echo "selected"; ?> value="Ms">Ms</option>
                                        </select>
                                    </div>
                                </li>
                                <li class="expand active titlecard">
                                    <input type="text" class="form-control modal-textfield" placeholder="Mobile Phone" id="mobile_phone" name="mobile_phone" value="<?php echo $customerData['MobilePhoneNumber'] ?>">
                                </li>
                                <li class="expand active firstname">
                                    <input type="text" class="form-control modal-textfield" placeholder="First Name" id="firstname" name="firstname" value="<?php echo $customerData['FirstName'] ?>">
                                </li>
                                <li class="expand active titlecard">
                                    <input type="text" class="form-control modal-textfield" placeholder="Last Name" id="lastname" name="lastname" value="<?php echo $customerData['LastName'] ?>">
                                </li>
                                <li class="expand active titlecard">
                                     <div class="form-group srd">
                                        <select class="form-control modal-address" id="gender" placeholder="Gender" name="gender">
                                          <option value="" disabled selected>Gender</option>
                                          <option <?php if(strtolower($customerData['Gender']) == "m") echo "selected"; ?> value="M">Male</option>
                                          <option <?php if(strtolower($customerData['Gender']) == "f") echo "selected"; ?> value="F">Female</option>
                                         </select>
                                      </div>
                                </li>
                                <li class="expand active titlecard">
                                    <input type="text" class="form-control modal-textfield" placeholder="Address" id="address" name="address" value="<?php echo $customerData['Address'] ?>">
                                </li>
                                <li class="expand active titlecard">
                                    <div class="form-group srd">
                                        <select class="form-control modal-address" id="country" placeholder="Country" name="country">
                                                <option value="" disabled selected>Country</option>
											<?php foreach($countries as $country) { ?>
                                                <option <?php if(strtolower($country) == strtolower($customerData['CountryName'])) echo "selected"; ?>   value="<?= $country ?>"><?= $country ?></option>
                                            <?php } ?>
                                                
                                        </select>
                                    </div>
                                </li>
                                <li class="expand active titlecard">
                                    <input type="text" class="form-control modal-textfield" placeholder="City" id="city" name="city" value="<?php echo $customerData['City'] ?>">
                                </li>
                                <li class="expand active titlecard">
                                    <input type="text" class="form-control modal-textfield" placeholder="Postcode/Zip" id="postcode" name="postcode" value="<?php echo $customerData['PostCode'] ?>">
                                </li>
                            </ul>
							 <input type="hidden"  id="customernumber" name="customernumber" value="<?php echo $customerData['CustomerNumber'] ?>">
                            <button class="submit register-button" name="register">Edit</button>
                        </form>
                    </div>
                </div>
            </section>
        </main>
</div>
