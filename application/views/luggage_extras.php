<!-- Main -->
<main id="app" class="body-wrapper wizard">
    <div class="container">
        <!-- Search Header -->
        <section class="search-header py-lg-4 py-2 text-center">
            <h1><?=  'Rottnest Ferries' ?></h1>
            <a href="../index.html" class="edit-search"><?= 'Change Search' ?><i class="fas fa-pencil-alt ml-2"></i></a>
        </section>

        <!-- Steps Guide -->
        <section class="booking-navigation py-lg-4 py-2">
            <div class="d-flex flex-row step-groups">
                <a href="select-ferries.html" class="p-2 step step-1 completed">
                    <div class="d-flex flex-md-row flex-column align-items-center justify-content-center">
                        <div class="d-md-block d-none step-jumbo mr-2"><?= '1' ?></div>
                        <div class="text-uppercase step-title">
                            <i class="demo-icon ferry-icon"> </i> <br>
                            <?= 'Ferries' ?>
                        </div>
                    </div>
                </a>
                <a href="#" class="p-2 step step-2 active">
                    <div class="d-flex flex-md-row flex-column align-items-center justify-content-center">
                        <div class="d-md-block d-none step-jumbo mr-2"><?= '2' ?></div>
                        <div class="text-uppercase step-title">
                            <i class="demo-icon packages-icon"></i> <br>
                            <?= 'Luggage' ?>
                        </div>
                    </div>
                </a>
                <a href="#" class="p-2 step step-3 disabled">
                    <div class="d-flex flex-md-row flex-column align-items-center justify-content-center">
                        <div class="d-md-block d-none step-jumbo mr-2"><?= '3' ?></div>
                        <div class="text-uppercase step-title">
                            <i class="demo-icon extras-icon"></i> <br>
                            <?= 'Extras' ?>
                        </div>
                    </div>
                </a>
                <a href="#" class="p-2 step step-4 disabled">
                    <div class="d-flex flex-md-row flex-column align-items-center justify-content-center">
                        <div class="d-md-block d-none step-jumbo mr-2"><?= '4' ?></div>
                        <div class="text-uppercase step-title">
                            <i class="fas fa-user "></i> <br>
                            <?= 'Passenger Details' ?>
                        </div>
                    </div>
                </a>
                <a href="#" class="p-2 step step-5 disabled">
                    <div class="d-flex flex-md-row flex-column align-items-center justify-content-center">
                        <div class="d-md-block d-none step-jumbo mr-2"><?= '5' ?></div>
                        <div class="text-uppercase step-title">
                            <i class="demo-icon dollar-icon"></i> <br>
                            <?= 'Payment' ?>
                        </div>
                    </div>
                </a>
            </div>
        </section>

        <!-- Step 2: Luggage-->
        <section class="luggage-options py-3 luggage-block">
            <div class="row">
                <div class="col-lg-9 col-md-8 pb-3">
                    <h2><?= 'Luggage & Freight' ?></h2>
                    <p class="instruction">
                        <?= ' Is there ' ?><span class="bold"><?= 'anything of yours ' ?></span><?= ' that you would like to take?' ?>
                    </p>
                    <!-- Luggage -->
                    <?php if(isset($extraDetails['luggage'])) {
                        $diff = strtotime($extraDetails['luggage']['departureDate']) - strtotime(date('Y-m-d'));
                        $diffDays = abs(round($diff / 86400));
                        if($diffDays > 7) {
                            $luggageTitle = "Early Bird Luggage";
                            $lugType = "E";
                        } else {
                            $luggageTitle = "Standard Luggage";
                            $lugType = "A";
                        }
                        if(isset($extraDetails['luggage']['price']['ResourcePrice'])) {
                            foreach($extraDetails['luggage']['price']['ResourcePrice'] as $lugPrice) {
                                if($diffDays > 7 && $lugPrice['Type'] == "E")
                                    $luggagePrice = $lugPrice['NetPrice'];
                                if($diffDays < 7 && ($lugPrice['Type'] == "A" || $lugPrice['Type'] == "N"))
                                    $luggagePrice = $lugPrice['NetPrice'];
                            }
                        } else
                        $luggagePrice = 0; 
                    ?>  
                        <div class="card">
                            <div class="card-header"><?= $luggageTitle ?></div>
                            <div class="card-body">
                                <p><?= ' You can opt to have your luggage delivered to your Rottnest Island accommodation on your behalf. Each item for delivery needs to be labelled, however there is no charge or label required for carry-on luggage.' ?></p>
                                <div class="d-flex flex-row flex-wrap align-items-center justify-content-end">
                                    <div class="cta-card-item card-price"><?= '$'.$luggagePrice.'.00/item ' ?></div>
                                    <button class="btn btn-green btn-xs cta-card-item" data-toggle="modal" data-target="#addLuggage" data-backdrop="static" id="addLuggageTrigger"><?= 'Add Luggage' ?></button>
                                    <button id="removeLuggage" type="button" class="cta-card-item btn btn-xs btn-remove" v-on:click="removeLuggage"><?= 'Remove Luggage' ?></button>
                                </div>
                            </div>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="addLuggage" tabindex="-1" role="dialog" aria-labelledby="addLuggage" aria-hidden="true">
                            <div id="accommodationModal" class="modal-dialog  modal-lg" role="document">
                                <div class="modal-content">
                                    <form>
                                        <div class="modal-header">
                                            <h4 class="modal-title"><?= 'Luggage & Accommodation Details' ?></h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="light">
                                                <p><?= 'Please note there is a weight limit of 22kg per item of luggage for delivery. Items weighing more than this will not be delivered to your accommodation, and you will need to collect them from the Rottnest Island Visitor Centre.' ?></p>
                                                <p><?= 'On the day of departure, you will need to collect your luggage labels from our office at Hillarys Boat Harbour. To assist in transporting your luggage to your accommodation, please ensure that:' ?></p>
                                                <ol>
                                                    <li><?= 'All items are labelled.' ?></li>
                                                    <li><?= 'Valuables and medication are stored in your carry-on luggage (not checked for delivery).' ?></li>
                                                </ol>
                                                <p><?= 'Please remember that craypots, golf clubs/buggies, fishing rods, diving equipment, surfboards and bikes will NOT be delivered to your accommodation. You will need to collect these directly from the ferry upon your arrival to Rottnest Island."' ?></p>
                                            </div>
                                            <p><?= 'Please add your accommodation details to ensure that your luggage is delivered to your room.' ?></p>
                                            <div class="form-group spinner py-2 required">
                                                <label for="luggageQuantity"><?= 'Luggage Quantity' ?></label>
                                                <?php if($extraDetails['luggage']['capacity'] > 0 ) { ?>
                                                    <input type="number" id="luggageQuantity" name="luggageQuantity" class="form-control number-input" value="0" min="1" required>
                                                <?php } else { ?>
                                                    <input type="number" id="luggageQuantity" name="luggageQuantity" class="form-control number-input" value="0" min="1" disabled="disabled">
                                                <?php } ?>
                                            </div>
                                            <div class="form-group required">
                                                <label for="accomodation"><?= 'Accommodation Name' ?></label>
                                                <select id="accommodation" name="accommodation" class="form-control" required v-model="selectedAccommodation" @change="onChange">
                                                    <option disabled value=""><?= 'Select an accommodation' ?></option>
                                                    <option v-for="(accommodation, index) in accommodations" v-bind:value="accommodation.value">{{accommodation.title }}</option>
                                                </select>
                                            </div>
                                            <!--  --------------------------
                                            ** Only show the item, when the accommodation requires item. So, DO NOT show item for barracks, discovery parks Rottnest, hostel, Karma Rottnest, because they do not have item!!!  
                                            ** Make sure that the scroll bar always visible when there is overflow options */
                                            -------------------  -->
                                            <template v-for="(accommodation, index) in accommodations">
                                                <div v-if="accommodation.value == selectedAccommodation" class="form-group required">
                                                    <label for="accomodationUnit" v-if="accommodation.units"><?= 'Accommodation Unit' ?></label>
                                                    <v-select :options="accommodation.units" v-model="unitSelected" v-if="accommodation.units" :clearable="false" placeholder="Select a item in your accommodation">
                                                    </v-select>
                                                </div>
                                            </template>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="luggageTerms">
                                                <label for="luggageTerms"><?= 'I acknowledge the terms above' ?></label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-cancel" data-dismiss="modal"><?= 'CANCEL' ?></button>
                                            <button id="saveLuggage" type="button" class="btn btn-sm btn-primary" :disabled="happyClick"><?= 'SAVE' ?></button>
                                            <button id="updateLuggage" type="button" class="btn btn-sm btn-primary" style="display:none;"><?= 'UPDATE' ?></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- Freights -->
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-8">
                                    <div class="row">
                                        <div class="col-md-7 col-12"><?= 'Take with you:' ?></div>
                                        <div class="col-md-5 d-none d-md-block text-right"><?= 'Cost per item' ?></div>
                                    </div>
                                </div>
                                <div class="col-4 text-right d-none d-md-block"><?= 'Quantity' ?></div>
                            </div>
                        </div>
                        <div class="card-body freight">
                            <?php if(isset($extraDetails['freight'])) { 
                                foreach($extraDetails['freight'] as $freight) { ?>
                                    <div class="row card-body-item">
                                        <div class="col-8">
                                            <div class="row">
                                                <div class="col-md-7"><?= $freight['Description'] ?></div>
                                                <div class="col-md-5 cost text-right">
                                                    <span class="money-currency"><?= '$' ?></span><span class="money-value"><?php if(isset($freight['price']['ResourcePrice']['NetPrice'])) echo $freight['price']['ResourcePrice']['NetPrice']; else echo "0.00" ?></span><span class="d-md-none meassure"><?= '/item' ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4 text-right">
                                            <?php if($freight['capacity'] > 0 ) { ?>
                                                <input name="bike" type="number" class="form-control number-input bike-luggage" value="0">
                                            <?php } else { ?>
                                                <input name="bike" type="number" class="form-control number-input bike-luggage" value="0" disabled="disabled">
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } 
                            } ?>
                        </div>
                    </div>
                </div> <!-- col-md-9-->
                <div class="col-lg-3 col-md-4">
                    <div class="booking-summary-wrapper">
                        <h2><?= 'Booking Summary' ?></h2>
                    </div>
                    <div id="accordionExample" class="summary-accordion">
                        <div class="card">
                            <div class="card-header">
                                <button type="button" data-toggle="collapse" data-target="#admissionFee" aria-expanded="true" aria-controls="admissionFee" class="btn btn-link"><i class="demo-icon icon-title icon-ticket"></i>
                                    <?= 'Admission Fee '?><i data-toggle="tooltip" title="" data-placement="top" tab-index="0" data-original-title="The entrance fee to an A-Class nature reserve and contributes to the conservation of the island. This is a government tax payable by all visitors to the island and is collected by the ferry companies on behalf of the Rottnest Island Authority" class="demo-icon icon-awesome-info-circle-icon ml-2 text-white"></i>
                                    <div class="icon-toggle"><i class="fas fa-chevron-up"></i></div>
                                </button>
                            </div>
                            <div id="admissionFee" aria-labelledby="admissionFee" class="booking-body collapse show">
                                <table class="booking-table">
                                    <tbody class="summary-item">
                                        <tr>
                                            <td class="table-item item-pass"><?= 'Adult (x1)' ?></td>
                                            <td class="table-item item-pass-price"><span class="money-currency"><?= '$' ?></span><span data-attribute="adult-price" class="money-value"><?= '19.50' ?></span></td>
                                        </tr>
                                        <tr>
                                            <td class="table-item item-pass"><?= 'Kid (x1)' ?></td>
                                            <td class="table-item item-pass-price"><span class="money-currency"><?= '$' ?></span><span data-attribute="child-price" class="money-value"><?= '7.00' ?></span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="line"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="table-item item-subtotal item-last"><?= 'Subtotal' ?></td>
                                            <td class="table-item item-price item-subtotal item-last"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '26.50' ?></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header"><button type="button" data-toggle="collapse" data-target="#ferries" aria-expanded="true" aria-controls="ferries" class="btn btn-link collapsed"><i class="demo-icon icon-title ferry-icon"></i><?= 'Ferries' ?>
                            <div class="icon-toggle"><i class="fas fa-chevron-up"></i></div></button></div>
                            <div id="ferries" aria-labelledby="ferries" class="booking-body collapse show">
                                <table class="booking-table">
                                    <tbody class="summary-item">
                                        <tr>
                                            <td colspan="2" class="table-item item-heading"><?= 'Hillarys to Rottnest Ferry Ticket' ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="table-item item-subheading"><?= 'Sun, 03 Jan 2021' ?><span class="write-depart-time"><?= '- 7:30am' ?></span></td>
                                        </tr>
                                        <tr class="temp-departure-show" style="display: table-row;">
                                            <td colspan="2" class="table-item item-fare-type item-depart-fare-type"><span class="quokka-saver"><?= 'Quokka Saver' ?></span></td>
                                        </tr>
                                        <tr class="temp-departure-show" style="display: table-row;">
                                            <td class="table-item item-pass"><?= 'Adult (x1)' ?></td>
                                            <td class="table-item item-pass-price"><span class="money-currency"><?= '$' ?></span><span data-attribute="adult-price" class="money-value"><?= '27.00' ?></span></td>
                                        </tr>
                                        <tr class="temp-departure-show" style="display: table-row;">
                                            <td class="table-item item-pass"><?= 'Child (x1)' ?></td>
                                            <td class="table-item item-pass-price"><span class="money-currency"><?= '$' ?></span><span data-attribute="child-price" class="money-value"><?= '18.00' ?></span></td>
                                        </tr>
                                        <tr class="temp-departure-show" style="display: table-row;"> 
                                            <td colspan="2">
                                                <div class="line"></div>
                                            </td>
                                        </tr>
                                        <tr class="temp-departure-show" style="display: table-row;">
                                            <td class="table-item item-subtotal item-last"><?= 'Subtotal' ?></td>
                                            <td class="table-item item-price item-subtotal item-last"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '45.00' ?></span></td>
                                        </tr>
                                    </tbody>
                                    <tbody class="summary-item">
                                        <tr>
                                            <td colspan="2" class="table-item item-heading"><?= 'Rottnest to Hillarys Ferry Ticket' ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="table-item item-subheading"><?= 'Tue, 05 Jan 2021 ' ?><span class="write-return-time"><?= '- 10:00am' ?></span></td>
                                        </tr>
                                        <tr class="temp-return-show" style="display: table-row;">
                                            <td colspan="2" class="table-item item-fare-type item-return-fare-type"><span class="everyday-fare"><?= 'Everyday Fare' ?></span></td>
                                        </tr>
                                        <tr class="temp-return-show" style="display: table-row;">
                                            <td class="table-item item-pass"><?= 'Adult (x1)' ?></td>
                                            <td class="table-item item-pass-price"><span class="money-currency"><?= '$' ?></span><span data-attribute="adult-price" class="money-value"><?= '34.50' ?></span></td>
                                        </tr>
                                        <tr class="temp-return-show" style="display: table-row;">
                                            <td class="table-item item-pass"><?= 'Child (x1)' ?></td>
                                            <td class="table-item item-pass-price"><span class="money-currency"><?= '$' ?></span><span data-attribute="child-price" class="money-value"><?= '20.50' ?></span></td>
                                        </tr>
                                        <tr class="temp-return-show" style="display: table-row;">
                                            <td colspan="2">
                                                <div class="line"></div>
                                            </td>
                                        </tr>
                                        <tr class="temp-return-show" style="display: table-row;">
                                            <td class="table-item item-subtotal item-last"><?= 'Subtotal' ?></td>
                                            <td class="table-item item-price item-subtotal item-last"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '55.00' ?></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card temp-bus-show" style="display: block;">
                            <div class="card-header"><button type="button" data-toggle="collapse" data-target="#bus" aria-expanded="true" aria-controls="bus" class="btn btn-link"><i class="demo-icon icon-title icon-bus"></i><?= 'Bus pickup' ?>
                            <div class="icon-toggle"><i class="fas fa-chevron-down"></i></div></button></div>
                            <div id="bus" aria-labelledby="bus" class="booking-body collapse show">
                                <table class="booking-table">
                                    <tbody class="summary-item">
                                        <tr>
                                            <td colspan="2" class="table-item item-subheading print-pickup-point"><?= 'Coolibah Lodge - 6:20am' ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="table-item item-pass"><?= 'Adult (x1)' ?></td>
                                            <td class="table-item item-pass-price"><span class="money-currency"><?= '$' ?></span><span data-attribute="adult-price" class="money-value"><?= '0.00' ?></span></td>
                                        </tr>
                                        <tr>
                                            <td class="table-item item-pass"><?= 'Child (x1)' ?></td> 
                                            <td class="table-item item-pass-price"><span class="money-currency"><?= '$' ?></span><span data-attribute="child-price" class="money-value"><?= '0.00' ?></span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="line"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="table-item item-subtotal item-last"><?= 'Subtotal' ?></td>
                                            <td class="table-item item-price item-subtotal item-last"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <button type="button" data-toggle="collapse" data-target="#luggage" aria-expanded="true" aria-controls="luggage" class="btn btn-link">
                                    <i class="demo-icon icon-title packages-icon"></i><?= 'Luggage' ?>
                                    <div class="icon-toggle">
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </button>
                            </div>
                            <div id="luggage" aria-labelledby="luggage" class="booking-body collapse show">
                                <table class="booking-table">
                                    <tbody class="summary-item">
                                        <tr class="temp-luggage-show">
                                            <td class="table-item item-pass">
                                                <?= 'Standard Luggage (x' ?><span class="print-luggage-quantity"></span><?= ')' ?>
                                            </td>
                                            <td class="table-item item-pass-price print-luggage-price">
                                                <span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span>
                                            </td>
                                        </tr>
                                        <tr class="bike-luggage-show temp-hide" style="display: none;">
                                            <td class="table-item item-pass">
                                                <?= 'Bike (x' ?><span class="print-bike-quantity"><?= '0' ?></span><?= ')' ?>
                                            </td>
                                            <td class="table-item item-pass-price print-bike-price">
                                                <span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span>
                                            </td>
                                        </tr>
                                        <tr class="surfboard-luggage-show temp-hide">
                                            <td class="table-item item-pass"><?= 'Surfboard (x' ?><span class="print-surfboard-quantity"></span><?= ')' ?></td>
                                            <td class="table-item item-pass-price print-surfboard-price"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span></td>
                                        </tr>
                                        <tr class="kayaks-luggage-show temp-hide">
                                            <td class="table-item item-pass"><?= 'Kayaks/Skis/SUP (x' ?><span class="print-kayaks-quantity"></span><?= ')' ?></td>
                                            <td class="table-item item-pass-price print-kayaks-price"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span></td>
                                        </tr>
                                        <tr class="divetank-luggage-show temp-hide">
                                            <td class="table-item item-pass"><?= 'Dive Tank/Cray Pot(x' ?><span class="print-divetank-quantity"></span><?= ')' ?></td>
                                            <td class="table-item item-pass-price print-divetank-price"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span></td>
                                        </tr>
                                        <tr class="tandem-luggage-show temp-hide">
                                            <td class="table-item item-pass"><?= 'Electric/Tandem Bike Freight(x' ?><span class="print-tandem-quantity"></span><?= ')' ?></td>
                                            <td class="table-item item-pass-price print-tandem-price"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span></td>
                                        </tr>
                                        <tr class="no-booking active">
                                            <td colspan="2" class="table-item pt-1"><?= 'No Item Selected' ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="line"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="table-item item-subtotal item-last"><?= 'Subtotal' ?></td>
                                            <td class="table-item item-price item-subtotal item-last"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <button type="button" data-toggle="collapse" data-target="#coupon" aria-expanded="true" aria-controls="coupon" class="btn btn-link collapsed"><i class="icon-title fas fa-tag"></i>
                                    <?= 'Coupon Discount' ?>
                                    <div class="icon-toggle">
                                        <i class="fas fa-chevron-up"></i>
                                    </div>
                                </button>
                            </div>
                            <div id="coupon" aria-labelledby="ferries" class="booking-body collapse show">
                                <table class="booking-table">
                                    <tbody class="summary-coupon active">
                                        <tr>
                                            <td class="table-item item-last item-heading"><?= 'Coupon Code' ?></td>
                                            <td id="couponRemove" class="table-item item-last text-right item-heading"><button id="removeCouponBtn" class="btn btn-primary btn-xs" style=""><?= 'Remove' ?></button></td>
                                        </tr>
                                        <tr class="coupon-input-row" style="display: none;">
                                            <td colspan="2" class="table-item coupon-input-col form-group item-last"><span class="input-group"><input type="text" id="couponInput" placeholder="Coupon Code" aria-label="Coupon Code" aria-describedby="Coupon Code" class="text-uppercase form-control"> <button id="couponBtn" class="btn btn-primary btn-xs"><?= 'Apply' ?></button></span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="coupon-bg">
                                                <table class="coupon-table">
                                                    <tbody>
                                                        <tr>
                                                            <td class="table-item coupon-input-col form-group item-last"><span class="coupon-code"><?= 'HAPPYDAY' ?></span></td>
                                                            <td class="table-item item-last item-coupon-btn item-price"> <span class="money-currency"><?= '-$' ?></span><span class="money-value"><?= '12.65' ?></span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="grand-total">
                            <table class="booking-table">
                                <tbody>
                                    <tr>
                                        <td class="table-item item-total"><?= 'Current Total' ?></td>
                                        <td class="table-item item-total item-price"><span class="money-currency"><?= '$' ?></span> <span class="money-value"><?= '113.85' ?></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Step 3: Extras-->
        <section class="extra-options py-3 extras-block">
            <div class="row">
                <div class="col-lg-9 col-md-8 pb-3">
                    <h2><?= 'Extras' ?></h2>
                    <p class="instruction"><?= 'Explore our range of hire equipment and tour options to enhance your Rottnest Island experience.' ?>
                    </p>

                    <?php if(isset($extraDetails['extras']['RFFB'])) { ?>
                        <div id="bikeHire" class="extra-item">
                            <h3><?= 'Bike Hire' ?></h3>
                            <?php foreach($extraDetails['extras']['RFFB'] as $rffb) { ?>
                                <!-- @Adults Bike @adultsBike @bike @Bike-->
                                <?php if($rffb['ResourceCode'] == 'BIK') { ?>
                                    <?php $bikeName = str_replace("Hire", "", $rffb['ResourceDescription']); ?>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <!-- *** To developer: LUKE will need a way to change the image and description. Image & description subject to change at this stage **** -->
                                                    <!-- *** To developer:Please discuss with Luke about CMS or solution to this **** -->
                                                    <!-- *** Can it be taken from this JSON data https://www.farwestscallops.com/scallop-recipes?format=json-pretty ?? For example, the text can be generated from the items.excerpt data and the image from items.assetUrl?
                                                    Please tak to us as well!!! The website will be moved to Squarespace and the website will generate similar data structure to this *** -->
                                                    <img src="../assets/img/bike.jpg" class="img-responsive">
                                                </div>
                                                <div class="col-sm-8 d-flex flex-column">
                                                    <h4 class="extra-title mt-3 mt-sm-0"><?= $bikeName ?></h4>
                                                    <p><?= 'Unisex bike featuring 26 inch tyres, three gear capacities with a front and back hand break as well as a comfortable adjustable seat.' ?></p>
                                                    <div class="d-flex w-100 flex-row justify-content-between align-items-end h-100">
                                                        <div class="card-price-wrapper mr-2">
                                                            <?= 'From' ?><span class="money-currency">&nbsp;<?= '$' ?></span><span class="money-value"><?php if(isset($rffb['Prices']['Price'][0]['Value'])) echo $rffb['Prices']['Price'][0]['Value']; else if(isset($rffb['Prices']['Price']['Value'])) echo $rffb['Prices']['Price']['Value']; else echo "00.00" ?></span><?= '/person' ?>
                                                        </div>
                                                        <div class="select-button-wrapper">
                                                            <div class="card-btn-wrapper">
                                                                <button id="removeAdultsBike" type="button" class="btn btn-xs btn-remove remove-extra" style="display: none;"><?= 'Remove' ?></button>
                                                            </div>
                                                            <div class="card-btn-wrapper">
                                                                <a class="btn btn-green btn-toggle" data-toggle="collapse" data-target="#adultsBikeOption" aria-expanded="false"   aria-controls="adultsBikeOption"><?= 'Select ' ?><i class="fas fa-angle-down"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 extra-toggle">
                                                    <!-- Pricing Toggle -->
                                                    <div id="adultsBikeOption" class="collapse extra-extension" aria-labelledby="adultsBikeOption">
                                                        <div class="row"> 
                                                            <div class="col-lg-6 form-group">
                                                                <label for="adultsBikeDate"><?= 'Booking from - Booking until' ?></label>
                                                                <input type="text" name="adultsBikeDate" id="adultsBikeDate" class="form-control equipmentDate" value="" disabled />
                                                            </div>
                                                            <div class="col-lg-3 form-group">
                                                                <label for="bikeQuantity"><?= 'Quantity' ?></label>
                                                                <?php if($rffb['Capacity'] > 0 ) { ?>
                                                                    <input type="number" id="adultsBikeQuantity" name="adultsBikeQuantity" class="form-control number-input bike-quantity" value="0" min="1" required>
                                                                <?php } else { ?>
                                                                    <input type="number" id="adultsBikeQuantity" name="adultsBikeQuantity" class="form-control number-input bike-quantity" value="0" min="1" disabled="disabled">
                                                                <?php } ?>
                                                            </div>
                                                            <div class="col-lg-3 align-self-end text-right form-group">
                                                                <button id="addAdultsBike" class="btn btn-green btn-add add-equipment"><?= 'Add' ?></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <!-- @ChildBike @childBike -->
                                <?php if($rffb['ResourceCode'] == 'CBE' || $rffb['ResourceCode'] == 'CBL' || $rffb['ResourceCode'] == 'CBM') { ?>
                                    <?php $bikeType = "";
                                    if($rffb['ResourceCode'] == 'CBE') {
                                        $bikeType = "extraSmallChildBike";
                                    } if($rffb['ResourceCode'] == 'CBL') {
                                        $bikeType = "largeChildBike";
                                    } if($rffb['ResourceCode'] == 'CBM') {
                                        $bikeType = "mediumChildBike";
                                    }
                                    $childBikeName = str_replace("Hire", "", $rffb['ResourceDescription']); ?>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <!-- *** To developer: LUKE will need a way to change the image and description. Image & description subject to change at this stage **** -->
                                                    <!-- *** To developer:Please discuss with Luke about CMS or solution to this **** -->
                                                    <!-- *** Can it be taken from this JSON data https://www.farwestscallops.com/scallop-recipes?format=json-pretty ?? For example, the text can be generated from the items.excerpt data and the image from items.assetUrl?
                                                    Please tak to us as well!!! The website will be moved to Squarespace and the website will generate similar data structure to this *** -->
                                                    <img src="../assets/img/bike.jpg" class="img-responsive">
                                                </div>
                                                <div class="col-sm-8 d-flex flex-column">
                                                    <h4 class="extra-title mt-3 mt-sm-0"><?= $childBikeName ?></h4>
                                                    <p><?= 'Junior bike suitable for [applicable age and tyre size], rear foot brakes and comfortable adjustable seats. Our bikes are available in Large and Medium. Please note our child bikes do not include training wheels.' ?></p>
                                                    <div class="d-flex w-100 flex-row justify-content-between align-items-end h-100">
                                                        <div class="card-price-wrapper mr-2">
                                                            <?= 'From' ?><span class="money-currency">&nbsp;<?= '$' ?></span><span class="money-value"><?php if(isset($rffb['Prices']['Price'][0]['Value'])) echo $rffb['Prices']['Price'][0]['Value']; else if(isset($rffb['Prices']['Price']['Value'])) echo $rffb['Prices']['Price']['Value']; else echo "00.00" ?></span><?= '/person' ?>
                                                        </div>
                                                        <div class="select-button-wrapper">
                                                            <div class="card-btn-wrapper">
                                                                <button id="removeChildBike" type="button" class="btn btn-xs btn-remove remove-extra"style="display: none;"><?= 'Remove' ?></button>
                                                            </div>
                                                            <div class="card-btn-wrapper">
                                                                <a class="btn btn-green btn-toggle" data-toggle="collapse" data-target="#<?= $bikeType ?>" aria-expanded="false" aria-controls="<?= $bikeType ?>"><?= 'Select ' ?><i class="fas fa-angle-down"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 extra-toggle">
                                                    <!-- Pricing Toggle -->
                                                    <div id="<?= $bikeType ?>" class="collapse extra-extension" aria-labelledby="<?= $bikeType ?>">
                                                        <div class="bike-row">
                                                            <div class="form-group calendar-col">
                                                                <label for="<?= $bikeType ?>Date"><?= 'Booking from - Booking until' ?></label>
                                                                <input type="text" name="<?= $bikeType ?>Date" id="<?= $bikeType ?>Date" class="form-control equipmentDate" value="" disabled />
                                                            </div>
                                                            <div class="bike-quantity-col">
                                                                <?php if($rffb['ResourceCode'] == 'CBL') { ?>
                                                                    <div class="form-group  bike-item">
                                                                        <label for="<?= $bikeType ?>Quantity"><?= 'Large Bike' ?></label>
                                                                        <input type="number" id="<?= $bikeType ?>Quantity" name="<?= $bikeType ?>Quantity" class="form-control number-input bike-quantity" value="0" min="1" <?php if($rffb['Capacity'] > 0 ) { ?> required <?php } else { ?> disabled="disabled" <?php } ?>>
                                                                    </div>
                                                                <?php } ?>
                                                                <?php if($rffb['ResourceCode'] == 'CBM') { ?>
                                                                    <div class="form-group bike-item">
                                                                        <label for="mediumBikeQuantity"><?= 'Medium Bike' ?></label>
                                                                        <input type="number" id="mediumBikeQuantity" name="mediumBikeQuantity" class="form-control number-input bike-quantity" value="0" min="1" <?php if($rffb['Capacity'] > 0 ) { ?> required <?php } else { ?> disabled="disabled" <?php } ?>>
                                                                    </div>
                                                                <?php } ?>
                                                                <?php if($rffb['ResourceCode'] == 'CBE') { ?>
                                                                     <div class="form-group bike-item">
                                                                        <label for="extraSmallBikeQuantity"><?= 'Extra Small Bike' ?></label>
                                                                        <input type="number" id="extraSmallBikeQuantity" name="extraSmallBikeQuantity" class="form-control number-input bike-quantity" value="0" min="1" <?php if($rffb['Capacity'] > 0 ) { ?> required <?php } else { ?> disabled="disabled" <?php } ?>>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="align-self-end text-right form-group">
                                                                <button id="addChildBike" class="btn btn-green btn-add add-equipment"><?= 'Add' ?></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>

                        <!-- @Snorkle Hire -->
                        <div id="snorkleHire" class="extra-item">
                            <h3><?= 'Snorkelling Equipment Hire' ?></h3>
                            <div class="card">
                                <?php foreach($extraDetails['extras']['RFFB'] as $rffb) { 
                                    if($rffb['ResourceCode'] == 'SN'){ ?>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <!-- *** To developer: LUKE will need a way to change the image and description. Image & description subject to change at this stage **** -->
                                                    <!-- *** To developer:Please discuss with Luke about CMS or solution to this **** -->
                                                    <!-- *** Can it be taken from this JSON data https://www.farwestscallops.com/scallop-recipes?format=json-pretty ?? For example, the text can be generated from the items.excerpt data and the image from items.assetUrl?
                                                    Please tak to us as well!!! The website will be moved to Squarespace and the website will generate similar data structure to this *** -->
                                                    <img src="../assets/img/snorkelling.jpg" class="img-responsive">
                                                </div>
                                                <div class="col-sm-8 d-flex flex-column">
                                                    <h4 class="extra-title mt-3 mt-sm-0"><?= 'Snorkel, Flippers & Mask Set' ?></h4>
                                                    <p><?= 'Collect your snorkelling equipment from our Terminal at Hillarys Boat Harbour before you board. When you arrive to Rottnest Island, step off the ferry and head straight to the best snorkelling spots on the Island! Set includes: snorkel and mask, flippers, and a mesh bag with shoulder strap to carry your equipment.' ?></p>
                                                    <div class="d-flex w-100 flex-row justify-content-between align-items-end h-100">
                                                        <div class="card-price-wrapper mr-2">
                                                            <?= 'From' ?><span class="money-currency">&nbsp;<?= '$' ?></span><span class="money-value"><?php if(isset($rffb['Prices']['Price'][0]['Value'])) echo $rffb['Prices']['Price'][0]['Value']; else if(isset($rffb['Prices']['Price']['Value'])) echo $rffb['Prices']['Price']['Value']; else echo "00.00" ?></span><?= '/person' ?>
                                                        </div>
                                                        <div class="select-button-wrapper">
                                                            <div class="card-btn-wrapper">
                                                                <button id="removeSnorkelling" type="button" class="btn btn-xs btn-remove remove-extra" style="display: none;"><?= 'Remove' ?></button>
                                                            </div>
                                                            <div class="card-btn-wrapper">
                                                                <a class="btn btn-green btn-toggle" data-toggle="collapse" data-target="#snorkelling" aria-expanded="false" aria-controls="snorkelling"><?= 'Select ' ?><i class="fas fa-angle-down"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 extra-toggle">
                                                    <!-- Pricing Toggle -->
                                                    <div id="snorkelling" class="collapse extra-extension" aria-labelledby="snorkelling">
                                                        <div class="row">
                                                            <div class="col-lg-6 form-group">
                                                                <label for="snorkellingDate"><?= 'Booking from - Booking until' ?></label>
                                                                <input type="text" name="snorkellingDate" id="snorkellingDate" class="form-control equipmentDate" value="" disabled />
                                                            </div>
                                                            <div class="col-lg-3 form-group">
                                                                <label for="snorkellingQuantity"><?= 'Quantity' ?></label>
                                                                <input type="number" id="snorkellingQuantity" name="snorkellingQuantity" class="form-control number-input" value="0" min="1" <?php if($rffb['Capacity'] > 0 ) { ?> required <?php } else { ?> disabled="disabled" <?php } ?> >
                                                            </div>
                                                            <div class="col-lg-3 align-self-end text-right form-group">
                                                                <button class="btn btn-green btn-add add-equipment"><?= 'Add' ?></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                                } ?>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- @Bus -->
                    <div id="busPass" class="extra-item">
                        <h3><?= 'Bus Pass' ?></h3>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <!-- *** To developer: LUKE will need a way to change the image and description. Image & description subject to change at this stage **** -->
                                        <!-- *** To developer:Please discuss with Luke about CMS or solution to this **** -->
                                        <!-- *** Can it be taken from this JSON data https://www.farwestscallops.com/scallop-recipes?format=json-pretty ?? For example, the text can be generated from the items.excerpt data and the image from items.assetUrl?
                                        Please tak to us as well!!! The website will be moved to Squarespace and the website will generate similar data structure to this *** -->
                                        <img src="../assets/img/bayseeker-island-tour.jpg" class="img-responsive">
                                    </div>
                                    <div class="col-sm-8 d-flex flex-column">
                                        <h4 class="extra-title mt-3 mt-sm-0"><?= 'Island Explorer Bus Pass' ?></h4>
                                        <p><?= "Upgrade your ferry transfer and experience the beauty of Rottnest Island with the easy and convenient Island Explorer Bus Pass. This service allows you to enjoy spectacular views as you travel around the Island in air-conditioned comfort, with the benefit of hopping on and off at any of Rottnest's 19 designated bus stops throughout your day" ?></p>
                                        <div class="d-flex w-100 flex-row justify-content-between align-items-end h-100">
                                            <div class="card-price-wrapper">
                                                <div class="card-price adult-price mr-3"><span class="money-currency">&nbsp;<?= '$' ?></span><span class="money-value"><?= '20.00' ?></span><?= '/adult' ?></div>
                                                <div class="card-price child-price mr-3"><span class="money-currency">&nbsp;<?= '$' ?></span><span class="money-value"><?= '16.00' ?></span><?= '/child' ?></div>
                                                <div class="card-price consession-price mr-3"><span class="money-currency">&nbsp;<?= '$' ?></span><span class="money-value"><?= '28.00' ?></span><?= '/consession' ?></div>
                                            </div>
                                            <div class="select-button-wrapper">
                                                <div class="card-btn-wrapper">
                                                    <button id="removeBuss" type="button" class="btn btn-xs btn-remove remove-extra" style="display: none;"><?= 'Remove' ?></button>
                                                </div>
                                                <div class="card-btn-wrapper">
                                                    <a class="btn btn-green btn-toggle" data-toggle="collapse" data-target="#busPassExtra" aria-expanded="false" aria-controls="busPassExtra"><?= 'Select ' ?><i class="fas fa-angle-down"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 extra-toggle">
                                        <!-- Pricing Toggle -->
                                        <div id="busPassExtra" class="collapse extra-extension" aria-labelledby="busPassExtra">
                                            <div class="row">
                                                <div class="col-xl-10 col-lg-9">
                                                    <div class="quantity-wrapper-tour">
                                                        <div class="form-group">
                                                            <label for="adultBusQuantity"><?= 'Adult' ?></label>
                                                            <input id="adultBusQuantity" name="adultBusQuantity" type="number" class="form-control number-input" value="0">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="childBusQuantity"><?= 'Child (4-12)' ?></label>
                                                            <input id="childBusQuantity" name="childBusQuantity" type="number" class="form-control number-input" value="0">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="consessionBusQuantity"><?= 'Concession ' ?><i class="demo-icon icon-awesome-info-circle-icon" data-toggle="tooltip" title="Student Card Holders, Australian Seniors and Pension Card Holders" data-placement="top" tab-index="0"></i> </label>
                                                            <input id="consessionBusQuantity" name="consessionBusQuantity" type="number" class="form-control number-input" value="0">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 align-self-end text-right form-group">
                                                    <button class="btn btn-green btn-add add-equipment" data-attribute="busPass"><?= 'Add' ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- @Tour -->
                    <div id="tours" class="extra-item">
                        <h3><?= 'Tours' ?></h3>
                        <!-- Historical Tour-->
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <!-- *** To developer: LUKE will need a way to change the image and description. Image & description subject to change at this stage **** -->
                                        <!-- *** To developer:Please discuss with Luke about CMS or solution to this **** -->
                                        <!-- *** Can it be taken from this JSON data https://www.farwestscallops.com/scallop-recipes?format=json-pretty ?? For example, the text can be generated from the items.excerpt data and the image from items.assetUrl?
                                        Please tak to us as well!!! The website will be moved to Squarespace and the website will generate similar data structure to this *** -->
                                        <img src="../assets/img/historical-train-tour.jpg" class="img-responsive">
                                    </div>
                                    <div class="col-sm-8 d-flex flex-column">
                                        <h4 class="extra-title mt-3 mt-sm-0"><?= 'Historical Train & Tunnel Tour' ?></h4>
                                        <p><?= 'Enjoy a breathtaking train ride to Oliver Hill and be guided on a tour of the massive 9.2 inch diameter gun and tunnel system that forms part of Rottnest’s military heritage, complete with morning tea and lunch.<br>Duration: 1Hr 30Min' ?></p>
                                        <div class="d-flex w-100 flex-row justify-content-between align-items-end h-100"> 
                                            <div class="card-price-wrapper">
                                                <div class="card-price adult-price mr-3"><span class="money-currency"> <?= '$' ?></span><span class="money-value"><?= '33.00'  ?></span><?= '/adult' ?></div>
                                                <div class="card-price child-price mr-3"><span class="money-currency"> <?= '$' ?></span><span class="money-value"><?= '16.00' ?></span><?= '/child' ?></div>
                                                <div class="card-price consession-price mr-3">
                                                    <span class="money-currency"><?= ' $' ?></span>
                                                    <span class="money-value"><?= '28.00' ?></span><?= '/consession' ?>
                                                </div>
                                            </div>
                                            <div class="select-button-wrapper">
                                                <div class="card-btn-wrapper">
                                                    <button id="removeBus" type="button" class="btn btn-xs btn-remove remove-extra" data-attribute="historicalTour" style="display: none;"><?= 'Remove' ?></button>
                                                </div>
                                                <div class="card-btn-wrapper">
                                                    <a class="btn btn-green btn-toggle" data-toggle="collapse" data-target="#historicalTour" aria-expanded="false" aria-controls="historicalTour"><?= 'Select ' ?><i class="fas fa-angle-down"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 extra-toggle">
                                        <!-- Pricing Toggle -->
                                        <div id="historicalTour" class="collapse extra-extension" aria-labelledby="historicalTour">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-12">
                                                    <label for="adultHistorical"><?= 'Tour date and time' ?></label>
                                                    <div class="row">
                                                        <div class="form-group col-7 pr-0">
                                                            <!-- If the departing and return day is at the same day, please disable the date picker.. Lock it -->
                                                            <!-- Disable all dates, unless available. For example, tour might be full on Sun,03 -> Please disable this in the calendar -->
                                                            <!-- Prevent clashing of booking. Please disable the booking date if customer already booked a tour for the same date and time -->
                                                            <input type="text" name="adultHistorical" id="adultHistorical" class="singleDatePicker form-control">
                                                        </div>
                                                        <div class="form-group col-5 pl-0">
                                                            <select class="tourTime form-control" disabled>
                                                                <!-- If only available at one time, please disable the option-->
                                                                <option value="13:30">13:30</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-12">
                                                    <div class="quantity-wrapper-tour">
                                                        <div class="form-group">
                                                            <label for="adultHistorical"><?= 'Adult' ?></label>
                                                            <input id="adultHistorical" name="adultHistorical" type="number" class="form-control number-input adult-quantity" value="0">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="childHistorical"><?= 'Child' ?></label>
                                                            <input id="childHistorical" name="childHistorical" type="number" class="form-control number-input child-quantity" value="0">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="consessionHistorical"><?= 'Concession' ?>&nbsp;<i class="demo-icon icon-awesome-info-circle-icon" data-toggle="tooltip" title="Student Card Holders, Australian Seniors and Pension Card Holders" data-placement="top" tab-index="0"></i> </label>
                                                            <input id="consessionHistorical" name="consessionHistorical" type="number" class="form-control number-input consession-quantity" value="0">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 align-self-end text-right form-group">
                                                    <button class="btn btn-green btn-add add-equipment" data-attribute="historicalTour"><?= 'Add' ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bayseeker Island Tour-->
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                    <!-- *** To developer: LUKE will need a way to change the image and description. Image & description subject to change at this stage **** -->
                                    <!-- *** To developer:Please discuss with Luke about CMS or solution to this **** -->
                                    <!-- *** Can it be taken from this JSON data https://www.farwestscallops.com/scallop-recipes?format=json-pretty ?? For example, the text can be generated from the items.excerpt data and the image from items.assetUrl?
                                    Please tak to us as well!!! The website will be moved to Squarespace and the website will generate similar data structure to this *** -->
                                    <img src="../assets/img/bayseeker-island-tour.jpg" class="img-responsive">
                                </div>
                                <div class="col-sm-8 d-flex flex-column">
                                    <h4 class="extra-title mt-3 mt-sm-0"><?= 'Bayseeker Island Tour ' ?></h4>
                                    <p><?= 'Explore the stunning beauty of this A-Class reserve in air conditioned comfort, taking in the amazing fauna and flora of the island, stunning beaches, woodlands and incredible salt lakes. This 2 hour island tour includes all of Rottnest’s must see locations, including Wadjemup Lighthouse, Henrietta Rocks, the majestic look-out point at the rugged Cathedral Rocks and Cape Vlamingh. All major habitats are covered on this amazing tour, including Rottnest’s cultural and historical heritage ranging from maritime, colonial and military history to future developments.' ?><br>
                                        <?= 'Duration: 1Hr 30Min' ?>
                                    </p>
                                    <div class="d-flex w-100 flex-row justify-content-between align-items-end h-100">
                                        <div class="card-price-wrapper">
                                            <div class="card-price adult-price mr-3"><span class="money-currency"><?= ' $' ?></span><span class="money-value"><?= '33.00' ?></span><?= '/adult' ?></div>
                                            <div class="card-price child-price mr-3"><span class="money-currency"><?= ' $' ?></span><span class="money-value"><?= '26.00' ?></span><?= '/child' ?></div>
                                            <div class="card-price consession-price mr-3"><span class="money-currency"><?= ' $' ?></span><span class="money-value"><?= '38.00' ?></span><?= '/consession' ?></div>
                                        </div>
                                        <div class="select-button-wrapper">
                                            <div class="card-btn-wrapper">
                                                <button id="removeBuss" type="button" class="btn btn-xs btn-remove remove-extra" data-attribute="baySeekerIslandTour" style="display: none;"><?= 'Remove' ?></button>
                                            </div>
                                            <div class="card-btn-wrapper">
                                                <a class="btn btn-toggle btn-disabled disabled"><?= 'UNAVAILABLE'  ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 extra-toggle">
                                    <!-- Pricing Toggle -->
                                    <div id="bayIslandTour" class="collapse extra-extension" aria-labelledby="bayIslandTour">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-12">
                                                <label for="adultHistorical"><?= 'Tour date and time' ?></label>
                                                <div class="row">
                                                    <div class="form-group col-7 pr-0">
                                                        <!-- If the departing and return day is at the same day, please disable the date picker.. Lock it -->
                                                        <!-- Disable all dates, unless available. For example, tour might be full on Sun,03 -> Please disable this in the calendar -->
                                                        <!-- Prevent clashing of booking. Please disable the booking date if customer already booked a tour for the same date and time -->
                                                        <input type="text" name="adultHistorical" id="adultHistorical" class="singleDatePicker form-control">
                                                    </div>
                                                    <div class="form-group col-5 pl-0">
                                                        <select class="tourTime form-control">
                                                            <!-- If only available at one time, please disable the option-->
                                                            <option disabled value=""><?= 'Select tour time ' ?></option>
                                                            <option value="13:45"><?= '13:45' ?></option>
                                                            <option value="00:01"><?= '00:01' ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-12">
                                                <div class="quantity-wrapper-tour">
                                                    <div class="form-group">
                                                        <label for="adultBayIsland"><?= 'Adult' ?></label>
                                                        <input id="adultBayIsland" name="adultBayIsland" type="number" class="form-control number-input adult-quantity" value="0">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="childBayIsland"><?= 'Child' ?></label>
                                                        <input id="childBayIsland" name="childBayIsland" type="number" class="form-control number-input child-quantity" value="0">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="consessionBayIsland"><?= 'Concession' ?>&nbsp;<i class="demo-icon icon-awesome-info-circle-icon" data-toggle="tooltip" title="Student Card Holders, Australian Seniors and Pension Card Holders" data-placement="top" tab-index="0"></i> </label>
                                                        <input id="consessionBayIsland" name="consessionBayIsland" type="number" class="form-control number-input consession-quantity" value="0">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 align-self-end text-right form-group">
                                                <button class="btn btn-green btn-add add-equipment" data-attribute="baySeekerIslandTour"><?= 'Add' ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Grand Island Tour-->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <!-- *** To developer: LUKE will need a way to change the image and description. Image & description subject to change at this stage **** -->
                                    <!-- *** To developer:Please discuss with Luke about CMS or solution to this **** -->
                                    <!-- *** Can it be taken from this JSON data https://www.farwestscallops.com/scallop-recipes?format=json-pretty ?? For example, the text can be generated from the items.excerpt data and the image from items.assetUrl?
                                    Please tak to us as well!!! The website will be moved to Squarespace and the website will generate similar data structure to this *** -->
                                    <img src="../assets/img/grand-isalnd-tour.jpg" class="img-responsive">
                                </div>
                                <div class="col-sm-8 d-flex flex-column">
                                    <h4 class="extra-title mt-3 mt-sm-0"><?= 'Grand Island Tour ' ?></h4>
                                    <p><?= 'Explore the stunning beauty of this A Class reserve in air conditioned comfort, taking in the amazing fauna and flora of the island, stunning beaches, woodlands and incredible salt lakes – all commencing with a unique historic train ride. Wadjemup Lighthouse, Henrietta Rocks, the majestic look-out point at the rugged Cathedral Rocks and Cape Vlamingh along with a guided tour of the Oliver Hill Guns and Tunnels. All major habitats are covered on this amazing tour, including Rottnest’s cultural and historical heritage ranging from Maritime and Military history right up to future developments.' ?><br>
                                        <?= 'Duration: 4 Hours' ?>
                                    </p>
                                    <div class="d-flex w-100 flex-row justify-content-between align-items-end h-100">
                                        <div class="card-price-wrapper">
                                            <div class="card-price adult-price mr-3"><span class="money-currency"><?= ' $' ?></span><span class="money-value"><?= '84.00' ?></span><?= '/adult' ?></div>
                                            <div class="card-price child-price mr-3"><span class="money-currency"><?= ' $'  ?></span><span class="money-value"><?= '64.00' ?></span><?= '/child' ?></div>
                                            <div class="card-price consession-price mr-3"><span class="money-currency"><?= ' $' ?></span><span class="money-value"><?= '79.00' ?></span><?= '/consession' ?></div>
                                        </div>
                                        <div class="select-button-wrapper">
                                            <div class="card-btn-wrapper">
                                                <button id="removeBuss" type="button" class="btn btn-xs btn-remove remove-extra" data-attribute="grandIslandTour" style="display: none;"><?= 'Remove' ?></button>
                                            </div>
                                            <div class="card-btn-wrapper">
                                                <a class="btn btn-green btn-toggle" data-toggle="collapse" data-target="#grandIslandTour" aria-expanded="false" aria-controls="grandIslandTour"><?= 'Select ' ?><i class="fas fa-angle-down"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 extra-toggle">
                                    <!-- Pricing Toggle -->
                                    <div id="grandIslandTour" class="collapse extra-extension" aria-labelledby="grandIslandTour">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-12">
                                                <label for="adultHistorical"><?= 'Tour date and time' ?></label>
                                                <div class="row">
                                                    <div class="form-group col-7 pr-0">
                                                        <!-- If the departing and return date is within the same day, please disable the date picker.. Lock it, so there is no date picker dropdown -->
                                                        <!-- Disable all dates, unless available. For example, tour might be full on Sun,03 -> Please disable this in the calendar -->
                                                        <!-- Prevent date & time clashing in book Please disable the booking date if customer already booked a tour for the same date and time -->
                                                        <input type="text" name="adultHistorical" id="adultHistorical" class="singleDatePicker form-control">
                                                    </div>
                                                    <div class="form-group col-5 pl-0">
                                                        <select class="tourTime form-control">
                                                            <!-- If only available at one time, please disable the option-->
                                                            <option disabled value=""><?= 'Select tour time ' ?></option>
                                                            <option value="11:30"><?= '11:30' ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-12">
                                                <div class="quantity-wrapper-tour">
                                                    <div class="form-group">
                                                        <label for="adultGrandIsland"><?= 'Adult' ?></label>
                                                        <input id="adulGrandIsland" name="adulGrandIsland" type="number" class="form-control number-input adult-quantity" value="0">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="childGrandIsland"><?= 'Child' ?></label>
                                                        <input id="childGrandIsland" name="childGrandIsland" type="number" class="form-control number-input child-quantity" value="0">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="consessionGrandIsland"><?= 'Concession' ?>&nbsp;<i class="demo-icon icon-awesome-info-circle-icon" data-toggle="tooltip" title="Student Card Holders, Australian Seniors and Pension Card Holders" data-placement="top" tab-index="0"></i> </label>
                                                        <input id="consessionGrandIsland" name="consessionGrandIsland" type="number" class="form-control number-input consession-quantity" value="0">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 align-self-end text-right form-group">
                                                <button class="btn btn-green btn-add add-equipment" data-attribute="grandIslandTour"><?= 'Add' ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="merchandise" class="extra-item">
                    <h3><?= 'Merchandise' ?></h3>
                    <div id="tshirt" class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <!-- *** To developer: LUKE will need a way to change the image and description. Image & description subject to change at this stage **** -->
                                    <!-- *** To developer:Please discuss with Luke about CMS or solution to this **** -->
                                    <!-- *** Can it be taken from this JSON data https://www.farwestscallops.com/scallop-recipes?format=json-pretty ?? For example, the text can be generated from the items.excerpt data and the image from items.assetUrl?
                                    Please tak to us as well!!! The website will be moved to Squarespace and the website will generate similar data structure to this *** -->
                                    <img src="../assets/img/merch-tshirt.jpg" class="img-responsive">
                                </div>
                                <div class="col-sm-8 d-flex flex-column">
                                    <h4 class="extra-title mt-3 mt-sm-0"><?= 'Unisex T-Shirt'  ?></h4>
                                    <p><?= 'Get your exclusive Rottnest Fast Ferries T-Shirt today and wear it on your trip. Available in various sizes: S, M, L, XL, XXL.' ?></p>
                                    <div class="d-flex w-100 flex-row justify-content-between align-items-end h-100">
                                        <div class="card-price-wrapper mr-2">
                                            <span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '26.00' ?></span><?= '/item' ?>
                                        </div>
                                        <div class="select-button-wrapper">
                                            <div class="card-btn-wrapper">
                                                <button type="button" class="btn btn-xs btn-remove remove-extra" style="display: none;"><?= 'Remove' ?></button>
                                            </div>
                                            <div class="card-btn-wrapper">
                                                <a class="btn btn-green btn-toggle" data-toggle="collapse" data-target="#tShirt" aria-expanded="false" aria-controls="tShirt"><?= 'Select ' ?><i class="fas fa-angle-down"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 extra-toggle">
                                    <!-- Pricing Toggle -->
                                    <div id="tShirt" class="collapse extra-extension" aria-labelledby="tShirt">
                                        <div class="row">
                                            <div class="col-xl-10 col-lg-9">
                                                <div class="merch-quantity-row">
                                                    <div class="form-group">
                                                        <label for="small-tshirt-quantity"><?= 'Size S' ?></label>
                                                        <input id="small-tshirt-quantity" name="small-tshirt-quantity" type="number" class="form-control number-input small-tshirt-quantity" value="0">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="medium-tshirt-quantity"><?= 'Size M' ?></label>
                                                        <input id="medium-tshirt-quantity" name="medium-tshirt-quantity" type="number" class="form-control number-input medium-tshirt-quantity" value="0">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="large-tshirt-quantity"><?= 'Size L' ?></label>
                                                        <input id="large-tshirt-quantity" name="large-tshirt-quantity" type="number" class="form-control number-input large-tshirt-quantity" value="0">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="extra-large-tshirt-quantity"><?= 'Size XL' ?></label>
                                                        <input id="extra-large-tshirt-quantity" name="extra-large-tshirt-quantity" type="number" class="form-control number-input extra-large-tshirt-quantity" value="0">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="extra-extra-large-tshirt-quantity"><?= 'Size XXL' ?></label>
                                                        <input id="extra-extra-large-tshirt-quantity" name="extra-extra-large-tshirt-quantity" type="number" class="form-control number-input extra-extra-large-tshirt-quantity" value="0">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 align-self-end text-right form-group">
                                                <button class="btn btn-green btn-add add-equipment" data-attribute="tShirt"><?= 'Add' ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- col-md-9-->
            <div class="col-lg-3 col-md-4">
                <div class="booking-summary-wrapper">
                    <h2> Booking Summary</h2>
                </div>
                <div id="accordionExample" class="summary-accordion">
                    
                    <!-- @Admission Fee-->
                    
                    <div class="card">
                        <div class="card-header">
                            <button type="button" data-toggle="collapse" data-target="#admissionFee" aria-expanded="true" aria-controls="admissionFee" class="btn btn-link"><i class="demo-icon icon-title icon-ticket"></i><?= 'Admission Fee ' ?><i data-toggle="tooltip" title="" data-placement="top" tab-index="0" data-original-title="The entrance fee to an A-Class nature reserve and contributes to the conservation of the island. This is a government tax payable by all visitors to the island and is collected by the ferry companies on behalf of the Rottnest Island Authority" class="demo-icon icon-awesome-info-circle-icon ml-2 text-white"></i><div class="icon-toggle"><i class="fas fa-chevron-up"></i></div></button>
                        </div>
                        <div id="admissionFee" aria-labelledby="admissionFee" class="booking-body collapse show">
                            <table class="booking-table">
                                <tbody class="summary-item">
                                    <tr>
                                        <td class="table-item item-pass"><?= 'Adult (x1)' ?></td>
                                        <td class="table-item item-pass-price">
                                            <span class="money-currency"><?= '$' ?></span><span data-attribute="adult-price" class="money-value"><?= '19.50' ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="table-item item-pass"><?= 'Kid (x1)' ?></td>
                                        <td class="table-item item-pass-price">
                                            <span class="money-currency"><?= '$' ?></span><span data-attribute="child-price" class="money-value"><?= '7.00' ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="line"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="table-item item-subtotal item-last"><?= 'Subtotal ' ?></td>
                                        <td class="table-item item-price item-subtotal item-last">
                                            <span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '26.50' ?></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- @Ferries-->
                    <div class="card">
                        <div class="card-header">
                            <button type="button" data-toggle="collapse" data-target="#ferries" aria-expanded="true" aria-controls="ferries" class="btn btn-link collapsed"><i class="demo-icon icon-title ferry-icon"></i><?= 'Ferries' ?><div class="icon-toggle"><i class="fas fa-chevron-up"></i></div></button>
                        </div>
                        <div id="ferries" aria-labelledby="ferries" class="booking-body collapse show">
                            <table class="booking-table">
                                <tbody class="summary-item">
                                    <tr>
                                        <td colspan="2" class="table-item item-heading"><?= 'Hillarys to Rottnest Ferry Ticket' ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="table-item item-subheading"><?= 'Sun, 03 Jan 2021' ?><span class="write-depart-time"> <?= '- 7:30am' ?></span></td>
                                    </tr>
                                    <tr class="temp-departure-show" style="display: table-row;">
                                        <td colspan="2" class="table-item item-fare-type item-depart-fare-type">
                                            <span class="quokka-saver"><?= 'Quokka Saver' ?></span>
                                        </td>
                                    </tr>
                                    <tr class="temp-departure-show" style="display: table-row;">
                                        <td class="table-item item-pass"><?= 'Adult (x1)' ?></td>
                                        <td class="table-item item-pass-price">
                                            <span class="money-currency"><?= '$' ?></span><span data-attribute="adult-price" class="money-value"><?= '27.00' ?></span>
                                        </td>
                                    </tr>
                                    <tr class="temp-departure-show" style="display: table-row;">
                                        <td class="table-item item-pass"><?= 'Child (x1)' ?></td>
                                        <td class="table-item item-pass-price"><span class="money-currency"><?= '$' ?></span><span data-attribute="child-price" class="money-value"><?= '18.00' ?></span></td>
                                    </tr>
                                    <tr class="temp-departure-show" style="display: table-row;">
                                        <td colspan="2">
                                            <div class="line"></div>
                                        </td>
                                    </tr>
                                    <tr class="temp-departure-show" style="display: table-row;">
                                        <td class="table-item item-subtotal item-last"><?= 'Subtotal' ?></td>
                                        <td class="table-item item-price item-subtotal item-last"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '45.00' ?></span></td>
                                    </tr>
                                </tbody>
                                <tbody class="summary-item">
                                    <tr>
                                        <td colspan="2" class="table-item item-heading"><?= 'Rottnest to Hillarys Ferry Ticket' ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="table-item item-subheading"><?= 'Tue, 05 Jan 2021' ?><span class="write-return-time"><?= ' - 10:00am' ?></span></td>
                                    </tr>
                                    <tr class="temp-return-show" style="display: table-row;">
                                        <td colspan="2" class="table-item item-fare-type item-return-fare-type">
                                        <span class="everyday-fare"><?= 'Everyday Fare' ?></span></td>
                                    </tr>
                                    <tr class="temp-return-show" style="display: table-row;">
                                        <td class="table-item item-pass"><?= 'Adult (x1)' ?></td>
                                        <td class="table-item item-pass-price"><span class="money-currency"><?= '$' ?></span><span data-attribute="adult-price" class="money-value"><?= '34.50' ?></span>
                                        </td>
                                    </tr>
                                    <tr class="temp-return-show" style="display: table-row;">
                                        <td class="table-item item-pass"><?= 'Child (x1)' ?></td>
                                        <td class="table-item item-pass-price"><span class="money-currency"><?= '$' ?></span><span data-attribute="child-price" class="money-value"><?= '20.50' ?></span></td>
                                    </tr>
                                    <tr class="temp-return-show" style="display: table-row;">
                                        <td colspan="2">
                                            <div class="line"></div>
                                        </td>
                                    </tr>
                                    <tr class="temp-return-show" style="display: table-row;">
                                        <td class="table-item item-subtotal item-last"><?= 'Subtotal' ?></td>
                                        <td class="table-item item-price item-subtotal item-last"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '55.00' ?></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- @Bus Pickup -->
                    <div class="card temp-bus-show" style="display: block;">
                        <div class="card-header"><button type="button" data-toggle="collapse" data-target="#bus" aria-expanded="true" aria-controls="bus" class="btn btn-link"><i class="demo-icon icon-title icon-bus"></i><?= 'Bus pickup' ?><div class="icon-toggle"><i class="fas fa-chevron-down"></i></div></button></div>
                        <div id="bus" aria-labelledby="bus" class="booking-body collapse show">
                            <table class="booking-table">
                                <tbody class="summary-item">
                                    <tr>
                                        <td colspan="2" class="table-item item-subheading print-pickup-point"><?= 'Coolibah Lodge - 6:20am' ?></td>
                                    </tr>
                                    <tr>
                                        <td class="table-item item-pass"><?= 'Adult (x1) '?></td>
                                        <td class="table-item item-pass-price"><span class="money-currency"><?= '$' ?></span><span data-attribute="adult-price" class="money-value"><?= '0.00' ?></span></td>
                                    </tr>
                                    <tr>
                                        <td class="table-item item-pass"><?= 'Child (x1)' ?></td>
                                        <td class="table-item item-pass-price"><span class="money-currency"><?= '$' ?></span><span data-attribute="child-price" class="money-value"><?= '0.00' ?></span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="line"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="table-item item-subtotal item-last"><?= 'Subtotal' ?></td>
                                        <td class="table-item item-price item-subtotal item-last"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- @Luggage-->
                    <div class="card">
                        <div class="card-header"><button type="button" data-toggle="collapse" data-target="#luggage" aria-expanded="true" aria-controls="luggage" class="btn btn-link"><i class="demo-icon icon-title packages-icon"></i><?= 'Luggage' ?><div class="icon-toggle"><i class="fas fa-chevron-down"></i></div></button></div>
                        <div id="luggage" aria-labelledby="luggage" class="booking-body collapse show">
                            <table class="booking-table">
                                <tbody class="summary-item">
                                    <tr class="temp-luggage-show" style="display: table-row;">
                                        <td class="table-item item-pass"><?= 'Standard Luggage (x' ?><span class="print-luggage-quantity"><?= '2' ?></span><?= ')' ?></td>
                                        <td class="table-item item-pass-price print-luggage-price"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '6.00' ?></span></td>
                                    </tr>
                                    <tr class="bike-luggage-show temp-hide" style="display: none;">
                                        <td class="table-item item-pass"><?= 'Bike (x' ?><span class="print-bike-quantity"><?= '0' ?></span><?= ')'  ?></td>
                                        <td class="table-item item-pass-price print-bike-price"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00'  ?></span></td>
                                    </tr>
                                    <tr class="surfboard-luggage-show temp-hide">
                                        <td class="table-item item-pass"><?= 'Surfboard (x' ?><span class="print-surfboard-quantity"></span><?= ')' ?></td>
                                        <td class="table-item item-pass-price print-surfboard-price"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span></td>
                                    </tr>
                                    <tr class="kayaks-luggage-show temp-hide" style="display: table-row;">
                                        <td class="table-item item-pass"><?= 'Kayaks/Skis/SUP (x' ?><span class="print-kayaks-quantity"><?= '1' ?></span><?= ')' ?></td>
                                        <td class="table-item item-pass-price print-kayaks-price"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '25.00' ?></span></td>
                                    </tr>
                                    <tr class="divetank-luggage-show temp-hide">
                                        <td class="table-item item-pass"><?= 'Dive Tank/Cray Pot(x' ?><span class="print-divetank-quantity"></span><?= ')' ?></td>
                                        <td class="table-item item-pass-price print-divetank-price"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00'  ?></span></td>
                                    </tr>
                                    <tr class="tandem-luggage-show temp-hide">
                                        <td class="table-item item-pass"><?= 'Electric/Tandem Bike Freight(x' ?><span class="print-tandem-quantity"></span><?= ')' ?></td>
                                        <td class="table-item item-pass-price print-tandem-price"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span></td>
                                    </tr>
                                    <tr class="no-booking">
                                        <td colspan="2" class="table-item pt-1"><?= 'No Item Selected' ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="line"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="table-item item-subtotal item-last"><?= 'Subtotal' ?></td>
                                        <td class="table-item item-price item-subtotal item-last"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '31.00' ?></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header"><button type="button" data-toggle="collapse" data-target="#extras" aria-expanded="true" aria-controls="extras" class="btn btn-link"><i class="demo-icon icon-title extras-icon"></i><?= 'Extras' ?><div class="icon-toggle"><i class="fas fa-chevron-down"></i></div></button></div>
                        <div id="extras" aria-labelledby="extras" class="booking-body collapse show">
                            <table class="booking-table">
                                <tbody class="summary-item">
                                    <tr class="adultsBike-show" style="display: none;">
                                        <td class="table-item item-pass"><?= 'Adults Bike (x' ?><span class="print-adultsBike-quantity"><?= '0' ?></span><?= ')(x' ?><span class="print-adultsBike-day"><?= '0' ?></span>&nbsp;<?= 'days)' ?></td>
                                        <td class="table-item item-pass-price print-adultsBike-price"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span></td>
                                    </tr>
                                    <tr class="largeChildBike-show" style="display: none;">
                                        <td class="table-item item-pass"><?= 'Child Bike - L (x' ?><span class="print-largeChildBike-quantity"><?= '1' ?></span><?= ') (x' ?><span class="print-largeChildBike-day"><?= '3' ?></span>&nbsp;<?= 'days)' ?></td>
                                        <td class="table-item item-pass-price print-largeChildBike-price"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span></td>
                                    </tr>
                                    <tr class="mediumChildBike-show" style="display: none;">
                                        <td class="table-item item-pass"><?= 'Child Bike - M (x'  ?><span class="print-mediumChildBike-quantity"><?= '0' ?></span><?= ') (x'  ?><span class="print-mediumChildBike-day"><?= '0' ?></span>&nbsp;<?= 'days)' ?></td>
                                        <td class="table-item item-pass-price print-mediumChildBike-price"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span></td>
                                    </tr>
                                    <tr class="snorkelling-show" style="display: none;">
                                        <td class="table-item item-pass"><?= 'Snorkel Set (x' ?><span class="print-snorkelling-quantity"><?= '0' ?></span><?= ') (x' ?><span class="print-snorkelling-day"><?= '0' ?></span>&nbsp;<?= 'days)' ?></td>
                                        <td class="table-item item-pass-price print-snorkelling-price"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span></td>
                                    </tr>
                                    <tr class="busPass-show" style="display: none;">
                                        <td colspan="2" class="table-item item-subsubheading"><?= 'Island Explorer Bus Pass' ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <table class="tour-table">
                                                <tbody>
                                                    <tr class="busPass-adult-show" style="display: none;">
                                                        <td class="table-item item-pass"><?= 'Adult (x' ?><span class="print-busPass-adultQuantity"><?= '0' ?></span><?= ')' ?></td>
                                                        <td class="table-item item-pass-price print-busPass-adultPrice">
                                                            <span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span>
                                                        </td>
                                                    </tr>
                                                    <tr class="busPass-child-show" style="display: none;">
                                                        <td class="table-item item-pass"><?= 'Child (x' ?><span class="print-busPass-childQuantity"><?= '2' ?></span><?= ')' ?></td>
                                                        <td class="table-item item-pass-price print-busPass-childPrice">
                                                            <span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span></td>
                                                    </tr>
                                                    <tr class="busPass-consession-show" style="display: none;">
                                                        <td class="table-item item-pass"><?= 'Concession (x' ?><span class="print-busPass-consessionQuantity"><?= '0' ?></span><?= ')' ?></td>
                                                        <td class="table-item item-pass-price print-busPass-consessionPrice"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr class="historicalTour-show" style="display: none;">
                                        <td colspan="2" class="table-item item-subsubheading"><?= 'Historical Train' ?>&amp;<?= ' Tunnel Tour -' ?>&nbsp;<span class="print-historicalTour-date tour-date"></span><?= ' - ' ?><span class="print-historicalTour-time"></span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <table class="tour-table">
                                                <tbody>
                                                    <tr class="historicalTour-adult-show" style="display: none;">
                                                        <td class="table-item item-pass"><?= 'Adult (x' ?><span class="print-historicalTour-adultQuantity"><?= '0' ?></span><?= ')' ?></td>
                                                        <td class="table-item item-pass-price print-historicalTour-adultPrice"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span></td>
                                                    </tr>
                                                    <tr class="historicalTour-child-show" style="display: none;">
                                                        <td class="table-item item-pass"><?= 'Child (x' ?><span class="print-historicalTour-childQuantity"><?= '0' ?></span><?= ')' ?></td>
                                                        <td class="table-item item-pass-price print-historicalTour-childPrice"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span></td>
                                                    </tr>
                                                    <tr class="historicalTour-consession-show" style="display: none;">
                                                        <td class="table-item item-pass"><?= 'Concession (x' ?><span class="print-historicalTour-consessionQuantity"><?= '0' ?></span><?= ')' ?></td>
                                                        <td class="table-item item-pass-price print-historicalTour-consessionPrice"> <span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr class="baySeekerIslandTour-show" style="display: none;">
                                        <td colspan="2" class="table-item item-subsubheading"><?= 'Bayseeker Island Tour -' ?>&nbsp;<span class="print-baySeekerIslandTour-date tour-date"><?= '04/01/21' ?></span><?= ' - ' ?><span class="print-baySeekerIslandTour-time"><?= '13:45' ?></span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="display: none;">
                                            <table class="tour-table">
                                                <tbody>
                                                    <tr class="baySeekerIslandTour-adult-show" style="display: none;">
                                                        <td class="table-item item-pass"><?= 'Adult (x' ?><span class="print-baySeekerIslandTour-adultQuantity"><?= '1' ?></span><?= ')' ?></td>
                                                        <td class="table-item item-pass-price print-baySeekerIslandTour-adultPrice"> <span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span></td>
                                                    </tr>
                                                    <tr class="baySeekerIslandTour-child-show" style="display: none;">
                                                        <td class="table-item item-pass"><?= 'Child (x' ?><span class="print-baySeekerIslandTour-childQuantity"><?= '0' ?></span><?= ')' ?></td>
                                                        <td class="table-item item-pass-price print-baySeekerIslandTour-childPrice"> <span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span></td>
                                                    </tr>
                                                    <tr class="baySeekerIslandTour-consession-show" style="display: none;">
                                                        <td class="table-item item-pass"><?= 'Concession (x' ?><span class="print-baySeekerIslandTour-consessionQuantity"><?= '0' ?></span><?= ')' ?></td>
                                                        <td class="table-item item-pass-price print-baySeekerIslandTour-consessionPrice"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr class="grandIslandTour-show" style="display: none;">
                                        <td colspan="2" class="table-item item-subsubheading"><?= 'Grand Island Tour -' ?>&nbsp;<span class="print-grandIslandTour-date tour-date"></span>-<span class="print-grandIslandTour-time"></span></td> 
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <table class="tour-table">
                                                <tbody>
                                                    <tr class="grandIslandTour-adult-show" style="display: none;">
                                                        <td class="table-item item-pass"><?= 'Adult (x' ?><span class="print-grandIslandTour-adultQuantity"><?= '0' ?></span><?= ')' ?></td>
                                                        <td class="table-item item-pass-price print-grandIslandTour-adultPrice"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span></td>
                                                    </tr>
                                                    <tr class="grandIslandTour-child-show" style="display: none;">
                                                        <td class="table-item item-pass"><?= 'Child (x' ?><span class="print-grandIslandTour-childQuantity"><?= '0' ?></span><?= ')' ?></td>
                                                        <td class="table-item item-pass-price print-grandIslandTour-childPrice"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span></td>
                                                    </tr>
                                                    <tr class="grandIslandTour-consession-show" style="display: none;">
                                                        <td class="table-item item-pass"><?= 'Concession (x' ?><span class="print-grandIslandTour-consessionQuantity"><?= '0' ?></span><?= ')' ?></td>
                                                        <td class="table-item item-pass-price print-grandIslandTour-consessionPrice"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr class="no-booking active">
                                        <td colspan="2" class="table-item"><?= 'No Item Selected' ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="line"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="table-item item-subtotal item-last"><?= 'Subtotal' ?></td>
                                        <td class="table-item item-price item-subtotal item-last"><span class="money-currency"><?= '$' ?></span><span class="money-value"><?= '0.00' ?></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <button type="button" data-toggle="collapse" data-target="#coupon" aria-expanded="true" aria-controls="coupon" class="btn btn-link collapsed"><i class="icon-title fas fa-tag"></i><?= 'Coupon Discount' ?><div class="icon-toggle"><i class="fas fa-chevron-up"></i></div></button>
                        </div>
                        <div id="coupon" aria-labelledby="ferries" class="booking-body collapse show">
                            <table class="booking-table">
                                <tbody class="summary-coupon active">
                                    <tr>
                                        <td class="table-item item-last item-heading"><?= 'Coupon Code' ?></td>
                                        <td id="couponRemove" class="table-item item-last text-right item-heading"><button id="removeCouponBtn" class="btn btn-primary btn-xs"><?= 'Remove' ?></button></td>
                                    </tr>
                                    <tr class="coupon-input-row" style="display: none;">
                                        <td colspan="2" class="table-item coupon-input-col form-group item-last"><span class="input-group"><input type="text" id="couponInput" placeholder="Coupon Code" aria-label="Coupon Code" aria-describedby="Coupon Code" class="text-uppercase form-control"> <button id="couponBtn" class="btn btn-primary btn-xs"><?= 'Apply' ?></button></span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="coupon-bg">
                                            <table class="coupon-table">
                                                <tbody>
                                                    <tr>
                                                        <td class="table-item coupon-input-col form-group item-last"><span class="coupon-code"><?= 'HAPPYDAY' ?></span></td>
                                                        <td class="table-item item-last item-coupon-btn item-price"><span class="money-currency"><?= '-$' ?></span><span class="money-value"><?= '141.75' ?></span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="grand-total">
                        <table class="booking-table">
                            <tbody>
                                <tr>
                                    <td class="table-item item-total"><?= 'Current Total' ?></td>
                                    <td class="table-item item-total item-price"><span class="money-currency"><?= '$' ?></span> <span class="money-value"><?= '141.75' ?></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- </div> -->
        </section>
    </div>
</main>
<div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal"><?= 'OKAY' ?></button>
            </div>
        </div>
    </div>
</div>