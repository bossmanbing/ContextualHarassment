jQuery(function($){
	
	// handle submission of the report
	// replace form with thank message on submission
	$('#report-form').submit(function(){
		var message = $('#report-message').val();
		$.post('handle/post-report.php ',{message: message},
			function(data){
				$('#report-form').remove();
				$('#left-side').append("<br /><h1>Your report has been submitted. Thanks!</h1>");
			});
		return false;
	});
	
	var place;
	var upPlace;
	var downPlace;
	// adds or subtracts points based on value selected
	// handles movement of submissions when comparing point values change
	function modPoints(id, data){
		$('#'+id).text(data);
		// get numeric location of the order of the clicked box
		$('#com'+place).attr('point-data', data);
		// positive numbers display green
		if(data > 0){
			$('#'+id).css('color', '#239923');
		}
		// negative numbers display red
		if(data < 0){
			$('#'+id).css('color', '#992323');
		}
		// 0 displays black
		if(data==0){
			$('#'+id).css('color', '#000000');
		}
		var thisPoint;
		var upOne;
		var downOne;
		// points for the clicked box
		// grabs number of points for the box immediately below and above
		// the clicked box
		thisPoint = $('#com'+place).attr('point-data');
		upOne = $('#com'+upPlace).attr('point-data');
		downOne = $('#com'+downPlace).attr('point-data');
		console.log(thisPoint,upOne,downOne);
		// move clicked box up if it has more points than higher box
		if(upOne && thisPoint > upOne){
			$('#com'+place).animate({"top": "-=92px"});
			$('#com'+upPlace).animate({"top": "+=92px"});				
		}
		// move clicked box down if it has less points than box below
		if(downOne && downOne > thisPoint){
			$('#com'+place).animate({"top": "+=92px"});
			$('#com'+downPlace).animate({"top": "-=92px"});				
		}
	}
	
	// get point value for this box and adjacent boxes
	$('.mod-point').click(function(){
		$(this).hide();
		$(this).siblings().hide();
		place = parseInt($(this).parent().attr('com-data'));
		upPlace = parseInt($(this).parent().attr('com-data'))-1;
		downPlace = parseInt($(this).parent().attr('com-data'))+1;
		return false;
	});
	
	// add a point and change value, color, and location
	$('.plus').click(function(){
		var oper = 'plus';
		var thisID = $(this).attr('plus-attr');
		$.post('handle/mod-point.php ',{oper: oper, id: thisID},
			function(data){
				modPoints(thisID, data);
			});
	});
	// subtract a point and change value, color, and location
	$('.minus').click(function(){
		var oper = 'minus';
		var thisID = $(this).attr('min-attr');
		$.post('handle/mod-point.php ',{oper: oper, id: thisID},
			function(data){
				modPoints(thisID, data);
			});
	});
});
