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
                $('#paysArrivee').append('<option label="'+id_pays+'" value="'+data+'">'+pays+'</option>');
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
                $('#villeArrivee').append('<option label="'+ville+'" value="'+id_ville+'">'+ville+'</option>');
            }
        });
        return false;});});

$(function() {
    $(".button_aeroport").click(function() {        
        var ville = $("select#select_ville_aeroport").val();
        var aeroport = $("input#aeroport").val();
            if (aeroport == "") {$("input#aeroport").focus();return false;} 
        var trigramme = $("input#trigramme").val();
            if (trigramme == "") {$("input#trigramme").focus();return false;}    
        var dataString = '&ville_aeroport='+ville+'&aeroport='+aeroport+'&trigramme='+trigramme;
        $.ajax({
            type: "POST",
            url: "/strategie/nouveau",
            data: dataString,
            success: function() {
                $("#popup_ajouter_aeroport").hide();
                $('#aeroportDepart').append('<option label="'+ville+'" value="'+trigramme+'">'+aeroport+' - '+trigramme+'</option>');
               $('#aeroportArrivee').append('<option label="'+ville+'" value="'+trigramme+'">'+aeroport+' - '+trigramme+'</option>');
            }
        });
        return false;});});




