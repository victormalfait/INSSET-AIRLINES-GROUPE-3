<?php

class FNouvelleMaintenance extends Zend_Form {
  
  public function init() {
       $this->setName ( 'NouvelleMaintenance' );
       $this->setMethod('post');
       $this->setAction('');
       $this->setAttrib('id', 'FNouvelleMaintenance');

    $this->clearDecorators();

    //decorateur d'element
    $decorators = array(
        'ViewHelper',
        'Errors',
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
      array('HtmlTag', array('tag' => 'ul')),
      array(
        array('DivTag' => 'HtmlTag'),
        array('tag' => 'div')
      ),
      'Form'
    );

    // on insere le decorateur de form au formulaire
    $this->setDecorators($decoratorsForm);


	$Time_Revision = new Zend_Form_Element_Select('Time_Revision');
	$Time_Revision	->setLabel('Type : ')
			->setRequired(true)
			->setAttrib('required', 'required')
			->setMultiOptions(array('2' => 'Petit ( 2 jours ) ','10' => 'Grande ( 10 Jours )'))
			->addValidator('notEmpty')
			->setDecorators($decorators);

	$Maint_Debut = new Zend_Form_Element_Text('datepickerdeb');
	$Maint_Debut	->setLabel('Jour du Debut:')
				->setAttrib('required', 'required')
				->setAttrib('size', '1')
				->setAttrib('class','datepickerdeb')
				->addValidator('notEmpty')
				->setDecorators($decorators);

    $Submit = new Zend_Form_Element_Submit('ajouter');
    $Submit  ->setLabel('Ajouter')
          ->setAttrib('class', 'button_avion')
          ->setAttrib('id', 'submitbutton')
          ->setDecorators($decoratorsBouton);


    $Fermer = new Zend_Form_Element_Reset('fermer');
    $Fermer  ->setLabel('Fermer')
          ->setAttrib('id', 'fermerbutton')
          ->setAttrib('class', 'closeAvion')
          ->setDecorators($decoratorsBouton);
      
  }
}
?>
