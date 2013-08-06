<?php
namespace TYPO3\Base\View;

/*                                                                        *
 * This script belongs to the TYPO3 package "Base".       		          *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * The main template view. Should be used as view if you want Base Templating
 *
 * @api
 */
class TemplateView extends \TYPO3\Base\View\AbstractTemplateView implements \TYPO3\Base\View\ViewInterface{

	/**
	 * Pattern to be resolved for "@templateRoot" in the other patterns.
	 *
	 * @var string
	 */
	protected $templateRootPathPattern = '@packageResourcesPath/Private/Templates';

	/**
	 * Pattern to be resolved for "@partialRoot" in the other patterns.
	 *
	 * @var string
	 */
	protected $partialRootPathPattern = '@packageResourcesPath/Private/Partials';

	/**
	 * Pattern to be resolved for "@layoutRoot" in the other patterns.
	 *
	 * @var string
	 */
	protected $layoutRootPathPattern = '@packageResourcesPath/Private/Layouts';

	/**
	 * Path to the template root. If NULL, then $this->templateRootPathPattern will be used.
	 *
	 * @var string
	 */
	protected $templateRootPath = NULL;

	/**
	 * Path to the partial root. If NULL, then $this->partialRootPathPattern will be used.
	 *
	 * @var string
	 */
	protected $partialRootPath = NULL;

	/**
	 * Path to the layout root. If NULL, then $this->layoutRootPathPattern will be used.
	 *
	 * @var string
	 */
	protected $layoutRootPath = NULL;

	/**
	 * File pattern for resolving the template file
	 *
	 * @var string
	 */
	protected $templatePathAndFilenamePattern = '@templateRoot/@subpackage/@controller/@action.@format';

	/**
	 * Directory pattern for global partials. Not part of the public API, should not be changed for now.
	 *
	 * @var string
	 */
	private $partialPathAndFilenamePattern = '@partialRoot/@subpackage/@partial.@format';

	/**
	 * File pattern for resolving the layout
	 *
	 * @var string
	 */
	protected $layoutPathAndFilenamePattern = '@layoutRoot/@layout.@format';

	/**
	 * Path and filename of the template file. If set,  overrides the templatePathAndFilenamePattern
	 *
	 * @var string
	 */
	protected $templatePathAndFilename = NULL;

	/**
	 * Path and filename of the layout file. If set, overrides the layoutPathAndFilenamePattern
	 *
	 * @var string
	 */
	protected $layoutPathAndFilename = NULL;

	

	/**
	 * Sets the path and name of of the template file. Effectively overrides the
	 * dynamic resolving of a template file.
	 *
	 * @param string $templatePathAndFilename Template file path
	 * @return void
	 * @api
	 */
	public function setTemplatePathAndFilename($templatePathAndFilename) {
		$this->templatePathAndFilename = $templatePathAndFilename;
	}

	/**
	 * Sets the path and name of the layout file. Overrides the dynamic resolving of the layout file.
	 *
	 * @param string $layoutPathAndFilename Path and filename of the layout file
	 * @return void
	 * @api
	 */
	public function setLayoutPathAndFilename($layoutPathAndFilename) {
		$this->layoutPathAndFilename = $layoutPathAndFilename;
	}

	

	/**
	 * Set the root path to the templates.
	 * If set, overrides the one determined from $this->templateRootPathPattern
	 *
	 * @param string $templateRootPath Root path to the templates. If set, overrides the one determined from $this->templateRootPathPattern
	 * @return void
	 * @api
	 */
	public function setTemplateRootPath($templateRootPath) {
		$this->templateRootPath = $templateRootPath;
	}

	/**
	 * Returns a unique identifier for the resolved template file
	 * This identifier is based on the template path and last modification date
	 *
	 * @param string $actionName Name of the action. If NULL, will be taken from request.
	 * @return string template identifier
	 */
	protected function getTemplateIdentifier($actionName = NULL) {
		$templatePathAndFilename = $this->getTemplatePathAndFilename($actionName);
		if ($actionName === NULL) {
			$actionName = $this->controllerContext->getRequest()->getControllerActionName();
		}
		$prefix = 'action_' . $actionName;
		return $this->createIdentifierForFile($templatePathAndFilename, $prefix);
	}

	/**
	 * Resolve the template path and filename for the given action. If $actionName
	 * is NULL, looks into the current request.
	 *
	 * @param string $actionName Name of the action. If NULL, will be taken from request.
	 * @return string Full path to template
	 * @throws \TYPO3\Base\View\Exception\InvalidTemplateResourceException in case the template was not found
	 */
	protected function getTemplateSource($actionName = NULL) {
		;
	}

