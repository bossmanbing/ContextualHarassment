var players = [];
jQuery(function($){
	chat();
	// hide options to reset and submit cards by default
	$('#reset').hide();
	$('#commit').hide();
	$('#voteArea').hide();
	$("#messages").scrollTop($("#messages")[0].scrollHeight);
	
	$('.answerCard').hover(
		function(){
			$(this).animate({
				zIndex: '1000',
				height: '190px',
				width: '150px',
				fontSize: '18px',
				fontWeight: 'bolder',
				borderRadius: '10px',
				top: '-=20px',
				left: '-=13px'
			}, 100);
		},
		function(){
			$(this).animate({
				height: '150px',
				width: '120px',
				fontSize: '15px',
				fontWeight: 'bold',
				borderRadius: '5px',
				top: '+=20px',
				left: '+=13px',
				zIndex: '20'
			}, 50);
		}
	);
			
	
	// placeholders to reset card back to default location after reset
	var top;
	var left;
	var blank;
	var droppedID;
	
	// save the starting location for a card when dragging it and save the text of the black card.
	//$('.playable').live('click',function(){
	$('.answerCard').live('click',function(){
		if ($(this).hasClass('playable')){
			top = parseInt($(this).css('top'))+20;
			left = parseInt($(this).css('left'))+13;
			blank  = $('.blank').html();
			var newAnswer = $(this).text();
			$('.blank').html("<u>"+newAnswer+"</u>");
			$(this).addClass('clicked');
			$('.clicked').hide();
			$('#reset').show();
			$('#commit').show();
			droppedID = $('.clicked').attr('id');
			$('.answerCard').removeClass('playable');
		}
	});
	
	// on drop, save white card text and add to the blank spot of the black card
	// hide the dropped card and show option to reset or submit card
	// get card ID to use with DB
	// disable draggable option for all other cards

	
	$('#reset').live('click', function(){
		$('.clicked').show();		// show the dropped card again
		$('.blank').html(blank);	// reset the black card blank spot
		$('#'+droppedID).css({'top':top+'px', 'left':left+'px'});	// card to starting spot
		$('#'+droppedID).removeClass('clicked');
		$('#reset').hide();			// hide option to reset
		$('.answerCard').addClass('playable');	// able to drag the other answers
		$('#commit').hide();		// hide option to submit answer
		return false;
	});

	$('#commit').live('click', function(){
		$('#voting').text('Waiting for other players');
		$('#answers').hide();
		$('#voting').show();
		var text = $('#blackCard').html();	// save full text of card with answer
		var card = $('.clicked').attr('card-id');
		$('.clicked').html('');
		// submit answer to database and associate to this user
		// display waiting message on black card
		$.post('./handle/commit.php ',{text: text},
			function(data){
				$('#blackCard').hide();
				$('#blackCard').html('');
				$('#blackCard').addClass('submitted');
			});
		$.post('./handle/newWhite.php ',{card: card},
			function(data){
				$('.clicked').html(data);
				updateScreen();
			});
		function updateScreen(){
			$('.clicked').show();
			$('#'+droppedID).removeClass('clicked');
			$('#p1').addClass('played');
			$('#reset').hide();		// hide option to reset card
			$('#commit').hide();	// hide option to submit card
		}
		return false;
	});
	$('#chat-form').submit(function(){
		var user = $('#form-user').val();
		var message = $('#chat-message').val();
		$.post('./handle/newMessage.php',{id: user, message: message},
			function(data){
				if (data){
					$("#messages").scrollTop($("#messages")[0].scrollHeight);
				}
				else{}
		});
		var message = $('#chat-message').val('');
		return false;
	});
	
	$('.votable').live('click', function(){
		var user = $(this).attr('user-id');
		$(this).addClass('voted');
		$('.blackAnswer').removeClass('votable');
		$.post('./handle/vote.php ',{user: user},
			function(data){
			});
		$('#voting').text("Waiting for others players");
	});
	
	function addMessage(messageID){
		// check for new messages in the chat table
		$.post('./handle/add-message.php',{id: messageID},
			function(data){
				if (data){
					$("#messages").scrollTop($("#messages")[0].scrollHeight);
					$('#messages').append(data);
				}
				else{}
		});
	}
	
	function chat(){
		setTimeout(function(){
			var messageID = $('.chatMessage').last().attr('message-id');
			addMessage(messageID);
		chat();
		}, 200);
	}

});
