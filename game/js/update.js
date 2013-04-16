jQuery(function($){
	$('.form-submit').submit(function(){
		$(this).remove();
		$.post('update.php ',{month: qryMonth, year: qryYear, day: qryDate},
		function(data){
			$('#day'+qryDate).append(data);
		});
		return false;
	});
});
