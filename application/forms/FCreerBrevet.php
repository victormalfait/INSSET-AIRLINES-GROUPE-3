<?php

class FCreerBrevet extends Zend_Form
{
 
	public function init()
	{
	//===============Parametre du formulaire
		$this->setName('creerbrevet');
		$this->setMethod('post');
		$this->setAction('');
		$this->setAttrib('id', 'FCreerBrevet');


	//=============== creation des decorateurs
		// Descativer les decorateurs par defaut
		$this->clearDecorators();

		$decorators = array(
		    array('ViewHelper'),
		    array('Errors'),
		    array('Label', array('class' => 'label')),
		    array('HtmlTag', array('tag' => 'li'))
		);

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


	//=============== Creation des element
		$eNomBrevet = new Zend_Form_Element_Text('nomBrevet');
		$eNomBrevet	->setLabel('Nom du brevet')
        			->addFilter('StripTags')
        			->addFilter('StringTrim')
        			->addValidator('notEmpty')
					->setAttrib('required', 'required')
		            ->setDecorators($decorators);

		$dureeTab = array();
		for ($i=1; $i <=10 ; $i++) { 
			$dureeTab[$i]=$i;
		}


		$eDuree = new Zend_Form_Element_Select('duree');
		$eDuree ->setLabel('Duree de validation')
				->setMultiOptions($dureeTab)
				->setDecorators($decorators);

		$eSubmit = new Zend_Form_Element_Submit('BTNCreerBrevet');
		$eSubmit 	->setAttrib('id', 'BTNCreerBrevet')
					->setLabel('Creer brevet')
					->setDecorators($decoratorsBouton);

		$eFermer = new Zend_Form_Element_Reset('fermer');
		$eFermer 	->setLabel('Fermer')
					->setAttrib('id', 'fermerbutton')
					->setAttrib('class', 'close')
					->setDecorators($decoratorsBouton);

		$elements = array($eNomBrevet, $eDuree, $eSubmit, $eFermer);
		$this->addElements($elements);

	}

}