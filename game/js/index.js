jQuery(function($){
	$('.hidden').hide();
	//$('#play').hide();
	
	$('#safe').mousedown(function(){
		if ($(this).attr('checked')){
			$('#safeWarn').text('***Safe Mode has been TURNED OFF***');
		}
		if (!$(this).attr('checked')){
			$('#safeWarn').text('Safe Mode is ON');
		}
	});
		
	$('.toggle').click(function(){
		var show = $(this).attr('show-attr');
		$('#'+show).slideToggle('fast');
		var sign = $(this).text();
		if(sign == '+'){
			$(this).text('-');
		}
		else{
			$(this).text('+');
		}
		return false;
	});

	$('#show-play').click(function(){
		$(this).text('');
		$('#play').slideDown('fast');
	});
});
