jQuery(function($){
	$(update());
	// Checks to see which players have played a card this in the current turn
	function checkPlay(playerID){
		$.post('./handle/check-play.php ',{id: playerID},
			function(data){
				if (data == 1){
					if ($('#player'+playerID).hasClass('played') != true){
						$('#player'+playerID).addClass('played');
					}
					else{}
				}
				else{}
		});
		var players = $('#roomPlayers').children().length;
		$.post('./handle/checkAll.php ',{},
			function(data){
				if ((players >= 2)&&(data)&&(data == players)){
					$('.answerCard').removeClass('playable');
					$.post('./handle/getAnswers.php ',{},
						function(newData){
						list = JSON.parse(newData)
						if ( $('#voteArea').children().length <= list.length ){
							for (var i=0; i < players; i++){
								$('#voteArea').append(list[i]);
								$('#voting').text('Vote for your favorite answer');
								$('#answers').hide();
								$('#voting').show();
							} // end for loop
						}
						else{}
					});
					$('#blackArea').hide();
					$('#voteArea').show();
				}
				else{
				}
		});
	}
	
	function updatePlayer(){
		$.post('./handle/checkUpdate.php',{},
			function(data){
				if (data >= 1){
					if ($('#blackCard').hasClass('submitted')){
						$.post('./handle/getBlack.php ',{},
							function(data){
								$('#blackCard').html(data);
								$('#blackCard').removeClass('submitted');
								$('.answerCard').addClass('playable');
								$('.player').removeClass('played');
						});
					}
					$('#voting').text('');
					$('#voting').hide();
					$('#answers').show();
					$('#blackCard').show();
					$('#blackArea').show();
					$('#voteArea').html('');
					$('#voteArea').hide();
				}
				else{
				}
		});
	}
	
	// removes inactive players and updates scores per player
	function playerChecks(playerID){
		$.post('./handle/check-active.php',{id: playerID},
			function(data){
				var array = JSON.parse(data);
				
				// Checks for the last click of all users and removes inactives
				if (array['checkPlayer'] == 1){
					$('div').remove('#player'+playerID);
				}
				else{}
				// update scores for players
				$('#score'+playerID).text(array['score']);
		});
	}
	
	// check if all players have voted, boots inactive player to home screen, and resets black card
	function checks(){
		// check if all players have voted.

		$.post('./handle/heartbeat.php',{},
			function(data){
				var array = JSON.parse(data);
				// if the player is inactive, send them back to home screen
				if (array['checkPlayer'] == 1){
					window.location.href= "http://"+location.host+"/game";
				}
				else{}
				
				// if everybody has voted, set a new black card and refresh the screen
				if (array['checkVote'] == 1){
					$('.player').removeClass('played');
				}
				else{}
		});
	}
	
	
	// Adds new players to the room
	var list;
	function addPlayer(){
		// check if new player has joined
		$.post('./handle/add-player.php',{},
			function(data){
				if (data != " "){
					list = JSON.parse(data);
					// compare player info to array of active players
					for (var i=0; i < list.length; i++){
						if($('#player'+list[i]).length == 0 ){
							$.post('./handle/addNew.php',{id: list[i]},
								function(newData){
									$('#roomPlayers').append(newData);
							});
						} // end if
						else{}
					} // end for loop
				} // end first if
				else{}
		});
	}
	
	// half second heart beat to refresh data on page
	function update(){
		setTimeout(function(){
			addPlayer();
			updatePlayer();
			checks();
			$('#roomPlayers').children('.player').each(function(){
				var playerID = $(this).attr('player-id');
				checkPlay(playerID);
				playerChecks(playerID);
			});
		update();
		}, 1000);
	}

});