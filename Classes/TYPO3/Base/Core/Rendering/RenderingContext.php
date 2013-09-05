<?php
namespace TYPO3\Base\Core\Rendering;

/*                                                                        *
 * This script belongs to the TYPO3  package "Base".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

class RenderingContext implements \TYPO3\Base\Core\Rendering\RenderingContextInterface {



	/**
	 * ViewHelper Variable Container
	 *
	 * @var \TYPO3\Base\Core\ViewHelper\ViewHelperVariableContainer
	 */
	protected $viewHelperVariableContainer;

	

	/**
	 * Injects the template variable container containing all variables available through ObjectAccessors
	 * in the template
	 *
	 * @param \TYPO3\Base\Core\ViewHelper\TemplateVariableContainer $templateVariableContainer The template variable container to set
	 */
	public function injectTemplateVariableContainer(\TYPO3\Base\Core\ViewHelper\TemplateVariableContainer $templateVariableContainer) {
		$this->templateVariableContainer = $templateVariableContainer;
	}

	/**
	 * Get the template variable container
	 *
	 * @return \TYPO3\Base\Core\ViewHelper\TemplateVariableContainer The Template Variable Container
	 */
	public function getTemplateVariableContainer() {
		return $this->templateVariableContainer;
	}

	

	/**
	 * Set the ViewHelperVariableContainer
	 *
	 * @param \TYPO3\Base\Core\ViewHelper\ViewHelperVariableContainer $viewHelperVariableContainer
	 * @return void
	 */
	public function injectViewHelperVariableContainer(\TYPO3\Base\Core\ViewHelper\ViewHelperVariableContainer $viewHelperVariableContainer) {
		$this->viewHelperVariableContainer = $viewHelperVariableContainer;
	}

	/**
	 * Get the ViewHelperVariableContainer
	 *
	 * @return \TYPO3\Base\Core\ViewHelper\ViewHelperVariableContainer
	 */
	public function getViewHelperVariableContainer() {
		return $this->viewHelperVariableContainer;
	}
}

?>
