<?php
namespace TYPO3\Base\ViewHelpers;

/*                                                                        *
 * This script belongs to the TYPO3 package "Base".          	          *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */


/**
 * View helper that outputs its child nodes with var_dump()
 *
 * = Examples =
 *
 * <code>
 * <f:debug>{object}</f:debug>
 * </code>
 * <output>
 * all properties of {object} nicely highlighted
 * </output>
 *
 * <code title="inline notation and custom title">
 * {object -> f:debug(title: 'Custom title')}
 * </code>
 * <output>
 * all properties of {object} nicely highlighted (with custom title)
 * </output>
 *
 * <code title="only output the type">
 * {object -> f:debug(typeOnly: 1)}
 * </code>
 * <output>
 * the type or class name of {object}
 * </output>
 *
 * Note: This view helper is only meant to be used during development
 *
 * @api
 */
class DebugViewHelper extends \TYPO3\Base\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @var boolean
	 */
	protected $escapingInterceptorEnabled = FALSE;

	/**
	 * A wrapper for var_dump() or Doctrine's Dump Utility.
	 *
	 * @param string $title optional custom title for the debug output
	 * @param integer $maxDepth Sets the max recursion depth of the dump (defaults to 8). De- or increase the number according to your needs and memory limit.
	 * @param boolean $plainText If TRUE, the dump is in plain text, if FALSE the debug output is in HTML format.
	 * @param boolean $ansiColors If TRUE, ANSI color codes is added to the plaintext output, if FALSE (default) the plaintext debug output not colored.
	 * @param boolean $inline if TRUE, the dump is rendered at the position of the <f:debug> tag. If FALSE (default), the dump is displayed at the top of the page.
	 * @param array $blacklistedClassNames An array of class names (RegEx) to be filtered. Default is an array of some common class names.
	 * @param array $blacklistedPropertyNames An array of property names and/or array keys (RegEx) to be filtered. Default is an array of some common property names.
	 * @return string
	 */
	public function render($title = NULL, $maxDepth = 8, $plainText = FALSE, $ansiColors = FALSE, $inline = FALSE, $blacklistedClassNames = NULL, $blacklistedPropertyNames = NULL) {
		if (class_exists('Doctrine\\Common\\Util\\Debug')) {
			return \Doctrine\Common\Util\Debug::dump($this->renderChildren(), $maxDepth, !$plainText);
		}
		return var_export($this->renderChildren(), TRUE);
	}
}


?>
