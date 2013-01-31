<?php

class FModifierHeursAvion extends Zend_Form {
  
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

    $heurs = new Zend_Form_Element_Text('heurs');
    $heurs  ->setLabel('Nombre d\'heures a rajouter :')
            ->setRequired(true)
            ->setAttrib('required', 'required')
            ->setAttrib('size', '5')
            ->addFilter('StripTags')
                ->addFilter('StringTrim')
            ->addValidator('notEmpty')
            ->setDecorators($decorators);

    $Submit = new Zend_Form_Element_Submit('ajuter');
    $Submit  ->setLabel('Ajouter')
          ->setAttrib('id', 'submitbutton')
          ->setDecorators($decoratorsBouton);

    $Fermer = new Zend_Form_Element_Reset('fermer');
    $Fermer  ->setLabel('Fermer')
          ->setAttrib('id', 'fermerbutton')
          ->setDecorators($decoratorsBouton);
      
    $elements = array(  $heurs,
        $Submit,
        $Fermer
      );
    
      $this->addElements ( $elements );
  }
}
