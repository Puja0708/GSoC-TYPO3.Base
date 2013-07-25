<?php
namespace TYPO3\Base\ViewHelpers\Form;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Fluid".                 *
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
 */
abstract class AbstractFormViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {


	/**
	 * Prefixes / namespaces the given name with the form field prefix
	 *
	 * @param string $fieldName field name to be prefixed
	 * @return string namespaced field name
	 */
	protected function prefixFieldName($fieldName) {
		if ($fieldName === NULL || $fieldName === '') {
			return '';
		}
		if (!$this->viewHelperVariableContainer->exists('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'fieldNamePrefix')) {
			return $fieldName;
		}
		$fieldNamePrefix = (string)$this->viewHelperVariableContainer->get('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'fieldNamePrefix');
		if ($fieldNamePrefix === '') {
			return $fieldName;
		}
		$fieldNameSegments = explode('[', $fieldName, 2);
		$fieldName = $fieldNamePrefix . '[' . $fieldNameSegments[0] . ']';
		if (count($fieldNameSegments) > 1) {
			$fieldName .= '[' . $fieldNameSegments[1];
		}
		return $fieldName;
	}

	/**
	 * Register a field name for inclusion in the HMAC / Form Token generation
	 *
	 * @param string $fieldName name of the field to register
	 * @return void
	 */
	protected function registerFieldNameForFormTokenGeneration($fieldName) {
		if ($this->viewHelperVariableContainer->exists('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'formFieldNames')) {
			$formFieldNames = $this->viewHelperVariableContainer->get('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'formFieldNames');
		} else {
			$formFieldNames = array();
		}
		$formFieldNames[] = $fieldName;
		$this->viewHelperVariableContainer->addOrUpdate('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'formFieldNames', $formFieldNames);
	}
}

?>
