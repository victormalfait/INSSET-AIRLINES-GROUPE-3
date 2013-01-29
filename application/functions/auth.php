<?php

class auth extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var Zend_Auth instance 
     */
    private $_auth;
    
    /**
     * @var Zend_Acl instance 
     */
    private $_acl;
        
    /**
     * Chemin de redirection lors de l'échec d'authentification
     */
    const FAIL_AUTH_MODULE     = null;
    const FAIL_AUTH_ACTION     = 'index';
    const FAIL_AUTH_CONTROLLER = 'index';
    
    /**
     * Chemin de redirection lors de l'échec de contrôle des privilèges
     */
    const FAIL_ACL_MODULE     = null;
    const FAIL_ACL_ACTION     = 'index';
    const FAIL_ACL_CONTROLLER = 'index';
    
    /**
     * Constructeur
     */
    public function __construct(Zend_Acl $acl, Zend_Auth $auth)    {
        $this->_acl  = $acl ;
        $this->_auth = $auth ;
    }
    
    /**
     * Vérifie les autorisations
     * Utilise _request et _response hérités et injectés par le FC
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)    {
        // si l'utilisateur est connecté ...
        if ($this->_auth->hasIdentity()) {
          // ... on recupere son role
          $user = $this->_auth->getIdentity();
          $role = $user->id_service;
        }
        else { //sinon (l'utilisateur n'est pas connecté)
          // c'est un visiteur
          $role = 'visiteur';
        }
        
        $module     = $request->getModuleName() ;
        $controller = $request->getControllerName() ;
        $action     = $request->getActionName() ;
        
        $front = Zend_Controller_Front::getInstance() ;
        $default = $front->getDefaultModule() ;
        
        // compose le nom de la ressource
        if ($module == $default)    {
            $resource = $controller ;
        }
        else {
            $resource = $module.'_'.$controller ;
        }
    
        // est-ce que la ressource existe ?
        if (!$this->_acl->has($resource)) {
          $resource = null;
        }
        
        // contrôle si l'utilisateur est autorisé
        if (!$this->_acl->isAllowed($role, $resource, $action)) {
            // l'utilisateur n'est pas autorisé Ã  accéder Ã  cette ressource
            // on va le rediriger
            if (!$this->_auth->hasIdentity()) {
                // il n'est pas identifié -> module de login
                $module = self::FAIL_AUTH_MODULE ;
                $controller = self::FAIL_AUTH_CONTROLLER ;
                $action = self::FAIL_AUTH_ACTION ;
            } else {
                // il est identifié -> error de privilèges
                $module = self::FAIL_ACL_MODULE ;
                $controller = self::FAIL_ACL_CONTROLLER ;
                $action = self::FAIL_ACL_ACTION ;
            }
        }

        $request->setModuleName($module) ;
        $request->setControllerName($controller) ;
        $request->setActionName($action) ;
    }
}