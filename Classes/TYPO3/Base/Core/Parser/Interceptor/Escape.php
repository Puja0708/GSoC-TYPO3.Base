<?php
namespace TYPO3\Base\Core\Parser\Interceptor;

/*                                                                        *
 * This script belongs to the TYPO3 package "Base".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * An interceptor adding the escape viewhelper to the suitable places.
 *
 */
class Escape implements \TYPO3\Base\Core\Parser\InterceptorInterface {

	/**
	 * Is the interceptor enabled right now?
	 * @var boolean
	 */
	protected $interceptorEnabled = TRUE;

	/**
	 * A stack of ViewHelperNodes which currently disable the interceptor.
	 * Needed to enable the interceptor again.
	 *
	 * @var array<\TYPO3\Base\Core\Parser\SyntaxTree\NodeInterface>
	 */
	protected $viewHelperNodesWhichDisableTheInterceptor = array();

	

	
	/**
	 * Adds a ViewHelper node using the Format\HtmlspecialcharsViewHelper to the given node.
	 * If "escapingInterceptorEnabled" in the ViewHelper is FALSE, will disable itself inside the ViewHelpers body.
	 *
	 * @param \TYPO3\Base\Core\Parser\SyntaxTree\NodeInterface $node
	 * @param integer $interceptorPosition One of the INTERCEPT_* constants for the current interception point
	 * @param \TYPO3\Base\Core\Parser\ParsingState $parsingState the current parsing state. Not needed in this interceptor.
	 * @return \TYPO3\Base\Core\Parser\SyntaxTree\NodeInterface
	 */
	public function process(\TYPO3\Base\Core\Parser\SyntaxTree\NodeInterface $node, $interceptorPosition, \TYPO3\Base\Core\Parser\ParsingState $parsingState) {
		if ($interceptorPosition === \TYPO3\Base\Core\Parser\InterceptorInterface::INTERCEPT_OPENING_VIEWHELPER) {
			if (!$node->getUninitializedViewHelper()->isEscapingInterceptorEnabled()) {
				$this->interceptorEnabled = FALSE;
				$this->viewHelperNodesWhichDisableTheInterceptor[] = $node;
			}
		} elseif ($interceptorPosition === \TYPO3\Base\Core\Parser\InterceptorInterface::INTERCEPT_CLOSING_VIEWHELPER) {
			if (end($this->viewHelperNodesWhichDisableTheInterceptor) === $node) {
				array_pop($this->viewHelperNodesWhichDisableTheInterceptor);
				if (count($this->viewHelperNodesWhichDisableTheInterceptor) === 0) {
					$this->interceptorEnabled = TRUE;
				}
			}
		} elseif ($this->interceptorEnabled && $node instanceof \TYPO3\Base\Core\Parser\SyntaxTree\ObjectAccessorNode) {
			$escapeViewHelper = $this->objectManager->get('TYPO3\Base\ViewHelpers\Format\HtmlspecialcharsViewHelper');
			$node = $this->objectManager->get(
				'TYPO3\Base\Core\Parser\SyntaxTree\ViewHelperNode',
				$escapeViewHelper,
				array('value' => $node)
			);
		}
		return $node;
	}

	/**
	 * This interceptor wants to hook into object accessor creation, and opening / closing ViewHelpers.
	 *
	 * @return array Array of INTERCEPT_* constants
	 */
	public function getInterceptionPoints() {
		return array(
			\TYPO3\Base\Core\Parser\InterceptorInterface::INTERCEPT_OPENING_VIEWHELPER,
			\TYPO3\Base\Core\Parser\InterceptorInterface::INTERCEPT_CLOSING_VIEWHELPER,
			\TYPO3\Base\Core\Parser\InterceptorInterface::INTERCEPT_OBJECTACCESSOR
		);
	}
}
?>
