jQuery(function($){
	$('*[rel="tooltip"]').tooltip();
	
	$('.input.error input,.input.error textarea').focus(function(){
		$(this).parent().removeClass('error'); 
		$(this).parent().find('.error-message').remove(); 
	})

});

jQuery(function($){
	$.datepicker.setDefaults($.datepicker.regional['fr']);
	var datapickers = $('.datepicker').datepicker({
	    //minDate : 0,
	    dateFormat : 'yy-mm-dd',
	    changeMonth: true,
		changeYear: true,
	    onSelect: function( date ) {
	    	var option = this.id == 'dateStart' ? 'minDate' : 'maxDate';
	    	datapickers.not('#'+this.id).datepicker('option',option,date);
	    }
	});

	$('.timepicker').timepicker({
	    timeFormat: 'hh:mm:ss',
	    showSecond:true,
	    second : 0
	});

	$('.datetimepicker').datetimepicker({
	    dateFormat : 'yy-mm-dd',
	    separator: ' ',
	    second : 0,
	    timeFormat: 'hh:mm:ss',
	    showSecond:true
	});

});

jQuery(function(){

	var width = $(".navbar-fixed-top").width();
	if (width < 963) {
		$("#admin i").each(function(){
			$(this).addClass('icon-white');
		});
	}else{
		$("#admin i.icon-white").each(function(){
			$(this).removeClass('icon-white');
		});
	};

	$(window).bind('resize', function() {
		var width = $(".navbar-fixed-top").width();
		if (width < 963) {
			$("#admin i").each(function(){
				$(this).addClass('icon-white');
			});
		}else{
			$("#admin i.icon-white").each(function(){
				$(this).removeClass('icon-white');
			});
		};
	});
});