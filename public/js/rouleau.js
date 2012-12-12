$(document).ready(function(){

	//Lorsque vous cliquez sur un lien de la classe poplight et que le href commence par #
	$('a.poplight[href^=#]').click(function() {
		var popID = $(this).attr('rel'); // On recupere l'id du pop-up appelé

		//Faire apparaitre la pop-up avec un effet fade in
		$('#' + popID).slideDown('slow');

		return false;
	});

	//Fermeture de la pop-up au clic sur le bouton
	$('.close').live('click', function() { 
		$('.popup_block').slideUp('slow');
		// return false;
	});

});