<?php

class FClient extends Zend_Form
{
	private $id;
 
	public function init()
	{
		//===============Parametre du formulaire
			$this->setName('client');
			$this->setMethod('post');
			$this->setAction('');
			$this->setAttrib('id', 'FClient');


		//=============== creation des decorateurs
			// Descativer les decorateurs par defaut
			$this->clearDecorators();

			// decorateur d'element bouton
			$decoratorsBouton = array(
			    'ViewHelper',
			    'Errors',
			    array('Label', array('class' => 'label submit')),
			    array('HtmlTag', array('tag' => 'li'))
			);

			//decorateur de formulaire
			$decoratorsForm = array(
				'FormElements',
			    array('Errors', array('class' => "error")),
				array('HtmlTag', array('tag' => 'ul')),
				array(
					array('DivTag' => 'HtmlTag'),
					array('tag' => 'div')
				),
				'Form'
			);

			// on insere le decorateur de form au formulaire
			$this->setDecorators($decoratorsForm);


			$eSubmit = new Zend_Form_Element_Submit('BTNReserver');
			$eSubmit ->setAttrib('id', 'BTNReserver')
					 ->setAttrib('class', 'bgRedBtn	')
					 ->setLabel('Réserver')
					 ->setDecorators($decoratorsBouton);


			$this->addElement($eSubmit);
	}


	/**
	 * @param $id_destination the $id_destination to set
	 */
	public function setId($id) {

		//=============== Creation du decorateur d'element
			$decorators = array(
			    array('ViewHelper'),
			    array('Errors'),
			    array('Label', array('class' => 'label')),
			    array('HtmlTag', array('tag' => 'li'))
			);

			$decoratorsBirth = array(
			    array('ViewHelper'),
			    array('Errors'),
			    array('Label', array('class' => 'label')),
			    array('HtmlTag', array('tag' => 'div'))
			);


		//=============== Creation des element
			$tabCivilite = array('M.' => 'M.', 'Mme.' => 'Mme.');
			$eCivilite = new Zend_Form_Element_Select('civilite'.$id);
			$eCivilite 	->setLabel('Civilite')
					   	->setMultiOptions($tabCivilite)
	        		   	->addFilter('StripTags')
	        		   	->addFilter('StringTrim')
						->setDecorators($decorators);


			$eNom = new Zend_Form_Element_Text('nom'.$id);
			$eNom 	->setLabel('Nom')
				  	->setAttrib('required', 'required')
				   	->setAttrib('size', '16')
				  	->addValidator('notEmpty')
					->setDecorators($decorators);

			$ePrenom = new Zend_Form_Element_Text('prenom'.$id);
			$ePrenom ->setLabel('Prénom')
				   	 ->setAttrib('size', '14')
				   	 ->setAttrib('required', 'required')
				     ->addValidator('notEmpty')
					 ->setDecorators($decorators);

			$eJour = new Zend_Form_Element_Text('jour'.$id);
			$eJour 	->setLabel('Naissance')
				   	->setAttrib('required', 'required')
				   	->setAttrib('maxlength', '2')
				   	->setAttrib('class', 'jour')
				   	->addValidator('notEmpty')
					->setDecorators($decoratorsBirth);

			$tabMois = array('-1'=>'mois', '1'=>'Janvier', '2'=>'Février', '3'=>'Mars', '4'=>'Avril', '5'=>'Mai', '6'=>'Juin', '7'=>'Juillet', '8'=>'Aout', '9'=>'Septembre', '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre');
			$eMois = new Zend_Form_Element_Select('mois'.$id);
			$eMois 	->setMultiOptions($tabMois)
				   	->setAttrib('class', 'mois')
	        	   	->addFilter('StripTags')
	        	   	->addFilter('StringTrim')
					->setDecorators($decoratorsBirth);

			$eAnnee = new Zend_Form_Element_Text('annee'.$id);
			$eAnnee ->setAttrib('required', 'required')
				   	->setAttrib('maxlength', '4')
				   	->setAttrib('class', 'annee')
				    ->addValidator('notEmpty')
					->setDecorators($decoratorsBirth);

			$eMail = new Zend_Form_Element_Text('email'.$id);
			$eMail 	->setLabel('Adresse email')
				  	->setAttrib('required', 'required')
				   	->addValidator('notEmpty')
					->setDecorators($decorators);

			$eConfMail = new Zend_Form_Element_Text('confemail'.$id);
			$eConfMail 	->setLabel('Confirmation email')
				   	   	->setAttrib('required', 'required')
				       	->addValidator('notEmpty')
						->setDecorators($decorators);

			$tabRepas = array('-1' => 'Choisissez'
							, '1'  => 'Normal'
							, '2'  => 'Plateau bébé'
							, '3'  => 'Hallal'
							, '4'  => 'Casher'
							, '5'  => 'Végétarien');

			$eRepasRestriction = new Zend_Form_Element_Select('repasrestriction'.$id) ;
			$eRepasRestriction 	->setLabel('Réserverepas à bord')
								->setMultiOptions($tabRepas)
				   	   			->setAttrib('required', 'required')
				   				->setAttrib('class', 'repas')
			       				->addValidator('notEmpty')
								->setDecorators($decorators);


			$elements = array($eCivilite
							, $eNom
							, $ePrenom
							, $eJour
							, $eMois
							, $eAnnee
							, $eRepasRestriction
							, $eMail
							, $eConfMail);

			$this->addElements($elements);
			$this->addDisplayGroup(array(
									'civilite'.$id,
									'nom'.$id,
									'prenom'.$id,
									'jour'.$id,
									'mois'.$id,
									'annee'.$id,
									'repasrestriction'.$id,
									'email'.$id,
									'confemail'.$id), 'trois'.$id, array("legend" => "Passager N° ".$id));

	}

	/**
	 * @return the $id_destination
	 */
	public function getId() {
		return $this->id;
	}
}