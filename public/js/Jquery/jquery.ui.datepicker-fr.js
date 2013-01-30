/* French initialisation for the jQuery UI date picker plugin. */
/* Written by Stéphane Nahmani (sholby@sholby.net). */
jQuery(function($) {
        $.datepicker.regional['fr'] = {
                closeText: 'Fermer', closeStatus: 'Fermer sans modifier',
                prevText: '&#x3c;Préc', prevStatus: 'Voir le mois précédent',
                nextText: 'Suiv&#x3e;', nextStatus: 'Voir le mois suivant',
                currentText: 'Courant', currentStatus: 'Voir le mois courant',
                monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin',
                'Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
                monthNamesShort: ['Jan','Fév','Mar','Avr','Mai','Jun',
                'Jul','Aoû','Sep','Oct','Nov','Déc'],
                dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
                dayNamesShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
                dayNamesMin: ['Di','Lu','Ma','Me','Je','Ve','Sa'],
                dateFormat: 'dd/mm/yyyy',
                firstDay: 1,
                isRTL: false,
                renderer: $.ui.datepicker.defaultRenderer,
                prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: 'Voir l\'année précédent',
                nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: 'Voir l\'année suivant',
                todayText: 'Aujourd\'hui', todayStatus: 'Voir aujourd\'hui',
                clearText: 'Effacer', clearStatus: 'Effacer la date sélectionnée',
                yearStatus: 'Voir une autre année', monthStatus: 'Voir un autre mois',
                weekText: 'Sm', weekStatus: 'Semaine de l\'année',
                dayStatus: '\'Choisir\' le DD d MM',
                defaultStatus: 'Choisir la date'
        };
        $.datepicker.setDefaults($.datepicker.regional['fr']);
});

