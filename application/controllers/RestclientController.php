<?php

class RestclientController extends Zend_Controller_Action
{

    public function init()
    {
       
    }

    public function indexAction()
    {
        $client = new Zend_Rest_Client('http://...');
        echo $client->methode(param,param2)->get();

    }

}