<?php

class MaintenanceController extends Zend_Controller_Action
{
	public function indexAction()
    {
    	
    	
    }
    public function ajouterAction()
    {

    }
    public function calendrierAction()
    {

        echo '
        <link rel="stylesheet" type="text/css" href="../css/menu.css" />
        <div id="menu" align="center">
            Ajouter un appareil :
            <br/><br/>
        ';



    	$m = (isset($_GET['m'])) ? $_GET['m'] : date("n");
    	$a = (isset($_GET['a'])) ? $_GET['a'] : date("Y");
    	$mnom = Array("","Janvier","Février","Mars" ,"Avril","Mai","Juin","Juillet",
    			"Août","Septembre","Octobre","Novembre","Décembre");
    	$dayone = date("w",mktime(1,1,1,$m,1,$a));
    	
    	if($dayone==0){
    		$dayone=7;
    	}
    	echo '<div id="corps"><div class="center">';
    	
    	for($i=1;$i<13;$i++)
    	{
    		echo"<a href='$i/$a'>&nbsp;&nbsp;".substr( $mnom[$i],0,1)."&nbsp;&nbsp;</a>";
    	}
    	
    	echo ' - <br/><br/></div><font size=4><div class="center">';
    	$mois=$m-1;
    	$an=$a;
    	if($mois==0) {
    		$mois=12;$an=$a-1;
    	}
    	echo"<a href='$mois/$an'><< </a>&nbsp;";
    	echo $mnom[$m]." ".$a;
    	$an=$a;
    	$mois=$m+1;
    	
    	if($mois==13) {
    		$mois=1;$an=$a+1;
    	}
    	
    	$jours_in_month=cal_days_in_month(CAL_GREGORIAN,$m,$a);
    	// calcul du nombe de semaine soit nb_jour ds le mois diviser par 7
    	//et on arrondit au superieur
    	$gg=$jours_in_month+$dayone-1;
    	$nb_semaine=ceil($gg/7);
    	$jours_a_afficher=$nb_semaine*7;
    	echo"&nbsp;<a href='$mois/$an'> >></a>"; // mois apres
    	echo '</font>';
    	echo '</div><table class="calendrier" cellspacing=0><tr class="paire">';
    	echo '<td width=170>Lundi</td><td width=170>Mardi</td><td width=170>Mercredi</td><td width=170>Jeudi</td><td width=170>Vendredi</td><td width=170>Samedi</td><td width=170>Dimanche</td>';
    	
    	for($i=1;$i<=$jours_a_afficher;$i++)
    	{
    		if($i%7 == 1)	  {
    			echo'</tr><tr>';
    		}
    		if($i<($jours_in_month+$dayone) && $i>=$dayone)
    		{
    			$a=$i-$dayone+1;
    			echo "<td width=170 > ".$a." </td>";
    		}
    		else
    		{
    			echo "<td  width=170 bgcolor=silver>&nbsp;</td>";
    		}
    	}
    	echo "</tr></table></div>";

        echo '</div>';
    	 
    }
    
}