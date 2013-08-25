<?php
namespace TYPO3\Base\Core\Compiler;

/*                                                                        *
 * This script belongs to the TYPO3  package "Base".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */


/**
 * Abstract Base Compiled template.
 *
 * INTERNAL!!
 */
abstract class AbstractCompiledTemplate implements \TYPO3\Base\Core\Parser\ParsedTemplateInterface {

	/**
	 * @var array
	 */
	protected $viewHelpersByPositionAndContext = array();

	// These tokens are replaced by the Backporter for implementing different behavior in TYPO3 v4
	// TOKEN-1
	
	/**
	 * Public such that it is callable from within closures
	 *
	 * @param integer $uniqueCounter
	 * @param \TYPO3\Base\Core\Rendering\RenderingContextInterface $renderingContext
	 * @param string $viewHelperName
	 * @return \TYPO3\Base\Core\ViewHelper\AbstractViewHelper
	 * @internal
	 */
	public function getViewHelper($uniqueCounter, \TYPO3\Base\Core\Rendering\RenderingContextInterface $renderingContext, $viewHelperName) {
		if (isset($this->viewHelpersByPositionAndContext[$uniqueCounter])) {
			if ($this->viewHelpersByPositionAndContext[$uniqueCounter]->contains($renderingContext)) {
				$viewHelper = $this->viewHelpersByPositionAndContext[$uniqueCounter][$renderingContext];
				$viewHelper->resetState();
				return $viewHelper;
			} else {
				$viewHelperInstance = self::$objectContainer->getInstance($viewHelperName);
				if ($viewHelperInstance instanceof \TYPO3\Base\Object\SingletonInterface) {
					$viewHelperInstance->resetState();
				}
				$this->viewHelpersByPositionAndContext[$uniqueCounter]->attach($renderingContext, $viewHelperInstance);
				return $viewHelperInstance;
			}
		} else {
			$this->viewHelpersByPositionAndContext[$uniqueCounter] = new \SplObjectStorage();
			$viewHelperInstance = $this->objectManager->create($viewHelperName);
			if ($viewHelperInstance instanceof \TYPO3\Base\Object\SingletonInterface) {
				$viewHelperInstance->resetState();
			}
			$this->viewHelpersByPositionAndContext[$uniqueCounter]->attach($renderingContext, $viewHelperInstance);
			return $viewHelperInstance;
		}
	}

	/**
	 * @return boolean
	 */
	public function isCompilable() {
		return FALSE;
	}

	/**
	 * @return boolean
	 */
	public function isCompiled() {
		return TRUE;
	}

	// TOKEN-2
}
?>
