// JavaScript Document

jQuery(document).ready(function() {

     // remove default html5 validation
     var form = document.getElementById("post");
     form.noValidate = true;

    // start form validation
 var frmvalidator  = new Validator("post");
    frmvalidator.addValidation("bee_listing_description","req","Please describe few word of  your Listing  !");
	frmvalidator.addValidation("bee_listing_address","req","Please Enter your contact address ! ");
	frmvalidator.addValidation("bee_listing_email","req","Please Enter your Email ! ");
	frmvalidator.addValidation("bee_listing_email","email","Please Enter valid Email Address ! ");

});