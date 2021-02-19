$.validator.addMethod("password_regex", function(value, element) 
{
    var regex = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8}/;
    // return this.optional(element) || /^[a-z0-9\.\-_]{3,30}$/i.test(value);
    return this.optional(element) || regex.test(value);
}, "Please choise a username with only a-z 0-9.");

$(document).ready(function()
{
    $('#form_register').validate(
    {
        rules: {
            'email': {
                required: true
            },
            'confirmemail': {
                required: true,
                equalTo: '#email'
            },
            'password': {
                required: true,
                password_regex: true
            },
            'confirmpassword': {
                required: true,
                equalTo: '#confirmpassword'
            },
            'title': {
                required: true
            },
            'mobile_phone': {
                required: true
            },
            'firstname': {
                required: true
            },
            'lastname': {
                required: true
            },
            'gender': {
                required: true
            },
            'address': {
                required: true
            },
            'state':  {
                required: true
            },
            'country': {
                required: true
            },
            'city': {
                required: true
            },
            'postcode': {
                required: true
            },
            'day': {
                required: true
            },
            'month': {
                required: true
            },
            'year': {
                required: true
            }
        },
        messages: {
            'email': {
                required: "This is a required field."
            },
            'confirmemail': {
                required: "This is a required field.",
                equalTo: "Email and Confirm Email should be same"
            },
            'password': {
                required: "This is a required field.",
                password_regex: " Please create a password that has atleast 8 characters, has atleast one capital letter and includes digits."
            },
            'confirmpassword': {
                required: "This is a required field.",
                equalTo: "Password and Confirm Password should be same."
            },
            'title': {
                required: "This is a required field."
            },
            'mobile_phone': {
                required: "This is a required field."
            },
            'firstname': {
                required: "This is a required field."
            },
            'lastname': {
                required: "This is a required field."
            },
            'gender': {
                required: "This is a required field."
            },
            'address': {
                required: "This is a required field."
            },
            'state':  {
                required: "This is a required field."
            },
            'country': {
                required: "This is a required field."
            },
            'city': {
                required: "This is a required field."
            },
            'postcode': {
                required: "This is a required field."
            },
            'day': {
                required: "This is a required field."
            },
            'month': {
                required: "This is a required field."
            },
            'year': {
                required: "This is a required field."
            }
        }
    });

    $('#form_login').validate(
    {
        rules: {
            'email': {
                required: true
            }
        },
        messages: {
            'email': {
                required: 'Email is a required field.'
            }
        }
    });
});