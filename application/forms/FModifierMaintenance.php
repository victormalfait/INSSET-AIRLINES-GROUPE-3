<?php

class FModifierMaintenance extends Zend_Form {
  
  public function init() {

    $this->setName ( 'NouvelleMaintenance' );
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
 //array('m.immatriculation','m.date_prevue','m.date_eff','m.duree_prevue','m.duree_eff','m.note'))

  $duree_prevue = new Zend_Form_Element_Select('duree_prevue');
  $duree_prevue ->setLabel('Type : ')
      ->setRequired(true)
      ->setAttrib('required', 'required')
      ->setMultiOptions(array('2' => 'Petit ( 2 jours ) ','10' => 'Grande ( 10 Jours )'))
      ->addValidator('notEmpty')
      ->setDecorators($decorators);

  $date_prevue = new Zend_Form_Element_Text('date_prevue');
  $date_prevue  ->setLabel('Jour du Debut : ')
        ->setAttrib('required', 'required')
        ->setAttrib('size', '8')
        ->setRequired(true)
        ->setAttrib('class','date_prevue')
        ->addValidator('notEmpty')
        ->setDecorators($decorators);

  $duree_eff = new Zend_Form_Element_Text('duree_eff');
  $duree_eff ->setLabel('Temps ( jours ): ')
      ->setRequired(true)
      ->setAttrib('size', '2')
      ->setAttrib('required', 'required')
      ->addValidator('notEmpty')
      ->setDecorators($decorators);

  $date_eff = new Zend_Form_Element_Text('date_eff');
  $date_eff  ->setLabel('Jour du Debut : ')
        ->setAttrib('required', 'required')
        ->setAttrib('size', '8')
        ->setRequired(true)
        ->setAttrib('class','date_eff')
        ->addValidator('notEmpty')
        ->setDecorators($decorators);

  $note = new Zend_Form_Element_Textarea('note');
  $note  ->setLabel('Note : ')
        ->addValidator('notEmpty')
        ->setRequired(true)
        ->setAttrib('class','note')
        ->setAttrib('required', 'required')
        ->setDecorators($decorators);

    $Submit = new Zend_Form_Element_Submit('ajouter');
    $Submit  ->setLabel('Ajouter')
          ->setAttrib('id', 'submitbutton')
          ->setDecorators($decoratorsBouton);


    $Fermer = new Zend_Form_Element_Reset('fermer');
    $Fermer  ->setLabel('Fermer')
          ->setAttrib('id', 'fermerbutton')
          ->setDecorators($decoratorsBouton);
      
    $elements = array(  $duree_prevue,
        $date_prevue,
        $duree_eff,
        $date_eff,
        $note,
        $Submit,
        $Fermer
      );
    
      $this->addElements ( $elements );
  }
}
