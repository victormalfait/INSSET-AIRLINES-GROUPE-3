//////////////////////////////////////////////////////////////////////////////////////////////////////
		//Remplir les select du formulaire en fonction de la réponse AJAX
		function remplirSelectPilote (dataAjax) {
		    // Envoi requête AJAX
		    $.ajax({
		         type: "POST"
		       , url: "../../../ajax/piloteplanning" // controller : ajax , action : remplir
		       , data : dataAjax
		       , dataType: "json"
		       , success: function(reponse){ // Sur Succès de la réponse AJAX

				    // Duplique ma réponse	
		   		    var optionData = reponse;
					// Suppression des éléments de mes listes déroulantes
					$("#pilote > option").remove();
			 		
				 	// Mes aeroports
				 	i = 0;           
					for (key in reponse.pilote) {	
			 			$("#pilote").append(  '<option label="' + optionData['pilote'][key] + '"' + 'value="' + key + '">' + optionData['pilote'][key] + '</option>');
					} //Eof:: for 'aeroport'
					$("#pilote:first").prepend( '<option label="Choisissez" value="-1" >-- Choississez --</option>');
					$("#pilote option:first").attr ('selected', 'selected');
		    	} //Eof:: success
		    });  //Eof:: ajax 
		} //Eof:: fucntion remplirSelect
//////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////
		//Remplir les select du formulaire en fonction de la réponse AJAX
		function remplirSelectCoPilote (dataAjax) {
		    // Envoi requête AJAX
		    $.ajax({
		         type: "POST"
		       , url: "../../../ajax/copiloteplanning" // controller : ajax , action : remplir
		       , data : dataAjax
		       , dataType: "json"
		       , success: function(reponse){ // Sur Succès de la réponse AJAX

				    // Duplique ma réponse	
		   		    var optionData = reponse;
					// Suppression des éléments de mes listes déroulantes
					$("#copilote > option").remove();
			 		
				 	// Mes aeroports
				 	i = 0;           
					for (key in reponse.copilote) {	
			 			$("#copilote").append(  '<option label="' + optionData['copilote'][key] + '"' + 'value="' + key + '">' + optionData['copilote'][key] + '</option>');
					} //Eof:: for 'aeroport'
					$("#copilote:first").prepend( '<option label="Choisissez" value="-1" >-- Choississez --</option>');
					$("#copilote option:first").attr ('selected', 'selected');
		    	} //Eof:: success
		    });  //Eof:: ajax 
		} //Eof:: fucntion remplirSelect
//////////////////////////////////////////////////////////////////////////////////////////////////////
 
 
//Sur fin du chargement du document
$(document).ready( function() {

//////////////////////////////////////////////////////////////////////////////////////////////////////

	// Sur changement de l'un des 'select pays'
		$("select#avion").change(function(){

			// Je recupère la valeur des sélections en cours
	        Vpilote  = $(this).val();
		 	// Données à passer à la requête AJAX
		 	var dataAjax = { immatriculation:Vpilote };

			// Modification des 'select'
			remplirSelectPilote (dataAjax);
			
		}); //Eof:: sur changement de l'un des 'select pays'
//////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////

	// Sur changement de l'un des 'select pays'
		$("select#pilote").change(function(){

			// Je recupère la valeur des sélections en cours
	        Vpilote  = $(this).val();
	        Vimmatriculation = $('select#avion').val()
		 	// Données à passer à la requête AJAX
		 	var dataAjax = { id_pilote:Vpilote, immatriculation:Vimmatriculation };

			// Modification des 'select'
			remplirSelectCoPilote (dataAjax);
			
		}); //Eof:: sur changement de l'un des 'select pays'
//////////////////////////////////////////////////////////////////////////////////////////////////////

		$("#fermerbutton").live('click', function () {
			location.reload();
		});

}); //Eof:: ready