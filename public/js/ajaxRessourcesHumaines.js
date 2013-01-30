jQuery(document).ready(function($) {         
    //////////////// AJAX PAYS////////////////////////
    // au click sur le bouton d'ajout de pays...
    $("#BTNCreerBrevet").click(function() {        
        // ... on récupère la valeur du nom du pays ajouter
        var duree = $("select#duree").val();
        var nom = $("input#nomBrevet").val();
        // ... si cette valeur est vide
        if (nom == "") {
            // on renvoi le focus sur le champ de texte
            $("input#nomBrevet").focus();
        }
        else { // sinon (valeur nom vide)
            // on execute la requete ajax
            $.ajax({
                type: "POST",
                url: "/ressourcehumaine/creerbrevet",
                data: 'nomBrevet='+nom+'&duree='+duree,
                
                success: function(id_brevet) {
                    alert(id_brevet);
                    $("#popup_creerBrevet").hide(); 
                    $('#brevet').append('<option label="'+nom+'" value="'+id_brevet+'">'+nom+'</option>');
                }
            });
        }
            
        return false;
    });
}); //document.ready function ends here