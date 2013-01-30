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

        //decorateur d'element
        $decoratorsBis = array(
            'ViewHelper',
            'Errors',
            array('Label', array('tag' => '<br>', 'class' => 'label')),
            array('HtmlTag', array('tag' => 'li'))
        );

        //decorateur de formulaire
        $decoratorsForm = array(
            'FormElements',
            array('Fieldset', array('tag' => 'div')),
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
        $eAeroportArrivee->setLabel('À')
                         ->setRequired(true)
                         ->addValidator('notEmpty')
                         ->setMultiOptions($this->listVille())
                         ->setDecorators($decorators);

        $eTypeTrajet = new Zend_Form_Element_Radio('typeTrajet');
        $eTypeTrajet->setMultiOptions(array( '2'=>'Aller retour'
                                            ,'1'=>'Aller simple'))
                    ->setOptions(array('separator'=>' '))
                    ->setValue('2')
                    ->setDecorators($decorators);

        $eDatedeb = new Zend_Form_Element_Text('datepickerdeb');
        $eDatedeb   ->setLabel('Date aller')
                    ->setRequired(true)
                    ->setAttrib('size', '7')
                    ->setDecorators($decoratorsBis);

        $eDatefin = new Zend_Form_Element_Text('datepickerfin');
        $eDatefin   ->setLabel('Date retour')
                    ->setAttrib('size', '7')
                    ->setDecorators($decoratorsBis);

        $nbr=array();
        for($i=1;$i<10;$i++)
            $nbr[$i]=$i;

        $eNbrPassager = new Zend_Form_Element_Select('nbrPassager');
        $eNbrPassager   ->setLabel('Nombre de passagers : ')
                        ->setRequired(true)
                        ->addValidator('notEmpty')
                        ->setMultiOptions($nbr)
                        ->setDecorators($decoratorsBis);

        $typePassager = array( '1' => 'Adultes (25-64 ans)'
                              ,'0.5' => 'Enfants (2-11 ans)'
                              ,'0.75' => 'Séniors (65 ans et plus)');

        $eTypePassagerA = new Zend_Form_Element_Select('typePassager');
        $eTypePassagerA ->setLabel('1 : ')
                        ->setMultiOptions($typePassager)
                        ->setDecorators($decorators);

        $eTypePassagerB = new Zend_Form_Element_Select('typePassager2');
        $eTypePassagerB ->setMultiOptions($typePassager)
                        ->setLabel('2 : ')
                        ->setDecorators($decorators);

        $eTypePassagerC = new Zend_Form_Element_Select('typePassager3');
        $eTypePassagerC ->setMultiOptions($typePassager)
                        ->setLabel('3 : ')
                        ->setDecorators($decorators);

        $eTypePassagerD = new Zend_Form_Element_Select('typePassager4');
        $eTypePassagerD ->setMultiOptions($typePassager)
                        ->setLabel('4 : ')
                        ->setDecorators($decorators);

        $eTypePassagerE = new Zend_Form_Element_Select('typePassager5');
        $eTypePassagerE ->setMultiOptions($typePassager)
                        ->setLabel('5 : ')
                        ->setDecorators($decorators);

        $eTypePassagerF = new Zend_Form_Element_Select('typePassager6');
        $eTypePassagerF ->setMultiOptions($typePassager)
                        ->setLabel('6 : ')
                        ->setDecorators($decorators);

        $eTypePassagerG = new Zend_Form_Element_Select('typePassager7');
        $eTypePassagerG ->setMultiOptions($typePassager)
                        ->setLabel('7 : ')
                        ->setDecorators($decorators);

        $eTypePassagerH = new Zend_Form_Element_Select('typePassager8');
        $eTypePassagerH ->setMultiOptions($typePassager)
                        ->setLabel('8 : ')
                        ->setDecorators($decorators);

        $eTypePassagerI = new Zend_Form_Element_Select('typePassager9');
        $eTypePassagerI ->setMultiOptions($typePassager)
                        ->setLabel('9 : ')
                        ->setDecorators($decorators);

        $eTypePassagerJ = new Zend_Form_Element_Select('typePassager10');
        $eTypePassagerJ ->setMultiOptions($typePassager)
                        ->setLabel('10 : ')
                        ->setDecorators($decorators);

        $classe = array( '1'=>'Economique'
                        ,'2.5'=>'Première'
                        ,'2'=>'Affaire');

        $eClasse = new Zend_Form_Element_Select('classe');
        $eClasse    ->setLabel('Classe : ')
                    ->setRequired(true)
                    ->addValidator('notEmpty')
                    ->setMultiOptions($classe)
                    ->setDecorators($decoratorsBis);

        $eSubmit = new Zend_Form_Element_Submit('reserver');
        $eSubmit     ->setAttrib('id', 'SBTReserver')
                    ->setAttrib('class', 'bgRedBtn')
                    ->setLabel('Réserver');

        $elements = array($eAeroportDepart
                        , $eAeroportArrivee
                        , $eTypeTrajet
                        , $eDatedeb
                        , $eDatefin
                        , $eNbrPassager
                        , $eTypePassagerA
                        , $eTypePassagerB
                        , $eTypePassagerC
                        , $eTypePassagerD
                        , $eTypePassagerE
                        , $eTypePassagerF
                        , $eTypePassagerG
                        , $eTypePassagerH
                        , $eTypePassagerI
                        , $eTypePassagerJ
                        , $eClasse
                        , $eSubmit
                        );
        $this->addElements($elements);

    //=============== creation des groupes de formulaire
        $this->addDisplayGroup(array('aeroportDepart','aeroportArrive'),
                                'un',
                                array("legend" => ""));
        
        $this->addDisplayGroup(array('typeTrajet'),
                                'deux',
                                array("legend" => ""));

        $this->addDisplayGroup(array('datepickerdeb','datepickerfin'),
                                'trois',
                                array("legend" => ""));

        $this->addDisplayGroup(array('nbrPassager'),
                                'quatre',
                                array("legend" => ""));

        $this->addDisplayGroup(array('typePassager',
                                    'typePassager2',
                                    'typePassager3',
                                    'typePassager4',
                                    'typePassager5',
                                    'typePassager6',
                                    'typePassager7',
                                    'typePassager8',
                                    'typePassager9',
                                    'typePassager10'),
                                'quatreBis',
                                array("legend" => ""));

        $this->addDisplayGroup(array('classe'),
                                'cinq',
                                array("legend" => ""));
        $this->addDisplayGroup(array('reserver'),
                                'six',
                                array("legend" => ""));

    }

    /**
    * Liste des villes
    */
    private function listVille () {
        // on charge les models
        $tableAeroport = new TAeroport;
 
        // on recupere tout les pays
        $reqAeroport = $tableAeroport   ->select()
                                        ->from($tableAeroport)
                                        ->order("nom_aeroport");

        $aeroport = $tableAeroport->fetchAll($reqAeroport);

        // on instancie le resultat en tableau de pays
        $aeroportTab = array();

        $aeroportTab[""] = "-- Choisissez --"; 
        foreach ($aeroport as $a) {
            $tableAeroport = new TAeroport;
            // on recherche l'aeroport par clé primaire
            $aeroport = $tableAeroport  ->find($a->trigramme)
                                        ->current();
            $ville = $aeroport->findParentRow('TVille');

            $aeroportTab[$ville->id_ville] = utf8_encode($a->nom_aeroport . '(' . $a->trigramme . ')' . ', ' . $ville->nom_ville);
        }
 
        return $aeroportTab;
    }
}
