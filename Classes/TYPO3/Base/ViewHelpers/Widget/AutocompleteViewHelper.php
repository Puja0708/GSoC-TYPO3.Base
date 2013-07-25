<?php
namespace TYPO3\Base\ViewHelpers\Widget;

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
 * Usage:
 * <f:input id="name" ... />
 * <f:widget.autocomplete for="name" objects="{posts}" searchProperty="author">
 *
 * Make sure to include jQuery and jQuery UI in the HTML, like that:
 *    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
 *    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>
 *    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.3/themes/base/jquery-ui.css" type="text/css" media="all" />
 *    <link rel="stylesheet" href="http://static.jquery.com/ui/css/demo-docs-theme/ui.theme.css" type="text/css" media="all" />
 *
 * @api
 */
class AutocompleteViewHelper extends \TYPO3\Fluid\Core\Widget\AbstractWidgetViewHelper {

	/**
	 * @var bool
	 */
	protected $ajaxWidget = TRUE;

	

	/**
	 * @param \TYPO3\Fluid\ViewHelpers\Widget\Controller\AutocompleteController $controller
	 * @return void
	 */
	public function injectController(\TYPO3\Fluid\ViewHelpers\Widget\Controller\AutocompleteController $controller) {
		$this->controller = $controller;
	}



	/**
	 * @param mixed $objects
	 * @param string $for
	 * @param string $searchProperty
	 * @return string
	 */
	public function render($objects, $for, $searchProperty) {
		return $this->initiateSubRequest();
	}
	
}
?>
