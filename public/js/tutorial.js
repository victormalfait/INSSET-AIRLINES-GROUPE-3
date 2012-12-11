$(function() {
    $(".button_pays").click(function() {		
    	var pays = $("input#pays").val();
    	    if (pays == "") {$("input#pays").focus();return false;}		
		$.ajax({
            type: "POST",
            url: "/strategie/nouveau",
            data: 'pays='+pays,
            success: function(id_pays) {
                $("#popup_ajouter_pays").hide(); 
                $('#paysDepart').append('<option label="'+id_pays+'" value="'+data+'">'+pays+'</option>');
            }
        });
        return false;});});

$(function() {
    $(".button_ville").click(function() {        
        var ville = $("input#ville").val();
            if (ville == "") {$("input#ville").focus();return false;} 
        var pays = $("select#selectpays").val();    
        var dataString = 'pays_ville='+pays+'&ville='+ville;
        $.ajax({
            type: "POST",
            url: "/strategie/nouveau",
            data: dataString,
            success: function(id_ville) {
                $("#popup_ajouter_ville").hide();
                $('#villeDepart').append('<option label="'+ville+'" value="'+id_ville+'">'+ville+'</option>');
            }
        });
        return false;});});




