$(function () {
  //Sidebar Navigation (Hamburger)
  $("#navToggle").click(function () {
    $('#navbarNav').css('left', "0");
  });

  $(".closeBtn").click(function () {
    $('#navbarNav').css('left', "-250px");
  });

  //Keep active tap open when page referesh
  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    localStorage.setItem('activeTab', $(e.target).attr('href'));
  });

  var activeTab = localStorage.getItem('activeTab');
  if (activeTab) {
    $('.nav-tabs a[href="' + activeTab + '"]').tab('show');
  }

  //Enable Tooltip everywhere
  $('[data-toggle="tooltip"]').tooltip({
    template: ' <div class="tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
  })

  //Number input, use spinner 
  $('.number-input').spinner({
    min: 0,
    change: function (event, ui ) {
      var val = $(this).spinner( "value" );
      $(this).spinner("value", parseInt(val,10) || 0);
    }
  })

  // Get click event, assign button to var, and get values from that var
  $('#from button').on('click', function () {
    var thisBtn = $(this);

    thisBtn.addClass('active').siblings().removeClass('active');
    var btnText = thisBtn.text();
    var btnValue = thisBtn.val();

    $('#selectedVal').text(btnValue);

    if (btnValue == 'hillarys') {
      $('#inputTo').val('Rottnest Island');
    } else {
      $('#inputTo').val('Hillarys Boat Harbour');
    }
  });

  //Range Date picker
  var today = new Date();
  var dd = String(today.getDate()).padStart(2, '0');
  var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
  var yyyy = today.getFullYear();
  today = dd + '/' + mm + '/' + yyyy;
  $('#departureFerry').daterangepicker({
    locale: {
      format: 'DD/MM/YYYY'
    },
    opens: 'center',
    drops: 'up',
    autoApply: true,
    autoUpdateInput: false,
    singleDatePicker: true,
  }).attr('readonly', 'readonly');

  $('#departureFerry').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('DD/MM/YYYY') );
  });

  $('#returnFerry').daterangepicker({
    locale: {
      format: 'DD/MM/YYYY'
    },
    opens: 'center',
    drops: 'up',
    startDate: today,
    minDate: today,
    autoApply: true,
    autoUpdateInput: false,
    singleDatePicker: true,
  }).attr('readonly', 'readonly');

  $('#returnFerry').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('DD/MM/YYYY') );
  });

  $('.departure-date').daterangepicker({
    locale: {
      format: 'DD/MM/YYYY'
    },
    opens: 'right ',
    startDate: today,
    minDate: today,
    autoApply: true,
    singleDatePicker: true,
  }).attr('readonly', 'readonly');

  $('.dob').daterangepicker({
    locale: {
      format: 'DD/MM/YYYY'
    },
    opens: 'right ',
    startDate: '14/08/1996',
    maxDate: today,
    showDropdowns: true,
    autoApply: true,
    singleDatePicker: true,
  }).attr('readonly', 'readonly');

  $('.equipmentDate').daterangepicker({
    locale: {
      format: 'DD/MM/YYYY'
    },
    opens: 'center',
    drops: 'up',
    startDate: '03/01/2021',
    endDate: '05/01/2021',
    minDate: today,
    autoApply: true,
  }).attr('readonly', 'readonly');

  $('.equipmentDate').daterangepicker({
    locale: {
      format: 'DD/MM/YYYY'
    },
    opens: 'center',
    drops: 'up',
    startDate: '03/01/2021',
    endDate: '05/01/2021',
    minDate: today,
    autoApply: true,
  }).attr('readonly', 'readonly');
  
  $('.bikeFerryDate').daterangepicker({
    locale: {
      format: 'DD/MM/YYYY'
    },
    opens: 'center',
    drops: 'up',
    startDate: '03/01/2021',
    endDate: '03/01/2021',
    minDate: today,
    autoApply: true,
  }).attr('readonly', 'readonly');

  // Remove Toddler, Infant, Concession from Bike and Ferry
  $('#tourPackage').on('change', function(){
    if($(this).val()=='bike-ferry-combo'){
      $('#toddlerTour').spinner( "disable").spinner("value",0);
      $('#infantTour').spinner( "disable" ).spinner("value",0);
      $('#consessionTour').spinner( "disable" ).spinner("value",0);
      $('#searchTour').attr('href','bike-ferry-combo/select-ferries.html')
    }else if($(this).val()=='bayseeker-island-package'){
      $('#searchTour').attr('href','bayseeker-package/select-ferries.html')
    }else if($(this).val()=='grand-island-package'){
      $('#searchTour').attr('href','grand-island-package/select-ferries.html')
    }else if($(this).val()=='bus-ferry-combo'){
      $('#toddlerTour').spinner( "disable").spinner("value",0);
      $('#infantTour').spinner( "disable" ).spinner("value",0);
      $('#searchTour').attr('href','bus-ferry-combo/select-ferries.html');
    }else if($(this).val()=='historical-tour-train'){
      $('#searchTour').attr('href','historical-train-package/select-ferries.html')
    }else if($(this).val()=='rottnest-seafood'){
      $('#searchTour').attr('href','seafood-package/select-ferries.html')
    }else{
      $('#toddlerTour').spinner( "enable" );
      $('#infantTour').spinner( "enable" );
      $('#consessionTour').spinner( "enable" );
    }
  });

  //accordion on summary
  $('.card .btn-link').on('click',function(){
    $(this).find('.icon-toggle').toggleClass('toggled');
  });

  //button color changes on Hover
  $('.btn-select').hover(function () {
    $(this).closest('.fare-type').toggleClass('hover');
  });

  //fuction to make text into url ready link
  function stringToLink(string) {
    var result;
    result = string.trim();
    result = result.replace(/\s+/g, '-').toLowerCase();
    return result;
  }

  //alert modal function
  function alertModal(header, body, happy) {
    if (happy != 1) {
      $('#alertModal .modal-title').addClass('error-modal-title');
    }
    $('#alertModal .modal-title').empty().append(header);
    $('#alertModal .modal-body').empty().append(body);
    $('#alertModal').modal('show');
  }


  //Alert box, have you selected your bus?


    $('#gotToLuggage').on('click',function(){
      if($('.temp-bus-show').is(':hidden')){
        $('#busReminder').modal('show');
      }else{
        // Changes By Himani Start
        window.location.href = "../processbooking/selectExtras";
        // End

      }
    });

    $('#openBusModal').on('click',function(){
      $('#busReminder').modal('hide');
      $('#busModal').modal('show');
    });

    $('#continueToLuggage').on('click',function(){
      $('#busReminder').modal('hide');
      // Changes By Himani Start
      window.location.href = "../processbooking/selectExtras";
      // End
    });

  //count How many Days
  function calcDays() {
    var startDate = $('#snorkellingDate').data('daterangepicker').startDate._d;
    var endDate = $('#snorkellingDate').data('daterangepicker').endDate._d;
    var dayLength = 0;
    if (startDate && endDate) {
      dayLength = Math.floor(((endDate.getTime() - startDate.getTime()) +1) / 86400000) ;; 
    }
    return dayLength;

  } 

  function updateSubTotal() {
    $('.booking-table .summary-item').each(function () {
      var subtotal =[];
      var a=0;
      console.log(this);

      $(this).find('.item-pass-price .money-value').each(function(index){
        a += parseFloat($(this).text());
        console.log(a);

        subtotal.push(a);        
      }); 
      for(var i=0; i< subtotal.length; i++){
        subtotal[i]=subtotal[i].toFixed(2);
        $(this).find('.item-subtotal .money-value').empty().append(subtotal[i]);
      }

    });

  }

  function countDiscount() {
    //count discount : 10%, get current total
    //current total = total before discount
    var sum = 0;
    $('.booking-table .item-subtotal .money-value').each(function () {
      sum += parseFloat($(this).text());
      return sum;
    });
    sum = sum.toFixed(2);

    var discount = ((10 / 100 * sum).toFixed(2));

    $('.coupon-table .item-price .money-value').empty().append(discount);
    return discount;
  }

  //updateTotal for Booking Summary function
  function updateTotal() {
    var sum = 0;
    $('.booking-table .item-subtotal .money-value').each(function () {
      sum += parseFloat($(this).text());
      return sum;
    });
    sum = sum.toFixed(2);

    //discount, check if there any active coupon applied
    if ($('.summary-coupon').hasClass('active')) {
      discount = parseFloat($('.summary-coupon .item-price .money-value').text());
      sum = (sum - discount).toFixed(2);
    }
    
    $('.item-total .money-value').empty().append(sum);
    $('.cta-current-total .money-value').empty().append(sum);
  }
  
  function countSurcharge(){
    var surcharge = (1.5/100 * ((Number($('.item-total .money-value').text()))) ).toFixed(2);
    var total= (Number($('.item-total .money-value').text()) + Number(surcharge)).toFixed(2);
    $('.final-price .money-value').empty().append(total);
    $('.item-final.item-price .money-value').empty().append(total);
    $('.surcharge .money-value').empty().append(surcharge);
  }

  $('.ferry-from .btn-select').on('click', function (slugDepartFareType) {
    //Get the departure time into the summary 
    var departTime =$(this).closest('.time-options').find('.depart-time').text();

    //show and hide for buspickup
    departTime = stringToLink(departTime);
    var prevDepartTime=(stringToLink($('.write-depart-time').text())).split('-').join('');;

    if(prevDepartTime !='' ){
      if(prevDepartTime != departTime && $('.temp-bus-show').is(':visible')) {
          
        $('#alertModal .modal-title').addClass('error-modal-title');
        $('#alertModal .modal-title').empty().append('Please re-select your bus pickup');
        $('#alertModal .modal-body').empty().append('Your ferry departure time has changed, please re-select your bus pickup.');
        $('#alertModal').modal('show');
        window.app.clearBusSelection();

        $('#busModalTrigger').text('Add bus pickup');
        $('#removeBusPickup').hide();
        $('.temp-bus-show').hide();

        }
    }

    $('.write-depart-time').empty().append(' - ' + departTime);


    if (departTime == '10:00am') {
      $('#morningPickups').hide();
      $('#lateMorningPickups').show();
    } else {
      $('#lateMorningPickups').hide();
      $('#morningPickups').show();
    }
    $('#busPopupTrigger').show();

    //show the departure ferry price after button is clicked
    $('.temp-departure-show').show();

    //Get the fare type
    var departFareType = $(this).closest('.fare-type').find('.fare-title').text();
    slugDepartFareType = stringToLink(departFareType);
    $('.item-depart-fare-type').empty().append('<span class="' + slugDepartFareType + '">' + departFareType + '</span>');

    //Deselect all buttons in the Departing Ferry or in the Return Ferry
    $('.ferry-from .btn-select').removeClass('selected');
    $('.ferry-from .fare-type').removeClass('active');
    $('.ferry-from .btn-select').text('select');
    $('.ferry-from .shade').removeClass('active');
    $('.ferry-from .time-options').removeClass('active');

    //Add active satage on the btn-select
    $(this).addClass('selected');
    $(this).text('selected');

    //add active stage on pricing details shade 
    var changeShade = ".shade." + slugDepartFareType;
    $(this).closest('.time-options').find(changeShade).addClass('active');
    $(this).closest('.time-options').addClass('active');

    //updatePrice:get adult price into the summary box;
    var adultDepartPrice = ($('.ferry-from .shade.active [data-attribute="adult-price"]').text());
    $('.temp-departure-show [data-attribute="adult-price"]').empty().append(adultDepartPrice);

    //updatePrice:get adult price into the summary box;
    var childDepartPrice = ($('.ferry-from .shade.active [data-attribute="child-price"]').text());
    $('.temp-departure-show [data-attribute="child-price"]').empty().append(childDepartPrice);

    //add active stage on .fare
    $(this).closest('.fare-type').addClass('active');

    updateSubTotal();
    countDiscount();
    updateTotal();

    //check if user has selected the departing ferry.... yes-> activate the button
    if ($('.ferry-to .fare-type').hasClass('active')) {
      $('.cta-btn').removeClass('disabled');
    }

  });


  $('.ferry-to .btn-select').on('click', function () {
    //Get the departure time into the summary 
    var returnTime = $(this).closest('.time-options').find('.return-time').text();
    $('.write-return-time').empty().append(' - ' + returnTime);

    //show the departure ferry price after button is clicked
    $('.temp-return-show').show();

    //Get the fare type
    var returnFareType = $(this).closest('.fare-type').find('.fare-title').text();
    slugReturnFareType = stringToLink(returnFareType);
    $('.item-return-fare-type').empty().append('<span class="' + slugReturnFareType + '">' + returnFareType + '</span>');

    //Deselect all buttons in the Departing Ferry or in the Return Ferry
    $('.ferry-to .btn-select').removeClass('selected');
    $('.ferry-to .fare-type').removeClass('active');
    $('.ferry-to .btn-select').text('select');
    $('.ferry-to .shade').removeClass('active');
    $('.ferry-to .time-options').removeClass('active');

    //Add active satage on the btn-select
    $(this).addClass('selected');
    $(this).text('selected');

    //add active stage on pricing details shade 
    var changeShade = ".shade." + slugReturnFareType;
    $(this).closest('.time-options').find(changeShade).addClass('active');
    $(this).closest('.time-options').addClass('active');

    //add active stage on .fare
    $(this).closest('.fare-type').addClass('active');

    //updatePrice:get adult price into the summary box;
    var adultReturnPrice = ($('.ferry-to .shade.active [data-attribute="adult-price"]').text());
    $('.temp-return-show [data-attribute="adult-price"]').empty().append(adultReturnPrice);

    //updatePrice:get adult price into the summary box;
    var childReturnPrice = ($('.ferry-to .shade.active [data-attribute="child-price"]').text());
    $('.temp-return-show [data-attribute="child-price"]').empty().append(childReturnPrice);

    //check if user has selected the departing ferry.... yes-> activate the button
    if ($('.ferry-from .fare-type').hasClass('active')) {
      $('.cta-btn').removeClass('disabled');
    }
    updateSubTotal();
    countDiscount();
    updateTotal();

  });

  $('.toggle-price').on('click', function () {
    $('.fa-angle-down', this).toggleClass('toggled');
  });

  $('.btn-toggle').on('click', function () {
    $('.fa-angle-down', this).toggleClass('toggled');
  });

  // Coupon Discount
  // * If Coupon == 'HAPPYDAY' -> Coupon valid
  $('#couponBtn').on('click', function () {
    var couponCode = $('#couponInput').val().toUpperCase();
    if (couponCode == 'HAPPYDAY') {
      alertModal('Coupon valid', 'Congratulations, your coupon ' + couponCode + ' is valid. 10% discount has been applied to your overall booking.', 1);
      $('.coupon-input-row').hide();
      $('.summary-coupon').addClass('active');
      $('.summary-coupon').append(
        '<tr><td colspan="2" class="coupon-bg"><table class="coupon-table"><tbody><tr><td class="table-item coupon-input-col form-group item-last"><span class="coupon-code">' + couponCode + '</span></td><td class="table-item item-last item-coupon-btn item-price"> <span class="money-currency">-$</span><span class="money-value">0.00</span></td></tr></tbody></table></td></tr>'
      );
      countDiscount();
      //show removeBtn
      $('#removeCouponBtn').show();
      updateTotal();
    } else {
      alertModal('Coupon invalid', 'Sorry, your coupon ' + couponCode + ' is invalid. Please make sure that your coupon code is a valid code and try again.', 0);
      $('#couponInput').val('');
    }

    countSurcharge();
  });

  $('#removeCouponBtn').on('click', function () {
    $('.coupon-input-row').show();
    $('.summary-coupon tr:last-child').remove();
    $('#removeCouponBtn').hide();
    $('#couponInput').val('');
    $('.summary-coupon').removeClass('active');
    updateTotal();

    countSurcharge();
  });
  
  //Enable #saveLuggage, check condition
  $('#luggageQuantity').on( "spinstop", function( event, ui ){
    var a=window.app.clickable();
    if(a==false && $('#luggageTerms').prop("checked") == true){
      $('#saveLuggage').attr('disabled',false);
    }else if(a==true || $('#luggageTerms').prop("checked") == false){
      $('#saveLuggage').attr('disabled',true);
    }   
  });

  $('#luggageTerms').on('change',function(){
    var a=window.app.clickable();
    if(a==false && $(this).prop("checked") == true){
      $('#saveLuggage').attr('disabled',false);
    }else if(a==true || $(this).prop("checked") == false){
      $('#saveLuggage').attr('disabled',true);
    }   
  });
   
  $('#saveLuggage').on('click',function(){
    $('#addLuggage').modal('hide');
    $('.no-booking').removeClass('active');
    var luggageQuanity = $('#luggageQuantity').val();
    var luggagePrice = (luggageQuanity * 3).toFixed(2);
    $('.print-luggage-quantity').text(luggageQuanity);
    $('#addLuggageTrigger').text('Edit Luggage & Accommodation');
    $('.print-luggage-price .money-value').text(luggagePrice);
    $('.temp-luggage-show').show();
    $('#removeLuggage').show();
    $('#saveLuggage').hide();
    $('#updateLuggage').show();
    updateSubTotal();
    countDiscount();
    updateTotal();
  });

  $('#updateLuggage').on('click',function(){
    $('#addLuggage').modal('hide');
    $('.no-booking').removeClass('active');
    var luggageQuanity = $('#luggageQuantity').val();
    var luggagePrice = (luggageQuanity * 3).toFixed(2);
    $('.print-luggage-quantity').text(luggageQuanity);
    $('#addLuggageTrigger').text('Edit Luggage & Accommodation');
    $('.print-luggage-price .money-value').text(luggagePrice);
    $('.temp-luggage-show').show();
    $('#removeLuggage').show();
    $('#saveLuggage').hide();
    $('#updateLuggage').show();
    updateSubTotal();
    countDiscount();
    updateTotal();

    if($('#luggageQuantity').val()=='0'){
      
      var x =$('.print-luggage-price').closest('.summary-item').find('.item-subtotal .money-value').text();
      if(x=='0.00'){
        $('.no-booking').addClass('active');
      }
      $('.temp-luggage-show').hide();
      $('#removeLuggage').hide();
      $('#saveLuggage').show();

      $('#addLuggageTrigger').text('Add Luggage');
      $(this).hide();
    }
  });
  

  $('#removeLuggage').on('click',function(){
    $('.print-luggage-price .money-value').text('0.00');
    $('#luggageQuantity').val('0');
    updateSubTotal();
    countDiscount();
    updateTotal();
    $('.temp-luggage-show').hide();
    $('#removeLuggage').hide();
    $('#addLuggageTrigger').text('Add Luggage');

    var x =$('.print-luggage-price').closest('.summary-item').find('.item-subtotal .money-value').text();
    if(x=='0.00'){
      $('.no-booking').addClass('active');
    }

  });

  //Get luggage frieght and count price
  
  $('.freight .number-input').each(function(){
    $(this).on( "spinstop", function( event, ui ){ 
      var value= $(this).val();
      var whereToPrint=('.print-'+$(this).attr("name")+'-quantity');
      var whereToPrintPrice=('.print-'+$(this).attr("name")+'-price .money-value');
      var whatToShow = ('.'+$(this).attr("name")+'-luggage-show');
      var price= parseFloat($(this).closest('.card-body-item').find('.money-value').text());
      var priceItem= (value*price).toFixed(2);

      $(whereToPrint).text(value);
      $(whatToShow).show();

      //price
      $(whereToPrintPrice).text(priceItem);
      
      //var itemPrice=value*

      if(value !==0){
        $('.no-booking').removeClass('active');
      }     
      //if quanity 0 -> hide summary row
      if(value =='0'){
        $(whatToShow).hide();
       }

      updateSubTotal();
      countDiscount();
      updateTotal();  

      var x =$(whereToPrint).closest('.summary-item').find('.item-subtotal .money-value').text();
      if(x=='0.00'){
        $('.no-booking').addClass('active');
      }

      });
  });

  $('#addAdultsBike').on('click',function(){
    var quantity=$('#adultsBikeQuantity').val();
    var dayLength =calcDays();
    var price=0;

    if(dayLength==1){
      price = 30
    } else if(dayLength==2){
      price = 45
    }else if (dayLength>2){
      var additionalDay= dayLength-2;
      price = 45 + (additionalDay*10)
    }
    price=(quantity*price).toFixed(2); 

    //Update Summary Box
    if(quantity==0){
      $('.adultsBike-show').hide();
      $('.print-adultsBike-quantity').text(0);
      $('.print-adultsBike-price .money-value').text('0.00');

      updateSubTotal();
      countDiscount();
      updateTotal();

      //Remove tick and green border to card
      $(this).closest('.card').removeClass('active');

      $(this).closest('.card-body').find('.remove-extra').hide();
      $(this).closest('.card-body').find('.add-equipment').text('Add');
      $(this).closest('.card-body').find('.btn-toggle').html('Select <i class="fas fa-angle-down"></i>');
      $(this).closest('.extra-extension').collapse('hide');

    }else{
      $('.print-adultsBike-quantity').text(quantity);
      $('.print-adultsBike-day').text(dayLength);
      $('.print-adultsBike-price .money-value').text(price);
      $('.adultsBike-show').show();
      updateSubTotal();
      countDiscount();
      updateTotal();

      //Show Remove , collapse accordion, change add to edit
      $(this).closest('.card-body').find('.remove-extra').show();
      $(this).closest('.extra-extension').collapse('hide');
      $(this).closest('.card-body').find('.btn-toggle').html('Edit <i class="fas fa-angle-down"></i>');
      $(this).text('UPDATE');

      //Add tick and green border to card
      $(this).closest('.card').addClass('active');
    }
    
    var itemSubtotal =$('.print-adultsBike-quantity').closest('.summary-item').find('.item-subtotal .money-value').text();
    var noBooking =$('.print-adultsBike-quantity').closest('.summary-item').find('.no-booking');

    if(itemSubtotal=='0.00'){
      $(noBooking).addClass('active');
    }else{
      $(noBooking).removeClass('active');
    }

  });

  $('#removeAdultsBike').on('click',function(){
    $('#adultsBikeQuantity').val(0);
    $('.print-adultsBike-quantity').text(0);
    $('.print-adultsBike-price .money-value').text('0.00');

    $('.adultsBike-show').hide();
    updateSubTotal();
    countDiscount();
    updateTotal();
    var itemSubtotal =$('.print-adultsBike-quantity').closest('.summary-item').find('.item-subtotal .money-value').text();
    var noBooking =$('.print-adultsBike-quantity').closest('.summary-item').find('.no-booking');
    if(itemSubtotal=='0.00'){
      $(noBooking).addClass('active');
    }else{
      $(noBooking).removeClass('active');
    }  
    
    $(this).hide();
    $(this).closest('.card-body').find('.add-equipment').text('Add');
    $(this).closest('.card-body').find('.btn-toggle').html('Select <i class="fas fa-angle-down"></i>');
    $(this).closest('.extra-extension').collapse('hide');

    //Remove tick and green border to card
    $(this).closest('.card').removeClass('active');

  });

  $('#addChildBike').on('click',function(){
    var medQuantity=Number($('#mediumBikeQuantity').val());
    var larBikeQuantity=Number($('#largeChildBikeQuantity').val());
    var dayLength =calcDays();
    var medPrice=0;
    var larPrice=0;
    var price=0;

    if(dayLength==1){
      price = 16
    } else if(dayLength==2){
      price = 23
    }else if (dayLength>2){
      var additionalDay= dayLength-2;
      price = 23 + (additionalDay*5)
    }
    medPrice=(medQuantity*price).toFixed(2); 
    larPrice=(larBikeQuantity*price).toFixed(2); 
    //console.log('medQuantity='+medQuantity); console.log('larBikeQuantity='+larBikeQuantity);
    
    if( (medQuantity==0) && (larBikeQuantity==0) ){

      $('.print-mediumChildBike-quantity').text('0');
      $('.print-mediumChildBike-day').text('0');
      $('.print-mediumChildBike-price .money-value').text('0.00');

      $('.print-largeChildBike-quantity').text('0');
      $('.print-largeChildBike-day').text('0');
      $('.print-largeChildBike-price .money-value').text('0.00');

      $(this).closest('.extra-extension').collapse('hide');
      $(this).closest('.card-body').find('.remove-extra').hide();
      $(this).text('Add');
      $(this).closest('.card-body').find('.btn-toggle').html('Add <i class="fas fa-angle-down"></i>');
      $('.mediumChildBike-show').hide();
      $('.largeChildBike-show').hide();

      $(this).closest('.card').removeClass('active');

      updateSubTotal();
      countDiscount();
      updateTotal();


    }else{

      if(medQuantity != 0){
        $('.print-mediumChildBike-quantity').text(medQuantity);
        $('.print-mediumChildBike-day').text(dayLength);
        $('.print-mediumChildBike-price .money-value').text(medPrice);
        $('.mediumChildBike-show').show();
      }else{
        $('.print-mediumChildBike-quantity').text(medQuantity);
        $('.print-mediumChildBike-day').text(dayLength);
        $('.print-mediumChildBike-price .money-value').text(medPrice);
        $('.mediumChildBike-show').hide();
      }

      if(larBikeQuantity != 0){
        $('.print-largeChildBike-quantity').text(larBikeQuantity);
        $('.print-largeChildBike-day').text(dayLength);
        $('.print-largeChildBike-price .money-value').text(larPrice);
        $('.largeChildBike-show').show();
      }else{
        $('.print-largeChildBike-quantity').text(larBikeQuantity);
        $('.print-largeChildBike-day').text(dayLength);
        $('.print-largeChildBike-price .money-value').text(larPrice);
        $('.largeChildBike-show').hide();
      }

      updateSubTotal();
      countDiscount();
      updateTotal();

      $(this).closest('.extra-extension').collapse('hide');
      $(this).closest('.card-body').find('.remove-extra').show();
      $(this).closest('.card-body').find('.btn-toggle').html('Edit <i class="fas fa-angle-down"></i>');
      $(this).text('UPDATE');

      $(this).closest('.card').addClass('active');

    }


    var itemSubtotal =$('.print-adultsBike-quantity').closest('.summary-item').find('.item-subtotal .money-value').text();
    var noBooking =$('.print-adultsBike-quantity').closest('.summary-item').find('.no-booking');

    if(itemSubtotal=='0.00'){
      $(noBooking).addClass('active');
    }else{
      $(noBooking).removeClass('active');
    }
    
  });

  $('#removeChildBike').on('click',function(){
    $('#adultsBikeQuantity').val(0);
    $('.print-mediumChildBike-quantity').text('0');
    $('.print-mediumChildBike-day').text('0');
    $('.print-mediumChildBike-price .money-value').text('0.00');

    $('.print-largeChildBike-quantity').text('0');
    $('.print-largeChildBike-day').text('0');
    $('.print-largeChildBike-price .money-value').text('0.00');

    $(this).closest('.extra-extension').collapse('hide');
    $(this).closest('.card-body').find('.remove-extra').hide();
    $(this).text('Add');
    $(this).closest('.card-body').find('.btn-toggle').html('Add <i class="fas fa-angle-down"></i>');
    $('.mediumChildBike-show').hide();
    $('.largeChildBike-show').hide();

    $(this).closest('.card').removeClass('active');

    updateSubTotal();
    countDiscount();
    updateTotal();

    var itemSubtotal =$('.print-adultsBike-quantity').closest('.summary-item').find('.item-subtotal .money-value').text();
    var noBooking =$('.print-adultsBike-quantity').closest('.summary-item').find('.no-booking');

    if(itemSubtotal=='0.00'){
      $(noBooking).addClass('active');
    }else{
      $(noBooking).removeClass('active');
    }

  });


  //Add Snorkelling
  $('#snorkelling .add-equipment').on('click',function(){
    var dayLength =calcDays();
    var quantity = $('#snorkellingQuantity').val();

    var price=0;
    if(dayLength==1){
      price = 15
    } else if(dayLength==2){
      price = 22
    }else if (dayLength>2){
      var additionalDay= dayLength-2;
      price = 22 + (additionalDay*7)
    }
    price=(quantity*price).toFixed(2); 
    $('.snorkelling-show').show();

    $('.print-snorkelling-quantity').empty().text(quantity);
    $('.print-snorkelling-day').empty().text(dayLength);
    $('.print-snorkelling-price .money-value').empty().text(price);
    updateSubTotal();
    countDiscount();
    updateTotal();
  
    var itemSubtotal =$('.print-snorkelling-day').closest('.summary-item').find('.item-subtotal .money-value').text();
    var noBooking =$('.print-snorkelling-day').closest('.summary-item').find('.no-booking');
    if(itemSubtotal=='0.00'){
      $(noBooking).addClass('active');
    }else{
      $(noBooking).removeClass('active');
    }

    //Remove from quanity reduce
    if(quantity==0){
      $('.snorkelling-show').hide();
      $('.print-snorkelling-price .money-value').empty().text('0.00');
      $('#removeSnorkelling').hide();
      $(this).closest('.card-body').find('.add-equipment').text('Add');
      $(this).closest('.card-body').find('.btn-toggle').html('Select <i class="fas fa-angle-down"></i>');
      $(this).closest('.card').removeClass('active');

    } 

    //Change Button from Select to Edit when quantity added
    $(this).closest('.card-body').find('.btn-toggle').html('Edit <i class="fas fa-angle-down"></i>')

    //Change Button add to update
    $(this).text('UPDATE');

    //After added, add tick on the card ; close accordion
    $(this).closest('.card').addClass('active');
    $('#snorkelling').collapse('hide');
    $(this).closest('.card').find('.remove-extra').show();

  });

  //Remove Snorkelling
  $('#removeSnorkelling').on('click',function(){
    $('#snorkellingQuantity').val(0);
    $('.print-snorkelling-quantity').empty().text(0);
    $('.print-snorkelling-price .money-value').empty().text('0.00');
    
    $('.snorkelling-show').hide();
    $(this).hide();
    $(this).closest('.card-body').find('.btn-toggle').html('Select <i class="fas fa-angle-down"></i>');
    $(this).closest('.card-body').find('.add-equipment').text('Add');

    updateSubTotal();
    countDiscount();
    updateTotal();

    var itemSubtotal =$('.busPass-adult-show').closest('.summary-item').find('.item-subtotal .money-value').text();
    var noBooking =$('.busPass-adult-show').closest('.summary-item').find('.no-booking');

    if(itemSubtotal=='0.00'){
      $(noBooking).addClass('active');
    }else{
      $(noBooking).removeClass('active');
    }

     // Remove tick on the card ; close accordion
     $(this).closest('.card').removeClass('active');
     $('#snorkelling').collapse('hide');

  });



  //Add Bus
  $('#busPass .add-equipment').on('click',function(){
    var adultQuantity = $('#adultBusQuantity').val();
    var childQuantity = $('#childBusQuantity').val();
    var consessionQuantity = $('#consessionBusQuantity').val();
    
    var adultPrice = Number($(this).closest('.card-body').find('.adult-price .money-value').text());
    var childPrice = Number($(this).closest('.card-body').find('.child-price .money-value').text());
    var consessionPrice = Number($(this).closest('.card-body').find('.consession-price .money-value').text());
    
    adultPrice= (adultQuantity*adultPrice).toFixed(2);
    childPrice= (childQuantity*childPrice).toFixed(2);
    consessionPrice= (consessionQuantity*consessionPrice).toFixed(2);
    $('.busPass-show').show();
    if(adultQuantity==0){
      $('.busPass-adult-show').hide();
    }else{
      $('.busPass-adult-show').show();
      $('.print-busPass-adultQuantity').text(adultQuantity);
      $('.print-busPass-adultPrice .money-value').text(adultPrice);
    }

    if(childQuantity==0){
      $('.busPass-child-show').hide();
    }else{
      $('.busPass-child-show').show();
      $('.print-busPass-childQuantity').text(childQuantity);
      $('.print-busPass-childPrice .money-value').text(childPrice);
    }

    if(consessionQuantity==0){
      $('.busPass-consession-show').hide();
    }else{
      $('.busPass-consession-show').show();
      $('.print-busPass-consessionQuantity').text(consessionQuantity);
      $('.print-busPass-consessionPrice .money-value').text(consessionPrice);
    }

    updateSubTotal();
    countDiscount();
    updateTotal();
  
    var itemSubtotal =$('.print-busPass-adultQuantity').closest('.summary-item').find('.item-subtotal .money-value').text();
    var noBooking =$('.print-busPass-adultQuantity').closest('.summary-item').find('.no-booking');
    if(itemSubtotal=='0.00'){
      $(noBooking).addClass('active');
    }else{
      $(noBooking).removeClass('active');
    }

    //Change Button from Select to Edit when quantity added
    $(this).closest('.card-body').find('.btn-toggle').html('Edit <i class="fas fa-angle-down"></i>')

    //Change Button add to update
    $(this).text('UPDATE');

    //After added, add tick on the card ; close accordion
    $(this).closest('.card').addClass('active');
    $(this).closest('.extra-extension').collapse('hide');
    $(this).closest('.card').find('.remove-extra').show();

  });

  //Remove Bus
  $('#busPass .remove-extra').on('click',function(){
   
    $('#adultBusQuantity').val(0)
    $('#childBusQuantity').val(0);
    $('#consessionBusQuantity').val(0);

    $('.busPass-show').hide();
    $('.busPass-adult-show').hide();
    $('.busPass-child-show').hide();
    $('.busPass-consession-show').hide();


    $('.print-busPass-adultPrice .money-value').text('0.00');
    $('.print-busPass-childPrice .money-value').text('0.00');
    $('.print-busPass-consessionPrice .money-value').text('0.00');

    updateSubTotal();
    countDiscount();
    updateTotal();

    var itemSubtotal =$('.busPass-adult-show').closest('.summary-item').find('.item-subtotal .money-value').text();
    var noBooking =$('.busPass-adult-show').closest('.summary-item').find('.no-booking');

    if(itemSubtotal=='0.00'){
      $(noBooking).addClass('active');
    }else{
      $(noBooking).removeClass('active');
    }

    //Hide remove-extra button
    $(this).hide();
    
    $(this).closest('.card-body').find('.add-equipment').text('Add');
    $(this).closest('.card-body').find('.btn-toggle').html('Select <i class="fas fa-angle-down"></i>');
    $(this).closest('.extra-extension').collapse('hide');
    
    //Remove tick and green border to card
    $(this).closest('.card').removeClass('active');
  });

  //dateinTour
  $('.singleDatePicker').daterangepicker({
    locale: {
      format: 'DD/MM/YYYY'
    },
    opens: 'right ',
    startDate: '03/01/2021',
    minDate: '03/01/2021',
    maxDate: '05/01/2021',
    autoApply: true,
    singleDatePicker: true,
  }).attr('readonly', 'readonly');


  //Add Tour
  $('#tours .add-equipment').each(function(){
    $(this).on('click',function(){  
      var tourCategory = $(this).attr('data-attribute');
      var date = $(this).closest('.card-body').find('.singleDatePicker').data('daterangepicker').startDate.format("DD/MM/YY");
      var time = $(this).closest('.card-body').find('.tourTime').val();

      var adultQuantity = $(this).closest('.card-body').find('.adult-quantity').val();
      var childQuantity = $(this).closest('.card-body').find('.child-quantity').val();
      var consessionQuantity = $(this).closest('.card-body').find('.consession-quantity').val();
      
      var adultPrice = Number($(this).closest('.card-body').find('.adult-price .money-value').text());
      var childPrice = Number($(this).closest('.card-body').find('.child-price .money-value').text());
      var consessionPrice = Number($(this).closest('.card-body').find('.consession-price .money-value').text());

      adultPrice= (adultQuantity*adultPrice).toFixed(2);
      childPrice= (childQuantity*childPrice).toFixed(2);
      consessionPrice= (consessionQuantity*consessionPrice).toFixed(2);

      $('.print-'+tourCategory+'-date').text(date);
      $('.print-'+tourCategory+'-time').text(time);

      $('.'+tourCategory+'-show').show();

      if(adultQuantity==0 && childQuantity==0 && consessionQuantity==0){
        $(this).closest('.card-body').find('.remove-extra').hide();
        $(this).closest('.extra-extension').collapse('hide');
        $(this).closest('.card-body').find('.btn-toggle').html('Select <i class="fas fa-angle-down"></i>');
        $(this).text('Add');
        $(this).closest('.card').removeClass('active');

        $('.print-'+tourCategory+'-adultPrice .money-value').text('0.00');
        $('.print-'+tourCategory+'-childPrice .money-value').text('0.00');
        $('.print-'+tourCategory+'-consessionPrice .money-value').text('0.00');

        $('.'+tourCategory+'-show').hide();
        $('.'+tourCategory+'-adult-show').hide();
        $('.'+tourCategory+'-child-show').hide();
        $('.'+tourCategory+'-consession-show').hide();
  

      }else{
        if(adultQuantity==0){
          $('.'+tourCategory+'-adult-show').hide();
        }else{
          $('.'+tourCategory+'-adult-show').show();
          $('.print-'+tourCategory+'-adultQuantity').text(adultQuantity);
          $('.print-'+tourCategory+'-adultPrice .money-value').text(adultPrice);
        }
  
        if(childQuantity==0){
          $('.'+tourCategory+'-child-show').hide();
        }else{
          $('.'+tourCategory+'-child-show').show();
          $('.print-'+tourCategory+'-childQuantity').text(childQuantity);
          $('.print-'+tourCategory+'-childPrice .money-value').text(childPrice);
        }
  
        if(consessionQuantity==0){
          $('.'+tourCategory+'-consession-show').hide();
        }else{
          $('.'+tourCategory+'-consession-show').show();
          $('.print-'+tourCategory+'-consessionQuantity').text(consessionQuantity);
          $('.print-'+tourCategory+'-consessionPrice .money-value').text(consessionPrice);
        }
  
        //Change Button State in the card
        //Show Remove Button , collapse accordion, change add to edit
        $(this).closest('.card-body').find('.remove-extra').show();
        $(this).closest('.extra-extension').collapse('hide');
        $(this).closest('.card-body').find('.btn-toggle').html('Edit <i class="fas fa-angle-down"></i>');
        $(this).text('UPDATE');

        //Add tick and green border to card
        $(this).closest('.card').addClass('active');
      }

      updateSubTotal();
      countDiscount();
      updateTotal();


      var itemSubtotal =$('.print-'+tourCategory+'-childQuantity').closest('.summary-item').find('.item-subtotal .money-value').text();
      var noBooking =$('.print-'+tourCategory+'-childQuantity').closest('.summary-item').find('.no-booking');

      if(itemSubtotal=='0.00'){
        $(noBooking).addClass('active');
      }else{
        $(noBooking).removeClass('active');
      }
      
    });
  });

  //Remove Tour
  $('#tours .remove-extra').each(function(){
    $(this).on('click',function(){ 
      var tourCategory = $(this).attr('data-attribute');

      $(this).closest('.card-body').find('.adult-quantity').val(0);
      $(this).closest('.card-body').find('.child-quantity').val(0);
      $(this).closest('.card-body').find('.consession-quantity').val(0);

      $('.'+tourCategory+'-show').hide();
      $('.'+tourCategory+'-adult-show').hide();
      $('.'+tourCategory+'-child-show').hide();
      $('.'+tourCategory+'-consession-show').hide();


      $('.print-'+tourCategory+'-adultPrice .money-value').text('0.00');
      $('.print-'+tourCategory+'-childPrice .money-value').text('0.00');
      $('.print-'+tourCategory+'-consessionPrice .money-value').text('0.00');

      updateSubTotal();
      countDiscount();
      updateTotal();

      var itemSubtotal =$('.print-'+tourCategory+'-childQuantity').closest('.summary-item').find('.item-subtotal .money-value').text();
      var noBooking =$('.print-'+tourCategory+'-childQuantity').closest('.summary-item').find('.no-booking');

      if(itemSubtotal=='0.00'){
        $(noBooking).addClass('active');
      }else{
        $(noBooking).removeClass('active');
      }

      //Hide remove-extra button
      $(this).hide();
      
      $(this).closest('.card-body').find('.add-equipment').text('Add');
      $(this).closest('.card-body').find('.btn-toggle').html('Select <i class="fas fa-angle-down"></i>');
      $(this).closest('.extra-extension').collapse('hide');
      
      //Remove tick and green border to card
      $(this).closest('.card').removeClass('active');

      
    });
  });


  $('#tshirt .add-equipment').each(function(){
    $(this).on('click',function(){

      var smallQuantity= $(this).closest('.card-body').find('#small-tshirt-quantity').val();
      var mediumQuantity= $(this).closest('.card-body').find('#medium-tshirt-quantity').val();
      var largeQuantity= $(this).closest('.card-body').find('#large-tshirt-quantity').val();
      var extraLargeQuantity= $(this).closest('.card-body').find('#extra-large-tshirt-quantity').val();
      var extraExtraLargeQuantity= $(this).closest('.card-body').find('#extra-extra-large-tshirt-quantity').val();

      var price=Number($(this).closest('.card-body').find('.card-price-wrapper .money-value').text());

      var smallTshirtPrice= (smallQuantity *price).toFixed(2);
      var mediumTshirtPrice= (mediumQuantity *price).toFixed(2);
      var largeTshirtPrice = (largeQuantity * price).toFixed(2);
      var extraLargeTshirtPrice=(extraLargeQuantity * price).toFixed(2);
      var extraExtraLargeTshirtPrice=(extraExtraLargeQuantity * price).toFixed(2);


      if( (smallQuantity==0) && (mediumQuantity==0) && (largeQuantity==0) && (extraLargeQuantity==0) && (extraExtraLargeQuantity==0) ){
    
        $('.tshirt-summary').hide();
        $('.print-small-tshirt-quantity').text('0');
        $('.print-medium-tshirt-quantity').text('0');
        $('.print-large-tshirt-quantity').text('0');
        $('.print-extra-extra-large-tshirt-quantity').text('0');
        $('.print-extra-large-tshirt-quantity').text('0');

        $('.print-small-tshirt-price .money-value').text('0.00');
        $('.print-medium-tshirt-price .money-value').text('0.00');
        $('.print-large-tshirt-price .money-value').text('0.00');
        $('.print-extra-large-tshirt-price .money-value').text('0.00');
        $('.print-extra-extra-large-tshirt-price .money-value').text('0.00');


      $(this).closest('.extra-extension').collapse('hide');
      $(this).closest('.card-body').find('.remove-extra').hide();
      $(this).text('Add');
      $(this).closest('.card-body').find('.btn-toggle').html('Select <i class="fas fa-angle-down"></i>');

      $(this).closest('.card').removeClass('active');

      updateSubTotal();
      countDiscount();
      updateTotal();


    }else{

      if(smallQuantity != 0){
        $('.print-small-tshirt-quantity').text(smallQuantity);
        $('.print-small-tshirt-price .money-value').text(smallTshirtPrice);
        $('.small-tshirt-show').show();
      }else{
        $('.print-small-tshirt-quantity').text(smallQuantity);
        $('.print-small-tshirt-price .money-value').text(smallTshirtPrice);
        $('.small-tshirt-show').hide();
      }

      if(mediumQuantity != 0){
        $('.print-medium-tshirt-quantity').text(mediumQuantity);
        $('.print-medium-tshirt-price .money-value').text(mediumTshirtPrice);
        $('.medium-tshirt-show').show();
      }else{
        $('.print-medium-tshirt-quantity').text(mediumQuantity);
        $('.print-medium-tshirt-price .money-value').text(mediumTshirtPrice);
        $('.medium-tshirt-show').hide();
      }

      if(largeQuantity != 0){
        $('.print-large-tshirt-quantity').text(largeQuantity);
        $('.print-large-tshirt-price .money-value').text(largeTshirtPrice);
        $('.large-tshirt-show').show();
      }else{
        $('.print-large-tshirt-quantity').text(largeQuantity);
        $('.print-large-tshirt-price .money-value').text(largeTshirtPrice);
        $('.large-tshirt-show').hide();
      }

      if(extraLargeQuantity != 0){
        $('.print-extra-large-tshirt-quantity').text(extraLargeQuantity);
        $('.print-extra-large-tshirt-price .money-value').text(extraLargeTshirtPrice);
        $('.extra-large-tshirt-show').show();
      }else{
        $('.print-extra-large-tshirt-quantity').text(extraLargeQuantity);
        $('.print-extra-large-tshirt-price .money-value').text(extraLargeTshirtPrice);
        $('.extra-large-tshirt-show').hide();
      }

      if(extraExtraLargeQuantity != 0){
        $('.print-extra-extra-large-tshirt-quantity').text(extraExtraLargeQuantity);
        $('.print-extra-extra-large-tshirt-price .money-value').text(extraExtraLargeTshirtPrice);
        $('.extra-extra-large-tshirt-show').show();
      }else{
        $('.print-extra-extra-large-tshirt-quantity').text(extraExtraLargeQuantity);
        $('.print-extra-extra-large-tshirt-price .money-value').text(extraExtraLargeTshirtPrice);
        $('.extra-extra-large-tshirt-show').hide();
      }

      updateSubTotal();
      countDiscount();
      updateTotal();

      $(this).closest('.extra-extension').collapse('hide');
      $(this).closest('.card-body').find('.remove-extra').show();
      $(this).closest('.card-body').find('.btn-toggle').html('Edit <i class="fas fa-angle-down"></i>');
      $(this).text('UPDATE');

      $(this).closest('.card').addClass('active');

    }


    var itemSubtotal =$('.print-adultsBike-quantity').closest('.summary-item').find('.item-subtotal .money-value').text();
    var noBooking =$('.print-adultsBike-quantity').closest('.summary-item').find('.no-booking');

    if(itemSubtotal=='0.00'){
      $(noBooking).addClass('active');
    }else{
      $(noBooking).removeClass('active');
    }
    });

  });

  $('#tshirt .remove-extra').each(function(){
    $(this).on('click',function(){
      $('.tshirt-summary').hide();
      $('.print-small-tshirt-quantity').text('0');
      $('.print-medium-tshirt-quantity').text('0');
      $('.print-large-tshirt-quantity').text('0');
      $('.print-extra-extra-large-tshirt-quantity').text('0');
      $('.print-extra-large-tshirt-quantity').text('0');

      $('.print-small-tshirt-price .money-value').text('0.00');
      $('.print-medium-tshirt-price .money-value').text('0.00');
      $('.print-large-tshirt-price .money-value').text('0.00');
      $('.print-extra-large-tshirt-price .money-value').text('0.00');
      $('.print-extra-extra-large-tshirt-price .money-value').text('0.00');

      $(this).closest('.extra-extension').collapse('hide');
      $(this).closest('.card-body').find('.remove-extra').hide();
      $(this).closest('.card-body').find('add-equipment').text('Add');
      $(this).closest('.card-body').find('.btn-toggle').html('Add <i class="fas fa-angle-down"></i>');

      $(this).closest('.card').removeClass('active');

      updateSubTotal();
      countDiscount();
      updateTotal();


      var itemSubtotal =$('.print-adultsBike-quantity').closest('.summary-item').find('.item-subtotal .money-value').text();
      var noBooking =$('.print-adultsBike-quantity').closest('.summary-item').find('.no-booking');

      if(itemSubtotal=='0.00'){
        $(noBooking).addClass('active');
      }else{
        $(noBooking).removeClass('active');
      }

    });
  });

  //Sign In button toggled to already ahve an account
  $('.sign-in').on('click', function(){
    $(this).toggleClass('guest');
    $('.toggleInst').toggleClass('active');
    if($(this).hasClass('guest')){
      $(this).html('Sign in.')
      $('.toggleInst').text('Already have an account?');

    }else{
      $(this).html('continue as a guest.')
      $('.toggleInst').text('Sign in as a registered user, or');
    }
  })
  $('#signInForm').submit(function(e){
    e.preventDefault();
  });
  $('#payment').submit(function(e){
    e.preventDefault();
  });

  $('#signIn .btn').on('click',function(){
    $('#signIn').hide();
    $('.afterLoggedin').show();
    $('#makeAccount').closest('.form-group').hide();
  });

  $('#signIn .btn').on('click',function(){
    $('.beforeLogin').hide();
  });

  $('#signOut').on('click', function(){
    $('.afterLoggedin').hide();
    $('.beforeLogin').show();
  })

  //Get luggage frieght and count price
  $('.mobility .number-input').each(function(index){

    $(this).on( "spinstop", function( event, ui ){ 

      var value= $(this).spinner( "value" );
      var whereToPrint=('.print-'+$(this).attr("name")+'-quantity');
      var whereToPrintPrice=('.print-'+$(this).attr("name")+'-price .money-value');
      var whatToShow = ('.'+$(this).attr("name")+'-show');
      var price= Number('0');
      var priceItem= (value*price).toFixed(2);

      $(whereToPrint).text(value);
      $(whatToShow).show();

      //price
      $(whereToPrintPrice).text(priceItem);
      
      //var itemPrice=value*

      if(value !==0){
        $('.no-booking').removeClass('active');
        $(whatToShow).addClass('notZero');
      }     
      //if quanity 0 -> hide summary row
      if(value =='0'){
        $(whatToShow).hide();
        $(whatToShow).removeClass('notZero');

      }
      updateSubTotal();
      countDiscount();
      updateTotal();   

      if($('.summary-mobility').hasClass('notZero')){
        $(whereToPrint).closest('.summary-item').find('.no-booking').removeClass('active');
      }else{
        $(whereToPrint).closest('.summary-item').find('.no-booking').addClass('active');

      }
    });

  });

  $('.agreement .form-check-input ').on('change', function () {
    if ( ($('#bookingConfirmed').prop("checked") == true) && ($('#termCond').prop("checked") == true)) {
        $('#paymentBtn').prop("disabled", false);
    } else{
      $('#paymentBtn').prop("disabled", true);
    }
  });

  //if cta-wrapper exist add class to the body
  
    if($('.cta-wrapper').length){
      $('body').addClass('hasCtaWrapper');
    }


});


