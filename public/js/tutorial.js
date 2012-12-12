jQuery(document).ready(function($) {         

    //////////////// AJAX PAYS////////////////////////
        $(".button_pays").click(function() {        
            var pays = $("input #nouveauPays").val();
            
            if (pays == "") {
                $("input #nouveauPays").focus();
                return false;
            }       
            
            $.ajax({
                type: "POST",
                url: "/strategie/nouveaupays",
                data: 'nouveauPays='+pays,
                
                success: function(id_pays) {
                    $("#popup_ajouterPays").hide(); 
                    $('#paysDepart').append('<option label="'+pays+'" value="'+id_pays+'">'+pays+'</option>');
                    $('#paysArrivee').append('<option label="'+pays+'" value="'+id_pays+'">'+pays+'</option>');
                }
            });

            return false;
        });

        $(".closePays").click(function(){
            $("#popup_ajouterPays").slideUp('slow');
        })
    //////////////// AJAX PAYS END HERE ////////////////////////


    //////////////// AJAX VILLE ////////////////////////
        $(".button_ville").click(function() {        
            var pays = $("select#pays_ville").val();    
            var ville = $("input#nouveauVille").val();
            
            if (ville == "") {
                $("input #nouveauVille").focus();
                return false;
            } 

            $.ajax({
                type: "POST",
                url: "/strategie/nouvelleville",
                data: 'pays_ville='+pays+'&nouveauVille='+ville,
                
                success: function(id_ville) {
                    $("#popup_ajouterVille").hide();
                    $('#villeDepart').append('<option label="'+ville+'" value="'+id_ville+'">'+ville+'</option>');
                    $('#villeArrivee').append('<option label="'+ville+'" value="'+id_ville+'">'+ville+'</option>');
                }
            });

            return false;
        });

        $(".closeVille").click(function(){
            $("#popup_ajouterVille").slideUp('slow');
        })
    //////////////// AJAX VILLE END HERE ////////////////////////


    //////////////// AJAX AEROPORT ////////////////////////
        $(".button_aeroport").click(function() {        
            var ville = $("select#ville_aeroport").val();
            var aeroport = $("input#nouvelAeroport").val();
            var trigramme = $("input#trigramme").val();
            var longueurpiste = $("input#longueurpiste").val();
            var dataString = '&ville_aeroport='+ville+'&nouvelAeroport='+aeroport+'&trigramme='+trigramme+'&longueurpiste='+longueurpiste;

            if (longueurpiste == "") {
                $("input #longueurpiste").focus();
                return false;
            }

            if (trigramme == "") {
                $("input #trigramme").focus();
                return false;
            }

            if (aeroport == "") {
                $("input #nouvelAeroport").focus();
                return false;
            }
            
            $.ajax({
                type: "POST",
                url: "/strategie/nouvelaeroport",
                data: dataString,
                
                success: function() {
                    $("#popup_ajouterAeroport").hide();
                    $('#aeroportDepart').append('<option label="'+ville+'" value="'+trigramme+'">'+aeroport+' - '+trigramme+'</option>');
                   $('#aeroportArrivee').append('<option label="'+ville+'" value="'+trigramme+'">'+aeroport+' - '+trigramme+'</option>');
                }
            });

            return false;
        });

        $(".closeAeroport").click(function(){
            $("#popup_ajouterAeroport").slideUp('slow');
        })
    //////////////// AJAX AEROPORT END HERE ////////////////////////
}); //document.ready function ends here




