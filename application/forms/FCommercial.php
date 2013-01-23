<?php

class FCommercial extends Zend_Form
{
	public function init()
	{
	
	//===============Parametre du formulaire
		$this->setMethod('post');
		$this->setAction('/commercial/recherche');
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
						->setMultiOptions($this->listVille())
						->setDecorators($decorators);

		$eAeroportArrivee = new Zend_Form_Element_Select('aeroportArrive');
		$eAeroportArrivee->setLabel('A')
						 ->setRequired(true)
						 ->addValidator('notEmpty')
						 ->setMultiOptions($this->listVille())
						 ->setDecorators($decorators);

		$eTypeTrajet = new Zend_Form_Element_Radio('typeTrajet');
		$eTypeTrajet->setMultiOptions(array('1'=>'Aller simple','2'=>'Aller retour')  )
        			->setOptions(array('separator'=>' '))
        			->setValue('2')
        			->setDecorators($decorators);

        $eDatedeb = new Zend_Form_Element_Text('datepickerdeb');
		$eDatedeb	->setLabel('Date aller')
					->setRequired(true)
					->setDecorators($decorators);

		$eDatefin = new Zend_Form_Element_Text('datepickerfin');
		$eDatefin	->setLabel('Date retour')
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

		$typePassager = array('1' => 'Adultes (25-64 ans)','0.5' => 'Enfants (2-11 ans)','0.75' => 'Séniors (65 ans et plus)');

		$eTypePassager = new Zend_Form_Element_Select('typePassager');
		$eTypePassager	->setLabel('Passager N° 1 : ')
						->setMultiOptions($typePassager)
						->setDecorators($decorators);

		$eTypePassager2 = new Zend_Form_Element_Select('typePassager2');
		$eTypePassager2	->setMultiOptions($typePassager)
						->setLabel('Passager N° 2 : ')
						->setDecorators($decorators);

		$eTypePassager3 = new Zend_Form_Element_Select('typePassager3');
		$eTypePassager3	->setMultiOptions($typePassager)
						->setLabel('Passager N° 3 : ')
						->setDecorators($decorators);

		$eTypePassager4 = new Zend_Form_Element_Select('typePassager4');
		$eTypePassager4	->setMultiOptions($typePassager)
						->setLabel('Passager N° 4 : ')
						->setDecorators($decorators);

		$eTypePassager5 = new Zend_Form_Element_Select('typePassager5');
		$eTypePassager5	->setMultiOptions($typePassager)
						->setLabel('Passager N° 5 : ')
						->setDecorators($decorators);

		$eTypePassager6 = new Zend_Form_Element_Select('typePassager6');
		$eTypePassager6	->setMultiOptions($typePassager)
						->setLabel('Passager N° 6 : ')
						->setDecorators($decorators);

		$eTypePassager7 = new Zend_Form_Element_Select('typePassager7');
		$eTypePassager7	->setMultiOptions($typePassager)
						->setLabel('Passager N° 7 : ')
						->setDecorators($decorators);

		$eTypePassager8 = new Zend_Form_Element_Select('typePassager8');
		$eTypePassager8	->setMultiOptions($typePassager)
						->setLabel('Passager N° 8 : ')
						->setDecorators($decorators);

		$eTypePassager9 = new Zend_Form_Element_Select('typePassager9');
		$eTypePassager9	->setMultiOptions($typePassager)
						->setLabel('Passager N° 9 : ')
						->setDecorators($decorators);

		$eTypePassager10 = new Zend_Form_Element_Select('typePassager10');
		$eTypePassager10->setMultiOptions($typePassager)
						->setLabel('Passager N° 10 : ')
						->setDecorators($decorators);

		$classe = array('1'=>'Economique','2.5'=>'Première','2'=>'Affaire');

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

		$elements = array($eAeroportDepart, $eAeroportArrivee, $eTypeTrajet, $eDatedeb, $eDatefin, $eNbrPassager, $eTypePassager, $eTypePassager2, $eTypePassager3, $eTypePassager4, $eTypePassager5, $eTypePassager6, $eTypePassager7, $eTypePassager8, $eTypePassager9, $eTypePassager10, $eClasse, $eSubmit);
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
								'typePassager',
								'typePassager2',
								'typePassager3',
								'typePassager4',
								'typePassager5',
								'typePassager6',
								'typePassager7',
								'typePassager8',
								'typePassager9',
								'typePassager10',
								), 'quatre', array("legend" => ""));
		$this->addDisplayGroup(array(
								'classe'), 'cinq', array("legend" => ""));
		$this->addDisplayGroup(array(
								'reserver'), 'six', array("legend" => ""));

	}

	/**
     * Liste des Pays
     */
    private function listVille () {
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
