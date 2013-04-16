	$.getJSON('handle/blackCards.php ',function(data){
		$.each(data, function(i){
			blackCards.push(data[i]);
		});
		getWhite();
	});
	
	function getWhite(){
		$.getJSON('handle/whiteCards.php ',function(data){
			$.each(data, function(j){
				whiteCards.push(data[j]);
			});
			dataReady();
		});
	}
	
	blackCards = blackCards.sort(function() { return 0.5 - Math.random() });
	whiteCards = whiteCards.sort(function() { return 0.5 - Math.random() });
	
			//for (var i=0; i < 10; i++) {
			//	$('#c'+(i+1)).text(whiteCards[i]);
			//}
			whiteCards.splice(0,10);
			
			//$('#gameCard').append(blackCards[0]);
			
			
	$('#commit').click(function(){
		$('#reset').hide();
		$('.dropped').show();
		$('.blank').html(blank);
		$('#'+droppedID).css({'top':top, 'left':left});
		$('.dropped').html(whiteCards[0]);
		$('#'+droppedID).removeClass('dropped');
		whiteCards.splice(0,1);
		blackCards.splice(0,1);
		$('#gameCard').html(blackCards[0]);
		$('.draggable').draggable('enable');
		console.log(blackCards.length);
		$('#commit').hide();
		return false;
	});
	
//
// WORKING VERSION OF checkActive
//

	function checkActive(playerID){
		$.post('./handle/check-active.php',{id: playerID},
			function(data){
				console.log(data);
				if (data == 1){
					$('#player'+playerID).remove();
				}
				else{}
		});
	}
	
	$('.draggable').draggable({revert: "invalid", snap:".droppable", snapMode: "inner", start: function(){
		$(this).css('z-index','100');
		top = parseInt($(this).css('top'))+20;
		left = parseInt($(this).css('left'))+13;
		console.log(top, left);
		blank  = $('.blank').html();
		}
	});
	
		$('.droppable').droppable({
		drop: function(event, ui){
			// set the text of the blank square to be the text of the dropped square
			var newAnswer = ui.draggable.text();
			$('.blank').html("<u>"+newAnswer+"</u>");
			ui.draggable.addClass('dropped');
			$('.dropped').hide();
			$('#reset').show();
			$('#commit').show();
			droppedID = $('.dropped').attr('id');
			$('.draggable').draggable('disable');
		}
	});
	
		$('#reset').click(function(){
		$('.dropped').show();		// show the dropped card again
		$('.blank').html(blank);	// reset the black card blank spot
		$('#'+droppedID).css({'top':top+'px', 'left':left+'px'});	// card to starting spot
		$('#'+droppedID).removeClass('dropped');
		$('#reset').hide();			// hide option to reset
		$('.draggable').draggable('enable');	// able to drag the other answers
		$('#commit').hide();		// hide option to submit answer
		return false;
	});