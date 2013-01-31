jQuery(document).ready(function($) {         
    //////////////// AJAX PAYS////////////////////////
        // au click sur le bouton d'ajout de pays...
        $(".button_pays").click(function() {        
            // ... on récupère la valeur du nom du pays ajouter
            var pays = $("input#nouveauPays").val();
            
            // ... si cette valeur est vide
            if (pays == "") {
                // on renvoi le focus sur le champ de texte
                $("input #nouveauPays").focus();
            }
            else { // sinon (valeur nom vide)
                // on execute la requete ajax
                $.ajax({
                    type: "POST",
                    url: "/strategie/nouveaupays",
                    data: 'nouveauPays='+pays,
                    
                    success: function(id_pays) {
                        $("#popup_ajouterPays").hide(); 
                        $('#paysDepart').append('<option label="'+pays+'" value="'+id_pays+'">'+pays+'</option>');
                        $('#paysArrivee').append('<option label="'+pays+'" value="'+id_pays+'">'+pays+'</option>');
                        $('#pays_ville').append('<option label="'+pays+'" value="'+id_pays+'">'+pays+'</option>');
                    }
                });
            }
            
            return false;
        });

        // au click sur le bouton close pays
        $(".closePays").click(function(){
            // on ferme le popup ajout pays
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
                    $('#ville_aeroport').append('<option label="'+ville+'" value="'+id_ville+'">'+ville+'</option>');
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
            var lattitude = $("input#lattitude").val();
            var longitude = $("input#longitude").val();
            var dataString = '&ville_aeroport='+ville+'&nouvelAeroport='+aeroport+'&trigramme='+trigramme+'&longueurpiste='+longueurpiste+'&longitude='+longitude+'&lattitude='+lattitude;;

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
                    $('#aeroportDepart').append('<option value="'+trigramme+'" label="'+ville+'">'+ville+' - '+aeroport+'</option>');
                    $('#aeroportArrivee').append('<option value="'+trigramme+'" label="'+ville+'">'+ville+' - '+aeroport+'</option>');
                }
            });

            return false;
        });

        $(".closeAeroport").click(function(){
            $("#popup_ajouterAeroport").slideUp('slow');
        })
    //////////////// AJAX AEROPORT END HERE ////////////////////////
}); //document.ready function ends here




