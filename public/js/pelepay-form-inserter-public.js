(function( $ ) {
	'use strict';

	 $(document).ready(function($) {
		/* This function needed for paying (it copies the selected value into a hidden field that pelepay uses):*/
		$('#pelepay_submit').click(function() {
			document.pelepayform.amount.value = document.getElementById("amount").value;
                        // in case user changes the description, copy the text input value to the description hidden field:
                        if (document.getElementById("description_text").value !== ''){
                            document.pelepayform.description.value = document.getElementById("description_text").value;                            
                        }
                        
		});
	 });

})( jQuery );

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
