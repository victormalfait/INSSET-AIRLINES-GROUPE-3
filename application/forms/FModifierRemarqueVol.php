<?php

class FModifierRemarqueVol extends Zend_Form {
  
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

    $remarque = new Zend_Form_Element_Textarea('remarque');
    $remarque  ->setLabel('Remarque : ')
          ->addValidator('notEmpty')
          ->setRequired(true)
          ->setAttrib('class','remarque')->setAttrib('cols', '80')->setAttrib('rows', '4')
          ->setAttrib('required', 'required')
          ->setDecorators($decorators);

    $Submit = new Zend_Form_Element_Submit('modifier');
    $Submit  ->setLabel('Modifier')
          ->setAttrib('id', 'submitbutton')
          ->setDecorators($decoratorsBouton);


    $Fermer = new Zend_Form_Element_Reset('fermer');
    $Fermer  ->setLabel('Fermer')
          ->setAttrib('id', 'fermerbutton')
          ->setDecorators($decoratorsBouton);
      
    $elements = array(  $remarque,
        $Submit,
        $Fermer
      );
    
      $this->addElements ( $elements );
  }
}
