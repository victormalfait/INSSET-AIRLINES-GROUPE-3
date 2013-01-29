<?php

class acl extends Zend_Acl 
{
    public function __construct($file)    {
        $roles = new Zend_Config_Ini($file, 'roles') ;
        $this->_setRoles($roles) ;
        
        $resources = new Zend_Config_Ini($file, 'resources') ;
        $this->_setResources($resources) ;
        
        foreach ($roles->toArray() as $role => $parents)    {
            $privileges = new Zend_Config_Ini($file, $role) ;
            $this->_setPrivileges($role, $privileges) ;
        }
    }
    
    protected function _setRoles($roles)    {
        foreach ($roles as $role => $parents)    {
            if (empty($parents))    {
                $parents = null ;
            } else {
                $parents = explode(',', $parents) ;
            }

            $this->addRole(new Zend_Acl_Role($role), $parents);
        }
        
        return $this ;
    }

    protected function _setResources($resources)    {
        foreach ($resources as $resource => $parents)    {
            if (empty($parents))    {
                $parents = null ;
            } else {
                $parents = explode(',', $parents) ;
            }

            $this->add(new Zend_Acl_Resource($resource), $parents);
        }
        
        return $this ;
    }

    protected function _setPrivileges($role, $privileges)    {
        foreach ($privileges as $do => $resources)    {
            foreach ($resources as $resource => $actions)    {
                if (empty($actions))    {
                    $actions = null ;
                } else {
                    $actions = explode(',', $actions) ;
                }
                
                $this->{$do}($role, $resource, $actions);
            }
        }
        
        return $this ;
    }
}