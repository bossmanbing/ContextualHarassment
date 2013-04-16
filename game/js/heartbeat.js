jQuery(function($){
	$(update());
	// Checks to see which players have played a card this in the current turn
	function checkPlay(playerID){
		$.post('./handle/check-play.php ',{id: playerID},
			function(data){
				if (data == 1){
					if ($('#player'+playerID).hasClass('played') != true){
						$('#player'+playerID).addClass('played');
						console.log('yup');
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
	
	// Checks to see if everyone has voted and resets board
	function checkVote(playerID){
		// check if all players have voted. 
		var players = $('#roomPlayers').children().length;
		$.post('./handle/check-vote.php ',{},
		function(data){
			if ((data == 1)){
				$('.player').removeClass('played');
				$.post('./handle/newBlack.php ',{},
					function(newData){
				});
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
								console.log(data);
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
	
	function updateScore(playerID){
		// check if all players have voted. 
		$.post('./handle/newScore.php',{id: playerID},
			function(data){
				$('#score'+playerID).text(data);
		});
	}
				
	// Checks for the last click of all users and removes inactives
	function checkActive(playerID){
		$.post('./handle/check-active.php',{id: playerID},
			function(data){
				if (data == 1){
					$('div').remove('#player'+playerID);
				}
				else{}
		});
	}
	
	function checkPlayer(){
		$.post('./handle/checkPlayer.php',{},
			function(active){
				if (active == 1){
					window.location.href= "http://"+location.host+"/game";
				}
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
			checkVote();
			updatePlayer();
			checkPlayer();
			$('#roomPlayers').children('.player').each(function(){
				var playerID = $(this).attr('player-id');
				console.log(playerID);
				updateScore(playerID);
				checkPlay(playerID);
				checkActive(playerID);
			});
		update();
		}, 1000);
	}

});