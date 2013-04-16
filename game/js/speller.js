// create an array of letters for the random answer choices
var answerLetters = [ "a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z" ];
// an array of full answer words
var answerWords = [ "tree","sun","star"];
jQuery(function($){
	
	
	$('#next').hide();
	$('#wrong').hide();
		
	var difficulty = 1;
	
	$.getJSON('handle/list.php ',function(data){
		$.each(data, function(i){
			answerWords.push(data[i]);
		});
		dataReady();
	});
	function dataReady(){
		// shuffle the list of words in random order
		picList = answerWords.sort(function() { return 0.5 - Math.random() });
		// set the starting point in the array
		var start = 0;
		// sets the picture to display and set corresponding information
		var setPic = function(start){
			var wrong = 0;
			// get current image name from array
			pick = picList[start];
			// set picture to the screen
			$('#picture').fadeIn('fast').html("<img src='imgs/"+pick+".jpg' alt='image' />");
			// pick a random letter to remove from the word
			var missing = Math.floor(Math.random()*(pick.length));
			// insert the removed letter into an array of possible answers
			var missLetter = pick[missing];
			var tempGuess = [missLetter];
			var guesses = []
			var answers = []
			if (difficulty == 2){
				while ($.inArray(pick[missing], tempGuess) !== -1){
					missing = Math.floor(Math.random()*(pick.length));
				}
				tempGuess.push(pick[missing]);
			}
			// create the squares containing the letters for the word
			for (var i=0; i < pick.length; i++) {
				// show the letter if it is not removed
				if ( $.inArray(pick[i],tempGuess) == -1 
					|| guesses.length == difficulty){
					$('#word').append("<div class='letter answers'>"+pick[i]+"</div>");
				}
				// if this is the removed letter, show an empty gray square
				else{
					$('#word').append("<div id='missing' class='letter droppable' attr-ans='"+pick[i]+"'></div>");
					guesses.push(pick[i]);
					answers.push(pick[i]);
				}
			}
			// pick 3 random letters to append to array of posible answers
			var loop = 4-guesses.length;
			for (var i=0; i < loop; i++) {
				var insert = answerLetters[Math.floor(Math.random()*(answerLetters.length))];
				// do not append the letter if it the right answer
				if (insert == missLetter){
					insert = answerLetters[Math.floor(Math.random()*(answerLetters.length))];
				}
				// traverse the list to avoid entering a duplicate guess
				for (var u=0; u < 3; u++) {
					if ( insert == guesses[u] ){
						insert = answerLetters[Math.floor(Math.random()*(answerLetters.length))];
					}
				}
				guesses.push(insert);
			}
			// shuffle the order of the possible answers
			guesses.sort(function() { return 0.5 - Math.random() });
			// display the possible answers to the screen
			for (var i=0; i < guesses.length; i++) {
				$('#guesses').append("<a href='.' class='letter draggable'>"+guesses[i]+"</a>");
			}
			// set the possible answers as draggable
			$('.draggable').draggable({revert: "invalid", snap:".droppable", snapMode: "inner", start: function(){
				// push all draggable pieces to the back and move the currently dragged piece to the front
				$('.draggable').css('z-index','1');
				$(this).css('z-index','100');
				}
			});
			var numMissing = guesses.length;
			var numPlaced = 0;
			// set blank square as a droppable element
			$('.droppable').droppable({
				drop: function(event, ui){
					// set the text of the blank square to be the text of the dropped square
					var newGuess = ui.draggable.text();
					ui.draggable.addClass('answers');
					var ans = $(this).attr('attr-ans');
					// if dropped square is the correct answer, congratulate user, add a star, and change to the next picture after set amount of time
					if ( newGuess == ans ){
						numPlaced ++;
						ui.draggable.draggable('disable');
						if ( numPlaced !== difficulty ){
							ui.draggable.css('background','#9AFF9A');
						}
						else{
							$('.answers').css('background','#9AFF9A');
							$('#next').fadeIn('fast');
							$('.star').removeClass('.new');
							$('#score').append("<img src='imgs/star.jpg' class='star new'/>");
							$('.new').fadeIn('slow');
							// change the picture, reset words and answer squares, and increment word array location
							setTimeout(function(){
								$('#picture').hide();
								$('#word').html('');
								$('#guesses').html('');
								$('#next').hide();
								start ++;
								// on the last word, reshuffle the array and start over
								if ( start >= answerWords.length ){
									wrong = 0;
									picList = answerWords.sort(function() { return 0.5 - Math.random() });
									start = 0;
									difficulty = 2;
								}
								setPic(start);
							}, 2000);
						}
					}
					// set word background to red for a wrong answer
					else{
						// on the first wrong guess append the word to the end of the array
						if ( wrong == 0 ){
							wrong = 1;
							answerWords.push(pick);
							console.log(answerWords, "Word appended to array");
						}
						// do not append the word if the player already guessed wrong on this word
						else{
							console.log("Word already added, won't add again");
						}
						if ( difficulty == 2 && $.inArray(newGuess,answers)!== -1){
							ui.draggable.css('background','#FF6A6A');
						}
						else{
							$('.answers').css('background','#FF6A6A');
							$('#wrong').fadeIn('fast');
							setTimeout(function(){
								$('.answers').css('background','#FFFFFF');
								$('#missing').text('');
								$('#wrong').hide();
								ui.draggable.remove();
							}, 1000);
						}
					}
				}
			});
		}
		// set the first picture
		setPic(start);
		// disable link on draggable squares
		$('.draggable').live('click',function(){
			return false;
		});
	}
});