	/**
	 * Resolve the path and file name of the layout file, based on
	 * $this->layoutPathAndFilename and $this->layoutPathAndFilenamePattern.
	 *
	 * In case a layout has already been set with setLayoutPathAndFilename(),
	 * this method returns that path, otherwise a path and filename will be
	 * resolved using the layoutPathAndFilenamePattern.
	 *
	 * @param string $layoutName Name of the layout to use. If none given, use "Default"
	 * @return string Path and filename of layout file
	 * @throws \TYPO3\Base\View\Exception\InvalidTemplateResourceException
	 */
	protected function getLayoutSource($layoutName = 'Default'){
		;
	}

	/**
	 * Figures out which partial to use.
	 *
	 * @param string $partialName The name of the partial
	 * @return string the full path which should be used. The path definitely exists.
	 * @throws \TYPO3\Base\View\Exception\InvalidTemplateResourceException
	 */
	protected function getPartialSource($partialName){
		;
	}
	

	/**
	 * Resolve the template path and filename for the given action. If $actionName
	 * is NULL, looks into the current request.
	 *
	 * @param string $actionName Name of the action. If NULL, will be taken from request.
	 * @return string Full path to template
	 * @throws \TYPO3\Base\View\Exception\InvalidTemplateResourceException
	 */
	protected function getTemplatePathAndFilename($actionName = NULL) {
		if ($this->templatePathAndFilename !== NULL) {
			return $this->templatePathAndFilename;
		}
		if ($actionName === NULL) {
			$actionName = $this->controllerContext->getRequest()->getControllerActionName();
		}
		$actionName = ucfirst($actionName);

		$paths = $this->expandGenericPathPattern($this->templatePathAndFilenamePattern, FALSE, FALSE);
		foreach ($paths as &$templatePathAndFilename) {
			$templatePathAndFilename = str_replace('@action', $actionName, $templatePathAndFilename);
			if (file_exists($templatePathAndFilename)) {
				return $templatePathAndFilename;
			}
		}
		throw new \TYPO3\Base\View\Exception\InvalidTemplateResourceException('Template could not be loaded. I tried "' . implode('", "', $paths) . '"', 1225709595);
	}

	/**
	 * Returns a unique identifier for the resolved layout file.
	 * This identifier is based on the template path and last modification date
	 *
	 * @param string $layoutName The name of the layout
	 * @return string layout identifier
	 */
	protected function getLayoutIdentifier($layoutName = 'Default') {
		$layoutPathAndFilename = $this->getLayoutPathAndFilename($layoutName);
		$prefix = 'layout_' . $layoutName;
		return $this->createIdentifierForFile($layoutPathAndFilename, $prefix);
	}

	

	/**
	 * Resolve the path and file name of the layout file, based on
	 * $this->layoutPathAndFilename and $this->layoutPathAndFilenamePattern.
	 *
	 * In case a layout has already been set with setLayoutPathAndFilename(),
	 * this method returns that path, otherwise a path and filename will be
	 * resolved using the layoutPathAndFilenamePattern.
	 *
	 * @param string $layoutName Name of the layout to use. If none given, use "Default"
	 * @return string Path and filename of layout files
	 * @throws \TYPO3\Base\View\Exception\InvalidTemplateResourceException
	 */
	protected function getLayoutPathAndFilename($layoutName = 'Default') {
		if ($this->layoutPathAndFilename !== NULL) {
			return $this->layoutPathAndFilename;
		}
		$paths = $this->expandGenericPathPattern($this->layoutPathAndFilenamePattern, TRUE, TRUE);
		$layoutName = ucfirst($layoutName);
		foreach ($paths as &$layoutPathAndFilename) {
			$layoutPathAndFilename = str_replace('@layout', $layoutName, $layoutPathAndFilename);
			if (file_exists($layoutPathAndFilename)) {
				return $layoutPathAndFilename;
			}
		}
		throw new \TYPO3\Base\View\Exception\InvalidTemplateResourceException('The template files "' . implode('", "', $paths) . '" could not be loaded.', 1225709595);
	}

