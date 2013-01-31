<?php

class FValiderVolFini extends Zend_Form {
  
  public function init() {
    $this->setMethod('post');
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

    $date_dep= new Zend_Form_Element_Text('datepickerdeb');
    $date_dep ->setLabel('Date de départ :')
          ->setAttrib('size', '6')
          ->setAttrib('class','datepickerdeb')
          ->setDecorators($decorators);


    $time_dep = new Zend_Form_Element_Text('timepickerdeb');
    $time_dep ->setLabel('Heures de départ:')
          ->setAttrib('required', 'required')
          ->setAttrib('size', '1')
          ->setAttrib('class','timepickerdeb')
          ->addValidator('notEmpty')
          ->setDecorators($decorators);

    $date_arr= new Zend_Form_Element_Text('datepickerarr');
    $date_arr ->setLabel('Date d\'arriver :')
          ->setAttrib('size', '6')
          ->setAttrib('class','datepickerarr')
          ->setDecorators($decorators);


    $time_arr = new Zend_Form_Element_Text('timepickerarr');
    $time_arr ->setLabel('Heures d\'arriver :')
          ->setAttrib('required', 'required')
          ->setAttrib('size', '1')
          ->setAttrib('class','timepickerarr')
          ->addValidator('notEmpty')
          ->setDecorators($decorators);

    $remarque = new Zend_Form_Element_Textarea('remarque');
    $remarque  ->setLabel('Remarque : ')
          ->addValidator('notEmpty')
          ->setRequired(true)
          ->setAttrib('class','remarque')->setAttrib('cols', '80')->setAttrib('rows', '4')
          ->setAttrib('required', 'required')
          ->setDecorators($decorators);

    $Submit = new Zend_Form_Element_Submit('valider');
    $Submit  ->setLabel('Valider')
          ->setAttrib('id', 'submitbutton')
          ->setDecorators($decoratorsBouton);

    $Fermer = new Zend_Form_Element_Reset('fermer');
    $Fermer  ->setLabel('Fermer')
          ->setAttrib('id', 'fermerbutton')
          ->setDecorators($decoratorsBouton);
      
    $elements = array(  $date_dep,
                        $time_dep,
                        $date_arr,
                        $time_arr,
                        $remarque,
                        $Submit,
                        $Fermer
      );
    
      $this->addElements ( $elements );
  }
}
