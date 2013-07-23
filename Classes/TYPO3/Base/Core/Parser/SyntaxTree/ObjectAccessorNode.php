<?php
namespace TYPO3\Base\Core\Parser\SyntaxTree;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Base".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * A node which handles object access. This means it handles structures like {object.accessor.bla}
 *
 */
class ObjectAccessorNode extends \TYPO3\Base\Core\Parser\SyntaxTree\AbstractNode {

	/**
	 * Object path which will be called. Is a list like "post.name.email"
	 * @var string
	 */
	protected $objectPath;

	/**
	 * Constructor. Takes an object path as input.
	 *
	 * The first part of the object path has to be a variable in the
	 * TemplateVariableContainer.
	 *
	 * @param string $objectPath An Object Path, like object1.object2.object3
	 */
	public function __construct($objectPath) {
		$this->objectPath = $objectPath;
	}


	/**
	 * Evaluate this node and return the correct object.
	 *
	 * Handles each part (denoted by .) in $this->objectPath in the following order:
	 * - call appropriate getter
	 * - call public property, if exists
	 * - fail
	 *
	 * The first part of the object path has to be a variable in the
	 * TemplateVariableContainer.
	 *
	 * @param \TYPO3\Base\Core\Rendering\RenderingContextInterface $renderingContext
	 * @return object The evaluated object, can be any object type.
	 */
	public function evaluate(\TYPO3\Base\Core\Rendering\RenderingContextInterface $renderingContext) {
		return self::getPropertyPath($renderingContext->getTemplateVariableContainer(), $this->objectPath, $renderingContext);
	}

}
?>