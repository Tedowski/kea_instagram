


$('#frmLogin').submit(function () {
    let thisForm = $(this);

    if(fnbValidateInput(thisForm)) {

        $.ajax({
            method: "POST",
            url: "apis/api-login.php",
            data: $('#frmLogin').serialize(),
            dataType: "JSON"
        })
            .done(function (jData) {
                if(jData.status == -1){
                    swal({
                        title: "System update",
                        text: jData.message,
                        icon: "error",
                    });
                } else if(jData.status == 0) {
                    swal({
                        title: "System update",
                        text: jData.message,
                        icon: "warning",
                    });
                } else if(jData.status == 1) {
                    location.href = 'home.php';
                }
            })
            .fail(function () {
                swal({
                    title: "System update",
                    text: "Cannot process your request. Please try again later (AJAX ERROR)",
                    icon: "error",
                });
            });

        return false;
    } else {
        console.log('Invalid input');
        return false;
    }
});

$('#frmSignup').submit(function () {
    let thisForm = $(this);

    if(fnbValidateInput(thisForm)) {

        $.ajax({
            method: "POST",
            url: "apis/api-signup.php",
            data: $('#frmSignup').serialize(),
            dataType: "JSON"
        })
            .done(function (jData) {
                console.log(jData);
                if (jData.status == 1) {
                    swal({
                        title: "Success!",
                        text: jData.message,
                        icon: "success",
                    });

                    $('#signup').addClass('hidden');
                    $('#login').removeClass('hidden');

                } else if (jData.status == -1) {
                    swal({
                        title: "System update",
                        text: jData.message,
                        icon: "error",
                    });
                } else if (jData.status == 0) {
                    swal({
                        title: "System update",
                        text: jData.message,
                        icon: "warning",
                    });
                }
            })
            .fail(function () {
                swal({
                    title: "System update",
                    text: "Cannot proccess your request. Please try again later (AJAX ERROR)",
                    icon: "error",
                });
            });

        return false;
    } else {
        swal({
            title: "Error",
            text: "invalid input",
            icon: "error",
        });
        return false;
    }
});

// Validate function *************************************************************************

function fnbValidateInput(formToValidate) {
    let isValid = true;
    // which elements to validate?
    $(formToValidate).find('input[data-validate=yes]').each(function () {
        $(this).removeClass('invalid');
        //what to validate?

        let sDataType = $(this).attr('data-type');

        let iMin = $(this).attr('data-min');
        let iMax = $(this).attr('data-max');

        switch (sDataType) {
            case "string":
                if ($(this).val().length < iMin || $(this).val().length > iMax) {
                    $(this).addClass('invalid');
                    isValid = false;
                }
                break
            case "integer":
                let reN = /^[0-9]*$/
                if (!reN.test(String($(this).val())) || $(this).val().length < iMin || $(this).val().length > iMax) {
                    $(this).addClass('invalid');
                    isValid = false;
                }
                break
            case "email":
                let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if ($(this).val().length < iMin || $(this).val().length > iMax || !re.test(String($(this).val()).toLowerCase())) {
                    $(this).addClass('invalid');
                    isValid = false;
                }
                break
            default:
                console.log('No idea how to validate that')

                break
        }
    });

    if (isValid) {
        return true
    } else {
        return false
    }
}