<?php

class FCommercial extends Zend_Form
{
	public function init()
	{
		//===============Parametre du formulaire
		$this->setMethod('post');
		$this->setAction('/strategie/index');
		$this->setAttrib('id', 'ConnexionNouveauVol');

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

		//===============Creation des element
		$tableVille = new TVille;
        $ville = $tableVille->fetchAll();
        $$tabVille = array();

        foreach ($ville as $v) {
        	$tabVille[$v['id']] = $v['nom'];
        }

		$eAeroportDepart = new Zend_Form_Element_Select('aeroportDepart');
		$eAeroportDepart->setLabel('De')
						->setRequired(true)
						->addValidator('notEmpty')
						->setMultiOptions($tabVille)
						->setDecorators($decorators);

		$eAeroportArrivee = new Zend_Form_Element_Select('aeroportArrivee');
		$eAeroportArrivee->setLabel('A')
						 ->setRequired(true)
						 ->addValidator('notEmpty')
						 ->setMultiOptions($tabVille)
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

		// on insere le decorateurde form au formulaire
		$this->setDecorators($decoratorsForm);

		$this->addDisplayGroup(array(
								'aeroportDepart',
								'aeroportArrivee'), 'un', array("legend" => ""));
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
}
