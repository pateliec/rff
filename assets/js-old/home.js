/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    var date_input = $('input[name="dept-date"]'); //our date input has the name "date"
    var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
    var options = {
        format: 'mm/dd/yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
    };
    date_input.datepicker(options);

    // 

    $('.sbutton').click(function() {
        var button = $(this).text();
        var oldValue = $(this).parent().find('#passengervalue').val();

        if ($.trim(button) == "add") {
            var newVal = parseFloat(oldValue) + 1;
        } else {
            if (oldValue > 0) {
                var newVal = parseFloat(oldValue) - 1;
            } else {
                newVal = 0;
            }
        }
        $(this).parent().find('.passengervalue').val(newVal);
    });

    // 

    var date_input = $('input[name="ret-date"]'); //our date input has the name "date"
    var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
    var options = {
        format: 'mm/dd/yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
    };
    date_input.datepicker(options);

    // 

    var date_input = $('input[name="date"]'); //our date input has the name "date"
    var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
    var options = {
        format: 'mm/dd/yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
    };
    date_input.datepicker(options);

    // 

    $('#one-way').click(function () {
        $('#ret').hide();
        $('cruiesselect').hide();
        $('cruiesselect').hide();
        $('#departselct').show();
        $('#arriveselect').show();
        $('passengselect').show();
        $('ret').show();
        $('dept').show();
    });
    $('#return').click(function () {
        $('#departselct').show();
        $('#arriveselect').show();
        $('#ret').show();
        $('#passengselect').show();
        $('dept').show();
        $('cruiesselect').hide();
    });
    $('#cruies').click(function () {
        $('#departselct').hide();
        $('#arriveselect').hide();
        $('#ret').hide();
        $('cruiesselect').show();
        $('passengselect').show();
        $('dept').show();

        $('#div1').hide();
        $('#div2').show();
    });

    if ($('#one-way').prop("checked") == true) {
        $('#ret').hide();
        $('cruiesselect').hide();
        $('#departselct').show();
        $('#arriveselect').show();
        $('passengselect').show();
        $('ret').show();
        $('dept').show();
    } else {
        $('#div1').hide();
        $('#div2').show();
    }
});