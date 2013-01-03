 
//Remplir les select du formulaire en fonction de la réponse AJAX
function remplirSelect (dataAjax) {
    // Envoi requête AJAX
    $.ajax({
         type: "POST"
       , url: "../ajax/remplir" // controller : ajax , action : remplir
       , data : dataAjax
       , dataType: "json"
       , success: function(reponse){ // Sur Succès de la réponse AJAX
		    // Duplique ma réponse	
   		    var optionData = reponse;
			// Suppression des éléments de mes listes déroulantes
 
		 	// Mes villes
		 	i = 0;           
			for (key in reponse.ville) {
				if (i <= 0) {
					$("#villeDepart > option").remove();
				};

				$("#villeDepart").append(  '<option label="' + optionData['ville'][key]+ '"' + 'value="' + key + '">'
								  			+ optionData['ville'][key]
								  		+ '</option>');
				i++;
				if (i == 2) { 
					$("#villeDepart:first").prepend( '<option label="Choisissez" value="" >-- Choississez --</option>');
					$("#villeDepart option:first").attr ('selected', 'selected');
				}
			} //Eof:: for 'ville'
	 
		 	// Mes aeroports
		 	i = 0;           
			for (key in reponse.aeroport) {
				if (i <= 0) {
					$("#aeroportDepart > option").remove();
				};

	 			$("#aeroportDepart").append(  '<option label="' + optionData['aeroport'][key] + '"' + 'value="' + key + '">'
								  				+ optionData['aeroport'][key]
								  			+ '</option>');
			    i++;
			    if (i == 2) { 
					$("#aeroportDepart:first").prepend( '<option abel="Choisissez" value="" >-- Choississez --</option>');
					$("#aeroportDepart option:first").attr ('selected', 'selected');
				}
			} //Eof:: for 'aeroport'
    	} //Eof:: success
    });  //Eof:: ajax 
} //Eof:: fucntion remplirSelect
 
 
//Sur fin du chargement du document
$(document).ready( function() {

 
	// Sur changement de l'un des 'select'
	$("select").change(function(){
 
		// Je recupère la valeur des sélections en cours
	 	VpaysDepart = $("select#paysDepart").val();
	 	VvilleDepart = $("select#villeDepart").val();
	 	VaeroportDepart = $("select#aeroportDepart").val();
 
	 	// Données à passer à la requête AJAX
	 	var dataAjax = {  paysDepart:VpaysDepart
	 	                , villeDepart:VvilleDepart
	 					, aeroportDepart:VaeroportDepart
 	         	    };

		// Modification des 'select'
		remplirSelect (dataAjax);
	}); //Eof:: sur changement de l'un des 'select'
 
 
	// Sur click bouton reset
	$("#fermerbutton").click (function() {
 
		// Aucun choix
	 	VpaysDepart = "";
	 	VvilleDepart = "";
	 	VaeroportDepart = "";
 
	 	// Données à passer à la requête AJAX
	 	var dataAjax = {  paysDepart:VpaysDepart
	 	                , villeDepart:VvilleDepart
	 					, aeroportDepart:VaeroportDepart
 	         	    };
 
		// Modification des 'select'
		remplirSelect (dataAjax);
 
	}); //Eof:: sur click btn 'reset'
}); //Eof:: ready