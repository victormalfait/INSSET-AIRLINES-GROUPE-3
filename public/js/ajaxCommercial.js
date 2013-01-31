//Sur fin du chargement du document
$(document).ready( function() {

	// on cache les element qui ne sont pas encore utiliser
	$('#typePassager2').parent().hide();
	$('#typePassager3').parent().hide();
	$('#typePassager4').parent().hide();
	$('#typePassager5').parent().hide();
	$('#typePassager6').parent().hide();
	$('#typePassager7').parent().hide();
	$('#typePassager8').parent().hide();
	$('#typePassager9').parent().hide();
	$('#typePassager10').parent().hide();

//////////////////////////////////////////////////////////////////////////////////////////////////////
	$("input[name$='typeTrajet']").change(function(){

		var radio_value = $(this).val();

		if(radio_value=='2') {
		    $("#datepickerfin").parent().show();
		}else{
			$("#datepickerfin").parent().hide();
		}
	});

	$("select#nbrPassager").change(function(){

		var select_value = $(this).val();
		$('#typePassager2').parent().hide();
		$('#typePassager3').parent().hide();
		$('#typePassager4').parent().hide();
		$('#typePassager5').parent().hide();
		$('#typePassager6').parent().hide();
		$('#typePassager7').parent().hide();
		$('#typePassager8').parent().hide();
		$('#typePassager9').parent().hide();
		$('#typePassager10').parent().hide();

		if(select_value > 1){

			for(var i = 2 ; i <= select_value; i++){
				$('#typePassager'+i).parent().show();
			}

			for(var j = 10 ; j > select_value; j--){
				$('#typePassager'+j).parent().hide();
			}
		}
	});	
}); //Eof:: sur changement de l'un des 'select'
//////////////////////////////////////////////////////////////////////////////////////////////////////

function verif(){
    var volAller = $('input[type=radio][name=volAller]:checked').attr('value');
    var volRetour = $('input[type=radio][name=volRetour]:checked').attr('value');
    var aller = volAller.split('/'); 
    var retour = volRetour.split('/'); 
    if (aller[1] >= retour[1]) {
        alert('Votre vol retour se situe avant le vol aller.\nChoissez une autre combinaison de vol');
        return false;
    }else{
        return true;
    };
}

function verifCommercial(){
	var verif = true;
	var phrase = '';
    var aeroDep = $('select#aeroportDepart').val();
    var aeroRet = $('select#aeroportArrive').val();
    if(aeroDep == -1){
    	verif = false;

    };

    if(aeroRet == -1){
    	verif = false;
    	phrase = 'Vous n\'avez pas choisi d\'aeroport de départ'; 
    };

    if(aeroRet == aeroDep){
    	verif = false;
    	phrase = 'Vous n\'avez pas choisi d\'aeroport de d\'arrivé'; 
    };

    var dateAller = $('#datepickerdeb').val();
    var dateRetour = $('#datepickerfin').val();
    var aller = dateAller.split('-'); 
    var retour = dateRetour.split('-'); 
    var x = new Date();
	var y = x.setFullYear(aller[2],aller[1]-1,aller[0]);
	var z = x.setFullYear(retour[2],retour[1]-1,retour[0]);

	if(z < y){
		verif = false;
		phrase = 'Votre date de retour est avant votre date de départ'; 
	};

    if (verif) {
        return true;
    }else{
        alert(phrase);
        return false;
    };
}
