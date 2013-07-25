<?php
namespace TYPO3\Base\ViewHelpers\Form;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Base".                  *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */


/**
 * Abstract Form View Helper. Bundles functionality related to direct property access of objects in other Form ViewHelpers.
 *
 * If you set the "property" attribute to the name of the property to resolve from the object, this class will
 * automatically set the name and value of a form element.
 *
 * @api
 */
abstract class AbstractFormFieldViewHelper extends \TYPO3\Fluid\ViewHelpers\Form\AbstractFormViewHelper {

	/**
	 * Initialize arguments.
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('name', 'string', 'Name of input tag');
		$this->registerArgument('value', 'mixed', 'Value of input tag');
		$this->registerArgument('property', 'string', 'Name of Object Property. If used in conjunction with <f:form object="...">, "name" and "value" properties will be ignored.');
	}

	/**
	 * Get the name of this form element.
	 * Either returns arguments['name'], or the correct name for Object Access.
	 *
	 * In case property is something like bla.blubb (hierarchical), then [bla][blubb] is generated.
	 *
	 * @return string Name
	 */
	protected function getName() {
		$name = $this->getNameWithoutPrefix();
		return $this->prefixFieldName($name);
	}

	/**
	 * Get the name of this form element, without prefix.
	 *
	 * @return string name
	 */
	protected function getNameWithoutPrefix() {
		if ($this->isObjectAccessorMode()) {
			$propertySegments = explode('.', $this->arguments['property']);
			$formObjectName = $this->viewHelperVariableContainer->get('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'formObjectName');
			if (!empty($formObjectName)) {
				array_unshift($propertySegments, $formObjectName);
			}
			$name = array_shift($propertySegments);
			foreach ($propertySegments as $segment) {
				$name .= '[' . $segment . ']';
			}
		} else {
			$name = $this->arguments['name'];
		}
		if ($this->hasArgument('value') && is_object($this->arguments['value'])) {
			if (NULL !== $this->persistenceManager->getIdentifierByObject($this->arguments['value'])
				&& (!$this->persistenceManager->isNewObject($this->arguments['value']))) {
				$name .= '[__identity]';
			}
		}

		return $name;
	}

	/**
	 * Get the value of this form element.
	 * Either returns arguments['value'], or the correct value for Object Access.
	 *
	 * @param boolean $convertObjects whether or not to convert objects to identifiers
	 * @return mixed Value
	 */
	protected function getValue($convertObjects = TRUE) {
		$value = NULL;

		if ($this->hasArgument('value')) {
			$value = $this->arguments['value'];
		} elseif ($this->hasMappingErrorOccured()) {
			$value = $this->getLastSubmittedFormData();
		} elseif ($this->isObjectAccessorMode() && $this->viewHelperVariableContainer->exists('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'formObject')) {
			$this->addAdditionalIdentityPropertiesIfNeeded();
			$value = $this->getPropertyValue();
		}
		if ($convertObjects && is_object($value)) {
			$identifier = $this->persistenceManager->getIdentifierByObject($value);
			if ($identifier !== NULL) {
				$value = $identifier;
			}
		}
		return $value;
	}

	/**
	 * Checks if a property mapping error has occured in the last request.
	 *
	 * @return boolean TRUE if a mapping error occured, FALSE otherwise
	 */
	protected function hasMappingErrorOccured() {
		$validationResults = $this->controllerContext->getRequest()->getInternalArgument('__submittedArgumentValidationResults');
		return ($validationResults !== NULL && $validationResults->hasErrors());
	}

	

	/**
	 * Internal method which checks if we should evaluate a domain object or just output arguments['name'] and arguments['value']
	 *
	 * @return boolean TRUE if we should evaluate the domain object, FALSE otherwise.
	 */
	protected function isObjectAccessorMode() {
		return $this->hasArgument('property')
			&& $this->viewHelperVariableContainer->exists('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'formObjectName');
	}

	/**
	 * Add an CSS class if this view helper has errors
	 *
	 * @return void
	 */
	protected function setErrorClassAttribute() {
		if ($this->hasArgument('class')) {
			$cssClass = $this->arguments['class'] . ' ';
		} else {
			$cssClass = '';
		}
		$mappingResultsForProperty = $this->getMappingResultsForProperty();
		if ($mappingResultsForProperty->hasErrors()) {
			if ($this->hasArgument('errorClass')) {
				$cssClass .= $this->arguments['errorClass'];
			} else {
				$cssClass .= 'error';
			}
			$this->tag->addAttribute('class', $cssClass);
		}
	}


	/**
	 * Renders a hidden field with the same name as the element, to make sure the empty value is submitted
	 * in case nothing is selected. This is needed for checkbox and multiple select fields
	 *
	 * @return void
	 */
	protected function renderHiddenFieldForEmptyValue() {
		$emptyHiddenFieldNames = array();
		if ($this->viewHelperVariableContainer->exists('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'emptyHiddenFieldNames')) {
			$emptyHiddenFieldNames = $this->viewHelperVariableContainer->get('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'emptyHiddenFieldNames');
		}

		$fieldName = $this->getName();
		if (substr($fieldName, -2) === '[]') {
			$fieldName = substr($fieldName, 0, -2);
		}
		if (!in_array($fieldName, $emptyHiddenFieldNames)) {
			$emptyHiddenFieldNames[] = $fieldName;
			$this->viewHelperVariableContainer->addOrUpdate('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'emptyHiddenFieldNames', $emptyHiddenFieldNames);
		}
	}
}

?>
