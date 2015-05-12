(function( $ ) {
	'use strict';

	 $(document).ready(function($) {
		/* This function needed for paying (it copies the selected value into a hidden field that pelepay uses):*/
		$('#pelepay_submit').click(function() {
			document.pelepayform.amount.value = document.getElementById("amount").value;
		});
	 });

})( jQuery );
