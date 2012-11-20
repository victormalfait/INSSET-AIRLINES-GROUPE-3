<?php

class FCommercial extends Zend_Form
{
	public function init()
	{
		//===============Parametre du formulaire
		$this->setMethod('post');
		$this->setAction('/strategie/index');
		$this->setAttrib('id', 'ConnexionNouveauVol');

		//===============Creation des element
		$tabVille = array('Paris','Madrid','Rome','New-York','Washington');

		$eAeroportDepart = new Zend_Form_Element_Select('aeroportDepart');
		$eAeroportDepart->setLabel('De : ')
						->setRequired(true)
						->addValidator('notEmpty')
						->setMultiOptions($tabVille);

		$eAeroportArrivee = new Zend_Form_Element_Select('aeroportArrivee');
		$eAeroportArrivee->setLabel('A : ')
						 ->setRequired(true)
						 ->addValidator('notEmpty')
						 ->setMultiOptions($tabVille);

		$eTypeTrajet = new Zend_Form_Element_Radio('typeTrajet');
		$eTypeTrajet->setMultiOptions(array('1'=>'Aller simple','2'=>'Aller retour')  );
        $eTypeTrajet->setOptions(array('separator'=>''));

        $eDatedeb = new Zend_Form_Element_Text('datepickerdeb');
		$eDatedeb->setLabel('Date de début');
		$eDatedeb->setRequired(true);

		$eDatefin = new Zend_Form_Element_Text('datepickerfin');
		$eDatefin->setLabel('Date de fin');
		$eDatefin->setRequired(true);

		$nbr=array();
		for($i=1;$i<10;$i++)
			$nbr[$i]=$i;

		$eNbrPassager = new Zend_Form_Element_Select('nbrPassager');
		$eNbrPassager->setLabel('Nombre de passagers : ')
						 ->setRequired(true)
						 ->addValidator('notEmpty')
						 ->setMultiOptions($nbr);

		$typePassager = array('Adultes (25-64 ans)','Enfants (2-11 ans)','Séniors (65 ans et plus)');

		$eTypePassager = new Zend_Form_Element_Select('typePassager');
		$eTypePassager->setLabel('Nombre de passagers : ')
						 ->setRequired(true)
						 ->addValidator('notEmpty')
						 ->setMultiOptions($typePassager);

		$classe = array('Economique','Première','Affaire');

		$eClasse = new Zend_Form_Element_Select('classe');
		$eClasse->setLabel('Classe : ')
						 ->setRequired(true)
						 ->addValidator('notEmpty')
						 ->setMultiOptions($classe);

		$eSubmit = new Zend_Form_Element_Submit('Rechercher');

		$this->addElement($eAeroportDepart);
		$this->addElement($eAeroportArrivee);
		$this->addElement($eTypeTrajet);
		$this->addElement($eDatedeb);
		$this->addElement($eDatefin);
		$this->addElement($eNbrPassager);
		$this->addElement($eTypePassager);
		$this->addElement($eClasse);
		$this->addElement($eSubmit);

		// Ajout des éléments au formulaire
		// $elements = array ( $ePaysDepart, $eAeroportDepart, $eDepartH, $eDepartM, $ePaysArrivee, $eAeroportArrivee, $eArriveeH, $eArriveeM, $ePeriodicite, $eSubmit );
		// $this->addElements ( $elements );

		// $this->addDisplayGroup(array(
		// 	'ePaysDepart',
		// 	'eAeroportDepart',
		// 	'eDepartH',
		// 	'eDepartM'
  //           ),'Depart',array('legend' => 'Départ'));
        
  //       $Depart = $this->getDisplayGroup('Depart');
  //       $Depart->setDecorators(array(
        
  //                   'FormElements',
  //                   'Fieldset',
  //                   array('HtmlTag',array('tag'=>'div','style'=>'width:50%;;float:left;'))
  //       ));

  //       $this->addDisplayGroup(array(
		// 	'ePaysArrivee',
		// 	'eAeroportArrivee',
		// 	'eArriveeH',
		// 	'eArriveeM'
  //           ),'Arrivee',array('legend' => 'Arrivée'));
        
  //       $Arrivee = $this->getDisplayGroup('Arrivee');
  //       $Arrivee->setDecorators(array(
        
  //                   'FormElements',
  //                   'Fieldset',
  //                   array('HtmlTag',array('tag'=>'div','style'=>'width:50%;;float:left;'))
  //       ));

  //       $this->addDisplayGroup(array(
		// 	'periodicite'
  //           ),'periodicite',array('legend' => 'Périodicité'));
        
  //       $periodicite = $this->getDisplayGroup('periodicite');
  //       $periodicite->setDecorators(array(
        
  //                   'FormElements',
  //                   'Fieldset',
  //                   array('HtmlTag',array('tag'=>'div','style'=>'width:50%;;float:left;'))
  //       ));

		// $this->addDisplayGroup(array(
		// 	'Enregistrer'
  //           ),'Enregistrer',array('legend' => 'Terminer'));
        
  //       $Enregistrer = $this->getDisplayGroup('Enregistrer');
  //       $Enregistrer->setDecorators(array(
        
  //                   'FormElements',
  //                   'Fieldset',
  //                   array('HtmlTag',array('tag'=>'div','style'=>'width:50%;;float:left;'))
  //       ));

	}
}