	/**
	 * Returns a unique identifier for the resolved partial file.
	 * This identifier is based on the template path and last modification date
	 *
	 * @param string $partialName The name of the partial
	 * @return string partial identifier
	 */
	protected function getPartialIdentifier($partialName) {
		$partialPathAndFilename = $this->getPartialPathAndFilename($partialName);
		$prefix = 'partial_' . $partialName;
		return $this->createIdentifierForFile($partialPathAndFilename, $prefix);
	}

	

	/**
	 * Resolve the partial path and filename based on $this->partialPathAndFilenamePattern.
	 *
	 * @param string $partialName The name of the partial
	 * @return string the full path which should be used. The path definitely exists.
	 * @throws \TYPO3\Base\View\Exception\InvalidTemplateResourceException
	 */
	protected function getPartialPathAndFilename($partialName) {
		$paths = $this->expandGenericPathPattern($this->partialPathAndFilenamePattern, TRUE, TRUE);
		foreach ($paths as &$partialPathAndFilename) {
			$partialPathAndFilename = str_replace('@partial', $partialName, $partialPathAndFilename);
			if (file_exists($partialPathAndFilename)) {
				return $partialPathAndFilename;
			}
		}
		throw new \TYPO3\Base\View\Exception\InvalidTemplateResourceException('The template files "' . implode('", "', $paths) . '" could not be loaded.', 1225709595);
	}

	/**
	 * Resolves the template root to be used inside other paths.
	 *
	 * @return string Path to template root directory
	 */
	public function getTemplateRootPath() {
		if ($this->templateRootPath !== NULL) {
			return $this->templateRootPath;
		} else {
			return str_replace('@packageResourcesPath', 'resource://' . $this->controllerContext->getRequest()->getControllerPackageKey(), $this->templateRootPathPattern);
		}
	}

	/**
	 * Set the root path to the partials.
	 * If set, overrides the one determined from $this->partialRootPathPattern
	 *
	 * @param string $partialRootPath Root path to the partials. If set, overrides the one determined from $this->partialRootPathPattern
	 * @return void
	 * @api
	 */
	public function setPartialRootPath($partialRootPath) {
		$this->partialRootPath = $partialRootPath;
	}

	/**
	 * Resolves the partial root to be used inside other paths.
	 *
	 * @return string Path to partial root directory
	 */
	protected function getPartialRootPath() {
		if ($this->partialRootPath !== NULL) {
			return $this->partialRootPath;
		} else {
			return str_replace('@packageResourcesPath', 'resource://' . $this->controllerContext->getRequest()->getControllerPackageKey(), $this->partialRootPathPattern);
		}
	}

	/**
	 * Set the root path to the layouts.
	 * If set, overrides the one determined from $this->layoutRootPathPattern
	 *
	 * @param string $layoutRootPath Root path to the layouts. If set, overrides the one determined from $this->layoutRootPathPattern
	 * @return void
	 * @api
	 */
	public function setLayoutRootPath($layoutRootPath) {
		$this->layoutRootPath = $layoutRootPath;
	}

	/**
	 * Resolves the layout root to be used inside other paths.
	 *
	 * @return string Path to layout root directory
	 */
	protected function getLayoutRootPath() {
		if ($this->layoutRootPath !== NULL) {
			return $this->layoutRootPath;
		} else {
			return str_replace('@packageResourcesPath', 'resource://' . $this->controllerContext->getRequest()->getControllerPackageKey(), $this->layoutRootPathPattern);
		}
	}

	
	/**
	 * Returns a unique identifier for the given file in the format
	 * <PackageKey>_<SubPackageKey>_<ControllerName>_<prefix>_<SHA1>
	 * The SH1 hash is a checksum that is based on the file path and last modification date
	 *
	 * @param string $pathAndFilename
	 * @param string $prefix
	 * @return string
	 */
	protected function createIdentifierForFile($pathAndFilename, $prefix) {
		$request = $this->controllerContext->getRequest();
		$packageKey = $request->getControllerPackageKey();
		$subPackageKey = $request->getControllerSubpackageKey();
		if ($subPackageKey !== NULL) {
			$packageKey .= '_' . $subPackageKey;
		}
		$controllerName = $request->getControllerName();
		$templateModifiedTimestamp = filemtime($pathAndFilename);
		$templateIdentifier = sprintf('%s_%s_%s_%s', $packageKey, $controllerName, $prefix, sha1($pathAndFilename . '|' . $templateModifiedTimestamp));
		return $templateIdentifier;
	}

	/**
	 * Initializes this view.
	 *
	 * @return void
	 * @api
	 */
	public function initializeView(){
		;
	}
}

?>
