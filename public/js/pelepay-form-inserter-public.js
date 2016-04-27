(function ($) {
    'use strict';

    $(document).ready(function ($) {
        // hide error message
        $('.pelepay_error_msg').hide();
        /* This function needed for paying (it copies the selected value into a hidden field that pelepay uses):*/
        $('#pelepay_submit').click(function () {
            if (document.pelepayform.amount_select.value == 0) {
                $('.pelepay_error_msg').show();
                $('.amount_select').focus();
                return false;
            } else {
                document.pelepayform.amount.value = document.pelepayform.amount_select.value;
                // in case user changes the description, copy the text input value to the description hidden field:
                if (document.pelepayform.description_text.value !== '') {
                    document.pelepayform.description.value = document.pelepayform.description_text.value;
                }
            }
        });
        if ($('#principles_submit').length > 0) {
            // first disable button. enable only if checkbox is checked
            $('#principles_submit').prop('disabled', true);
            $("#read_chkbx").change(function () {
                if (this.checked) {
                    $('#principles_submit').prop('disabled', false);
                } else {
                    $('#principles_submit').prop('disabled', true);
                }
            });
        }
    });

})(jQuery);

/* this function is still needed, for the old forms that use it*/
function onPay()
{
    document.pelepayform.amount.value = document.getElementById("amount").value;
    // in donation page, let the user write what the donation is for
    var amnt_desc = document.getElementById("amount-description");
    if (typeof (amnt_desc) != 'undefined' && amnt_desc != null && amnt_desc.value != "") {
        document.pelepayform.description.value = amnt_desc.value;
    }
}
