<?php

class FCommercial extends Zend_Form
{
	public function init()
	{
	
	//===============Parametre du formulaire
		$this->setMethod('post');
		$this->setAction('');
		$this->setAttrib('id', 'FCommercial');

	//=============== creation des decorateurs
		// Descativation des decorateurs par defaut
		$this->clearDecorators();

		//decorateur d'element
		$decorators = array(
		    'ViewHelper',
		    'Errors',
		    array('Label', array('class' => 'label')),
		    array('HtmlTag', array('tag' => 'li'))
		);

		//decorateur de formulaire
		$decoratorsForm = array(
			'FormElements',
			array('HtmlTag', array('tag' => 'ul')),
			array(
				array('DivTag' => 'HtmlTag'),
				array('tag' => 'div')
			),
			'Form'
		);

		// on insere le decorateurde form au formulaire
		$this->setDecorators($decoratorsForm);

	//===============Creation des element

		$eAeroportDepart = new Zend_Form_Element_Select('aeroportDepart');
		$eAeroportDepart->setLabel('De')
						->setRequired(true)
						->addValidator('notEmpty')
						->setMultiOptions($this->listPays())
						->setDecorators($decorators);

		$eAeroportArrivee = new Zend_Form_Element_Select('aeroportArrive');
		$eAeroportArrivee->setLabel('A')
						 ->setRequired(true)
						 ->addValidator('notEmpty')
						 ->setMultiOptions($this->listPays())
						 ->setDecorators($decorators);

		$eTypeTrajet = new Zend_Form_Element_Radio('typeTrajet');
		$eTypeTrajet->setMultiOptions(array('1'=>'Aller simple','2'=>'Aller retour')  )
        			->setOptions(array('separator'=>''))
        			->setDecorators($decorators);

        $eDatedeb = new Zend_Form_Element_Text('datepickerdeb');
		$eDatedeb	->setLabel('Date aller')
					->setRequired(true)
					->setDecorators($decorators);

		$eDatefin = new Zend_Form_Element_Text('datepickerfin');
		$eDatefin	->setLabel('Date retour')
					->setRequired(true)
					->setDecorators($decorators);

		$nbr=array();
		for($i=1;$i<10;$i++)
			$nbr[$i]=$i;

		$eNbrPassager = new Zend_Form_Element_Select('nbrPassager');
		$eNbrPassager	->setLabel('Nombre de passagers : ')
						->setRequired(true)
						->addValidator('notEmpty')
						->setMultiOptions($nbr)
						->setDecorators($decorators);

		$typePassager = array('Adultes (25-64 ans)','Enfants (2-11 ans)','Séniors (65 ans et plus)');

		$eTypePassager = new Zend_Form_Element_Select('typePassager');
		$eTypePassager	->setLabel('Nombre de passagers : ')
						->setRequired(true)
						->addValidator('notEmpty')
						->setMultiOptions($typePassager)
						->setDecorators($decorators);

		$classe = array('Economique','Première','Affaire');

		$eClasse = new Zend_Form_Element_Select('classe');
		$eClasse	->setLabel('Classe : ')
					->setRequired(true)
					->addValidator('notEmpty')
					->setMultiOptions($classe)
					->setDecorators($decorators);

		$eSubmit = new Zend_Form_Element_Submit('reserver');
		$eSubmit 	->setAttrib('id', 'SBTReserver')
					->setAttrib('class', 'bgRedBtn')
					->setLabel('Réserver');

		$elements = array($eAeroportDepart, $eAeroportArrivee, $eTypeTrajet, $eDatedeb, $eDatefin, $eNbrPassager, $eTypePassager, $eClasse, $eSubmit);
		$this->addElements($elements);


	//=============== creation des groupes de formulaire
		$this->addDisplayGroup(array(
								'aeroportDepart',
								'aeroportArrive'), 'un', array("legend" => ""));
		$this->addDisplayGroup(array(
								'typeTrajet'), 'deux', array("legend" => ""));
		$this->addDisplayGroup(array(
								'datepickerdeb',
								'datepickerfin'), 'trois', array("legend" => ""));
		$this->addDisplayGroup(array(
								'nbrPassager',
								'typePassager'), 'quatre', array("legend" => ""));
		$this->addDisplayGroup(array(
								'classe'), 'cinq', array("legend" => ""));
		$this->addDisplayGroup(array(
								'reserver'), 'six', array("legend" => ""));

	}

	/**
     * Liste des Pays
     */
    private function listPays () {
		// on charge le model
		$tableVille = new TVille;
		// on recupere tout les pays
        $reqVille = $tableVille	->select()
    							->order("nom_ville");

	    $ville = $tableVille->fetchAll($reqVille);

        // on instancie le resultat en tableau de ville
        $villeTab = array();

        $villeTab["-1"] = "-- Choisissez --"; 
        foreach ($ville as $v) {
        	$villeTab[$v->id_ville] = utf8_encode($v->nom_ville);
        }
 
        return $villeTab;
    }
}
