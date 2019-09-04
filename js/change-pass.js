$(document).ready(function() {

    $('#passForm').submit(function() {

        $.ajax({
            method: "POST",
            url: "apis/api-change-pass.php",
            data: $('#passForm').serialize(),
            dataType: "JSON"

        }).done(function(jData) {

            if(jData.status == 0) {
                swal({
                    title: "System update",
                    text: jData.message,
                    icon: "warning",
                });
            } else if(jData.status == 1) {
                swal({
                    title: "Success",
                    text: jData.message,
                    icon: "success",
                });
                location.href = 'profile.php';
            }
        }).fail(function() {
            swal({
                title: "System error",
                text: "Please try again later (AJAX ERROR)",
                icon: "error",
            });
        });

        return false;
    });
})