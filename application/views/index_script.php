<script>
  //Covert date to the format needed for lightpicker : sth like 'Wed Dec 02 2020'
  function convert(str) {
    var date = new Date(str),
      mnth = ("0" + (date.getMonth() + 1)).slice(-2),
      day = ("0" + date.getDate()).slice(-2);
      return [day, mnth, date.getFullYear()].join("/");
  } 

  let datepicker1 = document.getElementById('datepicker1');
  let datepicker2 = document.getElementById('datepicker2');

  //today is the minimum date
  var today = new Date();
  convert(today);

  //Function to fake the end date if end date is not defined.
  document.addEventListener('click', (e) => {
    let el = e.target;
    let isDayItem = el.classList.contains('day-item');
    let isEndDate = el && el.classList.contains('is-end-date');
    let isStartDate = !document.querySelector('.day-item.is-end-date');

   if (isDayItem && !isEndDate) {
      let startDate = convert(new Date(Number(el.dataset.time)));
      datepicker1.value = `${startDate}`;
      datepicker2.value =`${startDate}`;

      picker.setDateRange( new Date(Number(el.dataset.time)),new Date(Number(el.dataset.time)) );

    }
  });

  //Init the date range picker by another div, not the input to fake the start and end date
  let picker = new Litepicker({
    format:'DD/MM/YYYY',
    element: document.getElementById('triggerCalendar'),
    singleMode: false,
    numberOfColumns:2,
    numberOfMonths:2,
    minDate:today,

    onSelect: function(date1, date2) {
      datepicker1.value = `${convert(date1)}`;
      datepicker2.value =`${convert(date2)}`;
      //selectedRange.innerText = `${date1.toDateString()} - ${date2.toDateString()}`; 
    },
   
  });

  let picker2 = new Litepicker({
    format:'DD/MM/YYYY',
    element: document.getElementById('singleFerryDate'),
    singleMode: true,
    numberOfColumns:1,
    numberOfMonths:1,
    minDate:today,

  });

  document.querySelector('#return').addEventListener('change', (event) => {

    if(document.querySelector('#return').checked){
      
      document.querySelector('#triggerCalendar').style.display = "block";
      document.querySelector('#hideThis').style.display = "block";
      //return
      document.querySelector('#singleDate').style.display = "none";

     

    }else{


      document.querySelector('#hideThis').style.display = "none";
      document.querySelector('#singleDate').style.display = "block";

  

    }
   
  });



  </script>
  
<script>

function sourceDestination(surrce)
{
	if(surrce == "HIL")
	{
		jQuery("#route").val('HILROT');
		jQuery("#outward").val('HIL');
		jQuery("#arrival").val('ROT');
		jQuery("#hill_button").addClass('active');
		jQuery("#inputTo").val('Rottnest Island');
		
	}
	else
	{
		jQuery("#route").val('ROTHIL');
		jQuery("#outward").val('ROT');
		jQuery("#arrival").val('HIL');
	}
}

function changeAction(id)
{
	if(jQuery(id).prop('checked') == true){
		jQuery('#myForm').attr('action', '<?php echo site_url(); ?>processbooking/returnBooking/');
		jQuery("#tickettype").val('return');
    }
	else
	{
		jQuery('#myForm').attr('action', '<?php echo site_url(); ?>processbooking/onewayBooking/');
		jQuery("#tickettype").val('oneway');
	}
}

jQuery(document).ready(function() {
	jQuery("#hill_button").trigger("click");
});
</script>
