(function($) {
	"use strict";
	
	//accordion-wizard
	var options = {
		start: 1,
		stepNumbers:true,
		stepNumberClass:'',
		mode: 'wizard',
		autoButtonsNextClass: 'btn btn-primary btn_newx_valdation2 float-right',
		autoButtonsPrevClass: 'btn btn-info',
		stepNumberClass: 'badge badge-pill badge-primary mr-1',
		/*beforeNextStep:function( currentStep ) {
			if(step_wizard = 1){
				return false;
			}
			else if(step_wizard = 2){
				return false;
			}
			else{
				return true; 
			}	
		},*/
		onSubmit: function() {
		  	return true;
		},
	}
	$( "#form" ).accWizard(options);
	
})(jQuery);      