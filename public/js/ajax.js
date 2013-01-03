 
//Remplir les select du formulaire en fonction de la réponse AJAX
function remplirSelect (dataAjax) {
    // Envoi requête AJAX
    $.ajax({
         type: "POST"
       , url: "../ajax/remplir" // controller : ajax , action : remplir
       , data : dataAjax
       , dataType: "json"
       , success: function(reponse){ // Sur Succès de la réponse AJAX

		 //    // Duplique ma réponse	
   // 		    var optionData = reponse;
 
	  //   	// Suppression des éléments de mes listes déroulantes
	  //  		$("#paysDepart > option").remove();
	  //  		$("#villeDepart > option").remove();
	  //  		$("#aeroportDepart > option").remove();
 
	  //  		// Ajoute les options pour chaque liste déroulante en fonction de la réponse
	  //  		// Mes pays    
	  //  		i = 0;           
	  //  		for (key in reponse.pays) {	
			// 	$("#id_pays").append(  '<option label="' + optionData['pays'][key] + '"' + 'value="'+ key + '">'
			// 								+ optionData['pays'][key]
			// 							+ '</option>');
			// 	i++;
				
			// 	if (i == 2) { 
			// 		$("#id_pays:first").prepend( '<option label="Choisissez" value="" >-- Choississez --</option>');
			// 		$("#id_pays option:first").attr ('selected', 'selected');
			// 	}
			// } //Eof:: for 'pays'
 
		 // 	// Mes villes
		 // 	i = 0;           
			// for (key in reponse.ville) {	
			// 	$("#id_ville").append(  '<option label="' + optionData['ville'][key]+ '"' + 'value="' + key + '">'
			// 					  			+ optionData['ville'][key]
			// 					  		+ '</option>');
			// 	i++;
			// 	if (i == 2) { 
			// 		$("#id_ville:first").prepend( '<option label="Choisissez" value="" >-- Choississez --</option>');
			// 		$("#id_ville option:first").attr ('selected', 'selected');
			// 	}
			// } //Eof:: for 'ville'
	 
		 // 	// Mes aeroports
		 // 	i = 0;           
			// for (key in reponse.aeroport) {	
	 	// 		$("#id_aeroport").append(  '<option label="' + optionData['aeroport'][key] + '"' + 'value="' + key + '">'
			// 					  				+ optionData['aeroport'][key]
			// 					  			+ '</option>');
			//     i++;
			//     if (i == 2) { 
			// 		$("#id_aeroport:first").prepend( '<option abel="Choisissez" value="" >-- Choississez --</option>');
			// 		$("#id_aeroport option:first").attr ('selected', 'selected');
			// 	}
			// } //Eof:: for 'aeroport'
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
	$("#reset").click (function() {
 
		// Aucun choix
	 	VpaysDepart = "";
	 	VvilleDepart = "";
	 	VaeroportDepart = "";
 
	 	// Données à passer à la requête AJAX
	 	var dataAjax = {  paysDepart:[VpaysDepart]
	 	                , villeDepart:[VvilleDepart]
	 					, aeroportDepart:[VaeroportDepart]
 	         	    };
 
		// Modification des 'select'
		remplirSelect (dataAjax);
 
	}); //Eof:: sur click btn 'reset'
}); //Eof:: ready