/* this Vue Select is used on Bus pickup --- select-ferries.html */
Vue.component('v-select', VueSelect.VueSelect)
window.app = new Vue({
  el: '#app',
  data: {
    morningPickups: [{
        label: 'Crown (Riverside Entrance) -  6:10am'
      },
      {
        label: 'Beatty Lodge - 6:15am'
      },
      {
        label: 'Billabong Backpackers - 6:20am'
      },
      {
        label: 'Coolibah Lodge - 6:20am'
      },
      {
        label: 'Great Southern Hotel - 6:25am'
      },
      {
        label: 'Double Tree Northbridge - 6:30am'
      },
      {
        label: 'Perth City YHA - 6:30am'
      },
      {
        label: 'Peppers Kings Square Hotel - 6:35am'
      },
      {
        label: 'Rendezvous Scarborough - 6:45am'
      },
      {
        label: 'Karrinyup Waters Resort - 6:55am'
      }
    ],
    pickupPoint: '',
    lateMorningPickups: [{
        label: 'Crown (Riverside Entrance) - 7:40am'
      },
      {
        label: 'Billabong Backpackers - 7:55am'
      },
      {
        label: 'Coolibah Lodge - 7:55am'
      },
      {
        label: 'Great Southern Hotel - 8:00am'
      },
      {
        label: 'Double Tree Northbridge - 8:00am'
      },
      {
        label: 'Pan Pacific - 8:05am'
      },
      {
        label: 'Crowne Plaza - 8:05am'
      },
      {
        label: 'Mantra on Hay (Cnr Hay & Bennett) - 8:10am'
      },
      {
        label: 'Cnr Hay and Pier St (533 Hay St) - 8:15am'
      },
      {
        label: 'Travelodge (Cat Bus stop out front) - 8:15am'
      },
      {
        label: 'Peppers Kings Square Hotel - 8:25am'
      },
      {
        label: 'Across the road from Holiday Inn- 8:25am'
      },
      {
        label: 'Rendezvous Hotel Perth Central - 8:30am'
      },
      {
        label: 'Ramada Perth (The Outr) - 9:00am'
      },
      {
        label: 'Karrinyup Waters Resort - 9:10am'
      }
    ],
    //this Vue Select is used on Accommodation pickup --- select-ferries.html 
    accommodations: [{
        title: 'Barracks',
        value: "barracks",
      },
      {
        title: "Bathurst (501-548)",
        units: [
          '501', '502', '503', '504', '508', '509', '510', '511', '512', '513', '514', '515', '516', '517', '518', '519', '520', '521', '522', '523', '524', '525', '526', '527', '528', '529', '530', '531', '532', '533', '534', '535', '536', '537', '538', '539', '540', '541', '542', '543', '544', '545', '546', '547', '548'
        ],
        value: "bathurst"
      },
      {
        title: "Campground (612-648)",
        units: [
          '612', '613', '614', '615', '616', '617', '618', '619', '620', '621', '623', '624', '625', '626', '627', '628', '629', '630', '631', '632', '633', '634', '635', '636', '637', '638', '639', '640', '641', '642', '643', '644', '645', '646', '647', '648'
        ],
        value: "campground"
      },
      {
        title: "Caroline Thomson",
        units: [
          '701', '702', '703', '704', '705', '706', '707', '708', '709', '710', '711', '712', '713', '714', '715', '716', '717', '718', '719', '720', '721', '722', '723', '724', '725', '726', '727', '728', 'TBC'
        ],
        value: "caroline thomson"
      },
      {
        title: "Discovery Parks Rottnest",
        value: "Discovery Parks Rottnest",
      },
      {
        title: "Geordie Bay",
        units: ['800', '801', '802', '803', '804', '805', '806', '807', '808', '809', '810', '811', '812', '813', '814', '815', '816', '817', '818', '819', '820', '821', '822', '823', '824', '825', '826', '827', '828', '829', '830', '831', '832', '833', '834', '835', '836', '850', '851', '852', '853', '854', '855', '856', '857', '858', '859', '860', '861', '862', '863', '864', '865'],
        value: "Geordie Bay"
      },
      {
        title: "Hostel",
        value: "Hostel",
      },
      {
        title: "Hotel Rottnest",
        value: "Hotel Rottnest",
      },
      {
        title: "Kingstown or Governors Circle",
        units: ['101', '102', '103', '104', '105', '106', '107', '108', '109', '111', '112', '113', '114', '115', '116', '117', '118', '119', '121', '122', '123', '124', '125', '126', '127', '128', '150', '152', '156', '166', '173', '179', '191'],
        value: "Kingstown or Governors Circle"
      },
      {
        title: "Karma Rottnest",
        units: ['101', '102', '103', '104', '105', '106', '107', '108', '109', '111'],
        value: "Karma Rottnest"
      },
      {
        title: "Longreach/Fays Bays",
        units: ['900', '901', '902', '903', '904', '905', '906', '907', '908', '909', '910', '911', '912', '913', '914', '915', '916', '917', '918', '919', '920', '921', '922', '923', '924', '925', '926', '927', '928', '929', '930', '931', '932', '933', '934', '935', '936', '937', '938', '939', '940', '941', '942', '943', '944', '945', '946', '947', '948', '949', '950'],
        value: "Longreach/Fays Bays"
      },
      {
        title: "Thomson Bay North (301-450)",
        units: ['301','302','303','304','305','306','307','308','309','310','311','312','313','314','315','316','317','318','319','320','321','322','323','324','325','326','327','328','329','330','331','332','333','334','335','336','337','338','339','340','341','342','343','344','345','346','347','348','349','350','351','352','353','354','355','356','357','358','359','360','361','362','363','364','365','366','367','368','369','370','371','372','373','374','375','376','377','378','379','380','381','382','383','384','385','386','387','388','389','390','391','392','393','394','395','396','397','398','399','400','401','402','403','404','405','406','407','408','409','410','411','412','413','414','415','416','417','418','419','420','421','422','423','424','425','426','427','428','429','430','431','432','433','434','435','436','437','438','439','440','441','442','443','444','445','446','447','448','449','450'],
        value: "Thomson Bay North"
      },
      {
        title:"Thomson Bay South (201-249)",
        units:['201','202','203','204','205','206','207','208','209','210','211','212','213','214','215','216','217','218','219','220','221','222','223','224','225','226','227','228','229','230','231','232','233','234','235','236','237','238','239','240','241','242','243','244','245','246','247','248','249'],
        value:"Thomson Bay South"
      },
      {
        title: "I'm not sure",
        value: "I am not sure"
      }
    ],
    selectedAccommodation: '',
    unitSelected: '',
    validated:'',
  },
  methods: {
    saveBus: function (event) {
      $('#busModal').modal('hide');
      var printPickupPoint = this.pickupPoint.label;
      $('.print-pickup-point').text(printPickupPoint);
      $('.temp-bus-show').show();

      //change button from add to edit ;
      //add remove bus
      $('#busModalTrigger').text('Edit pickup point');
      $('#removeBusPickup').show();
    },
    removeBus: function (event) {
      $('#busModalTrigger').text('Add bus pickup');
      $('#removeBusPickup').hide();
      $('.temp-bus-show').hide();
      this.pickupPoint = '';
    },
    clearBusSelection:function(){
      this.pickupPoint = '';
    },
    removeLuggage:function(event){
      this.unitSelected ='';
      this.selectedAccommodation=''
    },
    onChange(event) {
      this.unitSelected='';
    },
    clickable() {
      if(this.selectedAccommodation !==''){
        for (i = 0; i < this.accommodations.length; i++) {
          if(this.selectedAccommodation == this.accommodations[i].value ){
            if( (typeof this.accommodations[i].units =='undefined' || typeof this.accommodations[i].units =='') && $('#luggageQuantity').val()!=='0' ) {
              $('#saveLuggage').attr('disabled',false);
            }else{
              if( (this.unitSelected!=='')  &&  ($('#luggageQuantity').val()!=='0') && ($('#luggageTerms').prop('checked')==true )){
                $('#saveLuggage').attr('disabled',false);
              }else{
                $('#saveLuggage').attr('disabled',true);
              }
            }
          }
        }
      }else{
        $('#saveLuggage').attr('disabled',true);
      }
    }
  },
  
  computed: {
    happyClick() {
      if(this.selectedAccommodation !==''){
        for (i = 0; i < this.accommodations.length; i++) {
          if(this.selectedAccommodation == this.accommodations[i].value ){
            if( (typeof this.accommodations[i].units =='undefined' || typeof this.accommodations[i].units =='') && ($('#luggageQuantity').val()!=='0')  && ($('#luggageTerms').prop('checked')==true )){
              return false;
            }else{
              if( (this.unitSelected!=='')  &&  ($('#luggageQuantity').val()!=='0') && ($('#luggageTerms').prop('checked')==true )){
                return false;
              }else{
                return true;
              }
            }
          }
        }
      }else{
        return true;
      }
    }
  },
})
// Start By Himani
$(document).ready(function () {
    var step1 = $('.step-2').hasClass('active');
    var step2 = $('.step-2').hasClass('active');
    var step3 = $('.step-3').hasClass('active');
    var step4 = $('.step-4').hasClass('active');
    var step5 = $('.step-5').hasClass('active');

    if($('.step-1').hasClass('active')) {
      $('.btn.cta-btn').remove();
      var btn = '<button id="gotToLuggage" class="btn cta-btn disabled">Continue to Luggage</button>';
      $('.nextbutton').append(btn);
    }
    if($('.step-2').hasClass('active')) {
      $('.btn.cta-btn').remove();
      var btn = '<button id="gotToExtras" class="btn cta-btn">Continue to Extras</a>';
      $('.nextbutton').append(btn);
    }
    if(!$('.step-2').hasClass('disabled')) {
      $('.step-2').on('click', function() {
        $(this).addClass('active');
        $(this).removeClass('disabled');
        $('.luggage-block').show();
        $('.extras-block').hide();
        $('.step-3').addClass('disabled');
        $('.step-3').removeClass('active');
      });
    }
    if(!$('.step-3').hasClass('disabled')) {
      $('.step-3').on('click', function() {
        $(this).addClass('active');
        $(this).removeClass('disabled');
        $('.luggage-block').hide();
        $('.extras-block').show();
      });
    }
    $('#gotToExtras').on('click',function(){
        $('.step-3').addClass('active');
        $('.step-3').removeClass('disabled');
        $('.luggage-block').hide();
        $('.extras-block').show();
    });
});
// End By Himani