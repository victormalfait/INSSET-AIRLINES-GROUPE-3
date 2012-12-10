$(function() {
    $(".button_pays").click(function() {		
    	var pays = $("input#pays").val();
    	    if (pays == "") {$("input#pays").focus();return false;}		
		$.ajax({
            type: "POST",
            url: "/strategie/nouveau",
            data: 'pays='+pays,
            success: function(data) {
                console.log(data);
                $("#popup_ajouter_pays").hide(); 
                $idselect.append('<option label="'+pays+'" value="data">France</option>')
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
            success: function() {
                $("#popup_ajouter_ville").hide(); 
            }
        });
        return false;});});




