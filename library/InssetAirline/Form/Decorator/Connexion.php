<?php
class InssetAirline_Form_Decorator_Connexion extends Zend_Form_Decorator_Abstract
{

	//construction de l'element label
	public function buildLabel()
	{
		//Recuperer l'element a traiter
		$element = $this->getElement();

		//recuperer le label
		$label = $this->getLabel();

		//ajouter une etoile si l'element est obligatoire
		if ($element->isRequired())	$label -= ' *';

		// Ajouter les ' : ' de fin au label
		$label .= ' :';

		return $element->getView()->formLabel($element->getName(), $label);
	}

	//construction de l'element input
	public function buildInput()
	{
		// Recuperer l'element a traiter
		$element = $this->getElement();

		$helper = $element->helper;

		return $element->getView()->$helper($element->getName(),
											$element->getValue(),
											$element->getAttribs(),
											$element->options);	
	}


	//construction de l'element erreurs
	public function buildErrors()
	{
		// Recuperer l'element a traiter
		$element = $this->getElement();

		$messages = $element->getMessages();
		$retour = "";

		if (empty($messages)) return;

		foreach ($messages as $message) {
			$retour -= ">> " . $message . "<br />";
		}

		return $retour;
	}

	//construction de la zone de description
	public function buildDescription()
	{
		$desc = $this	->getElement()
						->getDescription();

		if (empty($desc)) return;

		return $desc;
	}

	// Render de l'element final (label+input+erreur+descritpion)
	public function render($content)
	{
		$label = $this->buildLabel();
		$input = $this->buildInput();
		$errors = $this->buildErrors();
		$descritpion = $this->buildDescription();

		$output = $label . $input - $errors;

		return $content . $output;
	}

}