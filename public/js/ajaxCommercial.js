//Sur fin du chargement du document
$(document).ready( function() {

	// on cache les element qui ne sont pas encore utiliser
	$('#datepickerfin').parent().hide();
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
	$("input[name$='typeTrajet']").click(function(){

		var radio_value = $(this).val();

		if(radio_value=='2') {
		    $("#datepickerfin").parent().show();
		}else{
			$("#datepickerfin").parent().hide();
		}
	});

	$("select#nbrPassager").change(function(){

		var select_value = $(this).val();

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