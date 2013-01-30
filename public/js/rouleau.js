$(document).ready(function(){

	//Lorsque vous cliquez sur un lien de la classe poplightedit et que le href commence par #
	$('a.poplightedit[href^=#]').click(function() {
		//on fait disparaitre toutes les popups
		$('.popup_block').slideUp('slow');
	});

	//Lorsque vous cliquez sur un lien de la classe poplight et que le href commence par #
	$('a.poplight[href^=#]').click(function() {
		// On recupere l'id du pop-up appelé
		var popID = $(this).attr('rel');

		//on fait apparaitre la pop-up avec un effet de slide
		$('#' + popID).slideDown('slow');

		return false;
	});

	//Fermeture de la pop-up au clic sur le bouton
	$('.close').live('click', function() { 
		$('.popup_block').slideUp('slow');
	});

});