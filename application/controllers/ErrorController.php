<?php

class ErrorController extends Zend_Controller_Action 
{
	public function errorAction() 
	{
		$erreur = $this->_getParam('error_handler');

			
			echo '<br/> Type : ';
			var_dump($erreur->type);
			echo "<br/> Message: ";
			var_dump($erreur->exception->getMessage());
			echo "<br/> Line : ";
			var_dump($erreur->exception->getLine());
			echo "<br/> Code : ";
			var_dump($erreur->exception->getCode());
			echo "<br/> Files : ";
			var_dump($erreur->exception->getFile());



	}

}









