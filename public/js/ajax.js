//////////////////////////////////////////////////////////////////////////////////////////////////////
		//Remplir les select du formulaire en fonction de la réponse AJAX
		function remplirSelectDepart (dataAjax) {
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
					$("#aeroportDepart > option").remove();
			 
				 	// Mes aeroports
				 	i = 0;           
					for (key in reponse.aeroport) {	
			 			$("#aeroportDepart").append(  '<option label="' + optionData['aeroport'][key] + '"' + 'value="' + key + '">'
										  				+ optionData['aeroport'][key]
										  			+ '</option>');
					    i++;
					    if (i >= 2) { 
							$("#aeroportDepart:first").prepend( '<option label="Choisissez" value="-1" >-- Choississez --</option>');
							$("#aeroportDepart option:first").attr ('selected', 'selected');
						}
					} //Eof:: for 'aeroport'
		    	} //Eof:: success
		    });  //Eof:: ajax 
		} //Eof:: fucntion remplirSelect
		
		//Remplir les select du formulaire en fonction de la réponse AJAX
		function remplirSelectArrivee (dataAjax) {
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
					$("#aeroportArrivee > option").remove();
			 
				 	// Mes aeroports
				 	i = 0;           
					for (key in reponse.aeroport) {	
			 			$("#aeroportArrivee").append(  '<option label="' + optionData['aeroport'][key] + '"' + 'value="' + key + '">'
										  				+ optionData['aeroport'][key]
										  			+ '</option>');
					    i++;
					    if (i >= 2) { 
							$("#aeroportArrivee:first").prepend( '<option label="Choisissez" value="-1" >-- Choississez --</option>');
							$("#aeroportArrivee option:first").attr ('selected', 'selected');
						}
					} //Eof:: for 'aeroport'
		    	} //Eof:: success
		    });  //Eof:: ajax 
		} //Eof:: fucntion remplirSelect
//////////////////////////////////////////////////////////////////////////////////////////////////////
 
 
//Sur fin du chargement du document
$(document).ready( function() {

//////////////////////////////////////////////////////////////////////////////////////////////////////

	// Sur changement de l'un des 'select pays'
		$("select.choixPaysDepart").change(function(){

			// Je recupère la valeur des sélections en cours
	        Vpays  = $(this).val();
		 	// Données à passer à la requête AJAX
		 	var dataAjax = { pays:Vpays };

			// Modification des 'select'
			remplirSelectDepart (dataAjax);
			
		}); //Eof:: sur changement de l'un des 'select pays'

		$("select.choixPaysArrivee").change(function(){

			// Je recupère la valeur des sélections en cours
	        Vpays  = $(this).val();
		 	// Données à passer à la requête AJAX
		 	var dataAjax = { pays:Vpays };

			// Modification des 'select'
			remplirSelectArrivee (dataAjax);
			
		}); //Eof:: sur changement de l'un des 'select pays'
//////////////////////////////////////////////////////////////////////////////////////////////////////

}); //Eof:: ready