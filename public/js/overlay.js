$(document).ready(function(){

	//Lorsque vous cliquez sur un lien de la classe poplight et que le href commence par #
	$('a.overlay[href^=#]').click(function() {
		var popID = $(this).attr('rel'); // On recupere l'id du pop-up appelé

		//Faire apparaitre la pop-up avec un effet fade in
		$('#' + popID).fadeIn();

		//Récupération du margin, qui permettra de centrer la fenêtre
		var popMargTop = ($('#' + popID).height()) / 2;
		var popMargLeft = ($('#' + popID).width()) / 2;

		//On affecte le margin
		$('#' + popID).css({
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});

		//Effet fade-in du fond opaque
		// $('body').append(''); //Ajout du fond opaque noir
		//Apparition du fond - .css({'filter' : 'alpha(opacity=80)'}) pour corriger les bogues de IE
		$('#fade').css({'filter' : 'alpha(opacity=50)'}).fadeIn();

		return false;
	});

	//Fermeture de la pop-up et du fond
	$('.close').live('click', function() { //Au clic sur le bouton ou sur le calque...
		$('#fade , .overlay_block').fadeOut();
		// return false;
	});

});