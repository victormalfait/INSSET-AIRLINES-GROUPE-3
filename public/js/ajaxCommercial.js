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
