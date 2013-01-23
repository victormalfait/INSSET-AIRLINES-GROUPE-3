<?php 
class time{
	function find_date_vol($timestamp, $jour) {
        $target = $timestamp;
        //on recupere le jour d'aujourd'hui
        $day = date('D',$target);
        switch(strtoupper($day)) {
            case "MON":$first = 0;
                break;
            case "TUE":$first = 1;
                break;
            case "WED":$first = 2;
                break;
            case "THU":$first = 3;
                break;
            case "FRI":$first = 4;
                break;
            case "SAT":$first = 5;
                break;
            case "SUN":$first = 6;
                break;
            default:$first = 0;
                break;
        }

        switch($jour) {
            case "Lundi":$second = 0;
                break;
            case "Mardi":$second = 1;
                break;
            case "Mercredi":$second =2;
                break;
            case "Jeudi":$second = 3;
                break;
            case "Vendredi":$second = 4;
                break;
            case "Samedi":$second = 5;
                break;
            case "Dimanche":$second = 6;
                break;
            default:$second = 0;
                break;
        }
        //nombre en timestamp d'une journée
        $one_day = 24*60*60;
        //nombre de jour d'ecart entre le jour d'aujourdhui et le jour du vol
        $day_left = $second - $first;
        //si la différence est negative on ajoute 7 
        if($day_left < 0)
            $day_left += 7;
        //on ajoute au timestamp d'aujourdhui le nombre de journee d'ecart
        $date = $target + ($one_day * $day_left);
        //on renvoi le timestamp
        return $date;
    }

    function timestamp_now(){
    	$mois = date("m", time());
        $jour = date("d", time());
        $an = date("Y", time());

        //on cree un timestamp de la date d'aujourd'hui
        $date_jour = mktime(0,0,0, $mois, $jour, $an);

        return $date_jour;
    }

    function timestamp_fly($date_vol,$heure_dep){
    	$minuteD = date("i", $heure_dep);
        $heureD = date("H", $heure_dep);
        $mois = date("m", $date_vol);
        $jour = date("d", $date_vol);
        $an = date("Y", $date_vol);

        $date_vol_dep = mktime($heureD,$minuteD,0, $mois, $jour, $an);

        return $date_vol_dep;
    }

    function timestamp_fly_arrived($date_vol_dep,$heure_arr){
    	$minuteA = date("i", $heure_arr);
        $heureA = date("H", $heure_arr);

        $heure = $heureA - $heureD;
        if($heure < 0)
            $heure += 24;
        $minute = $minuteA - $minuteD;
        if($heure < 0)
            $heure += 60;

        //on cree le timestamp de la date d'arrivee du vol
        $date_vol_arr = $date_vol_dep + $heure*60*60 + $minute*60;

        return $date_vol_arr;
    }
}