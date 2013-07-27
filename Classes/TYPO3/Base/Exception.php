<?php
namespace TYPO3\Base;

/*                                                                        *
 * This script belongs to the TYPO3  package "Base".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */


/**
 * The most general Fluid exception.
 *
 * @api
 */
class Exception extends \Exception {

  /**
	 * @var string
	 */
	protected $referenceCode;

	/**
	 * @var integer
	 */
	protected $statusCode = 500;

	/**
	 * Returns a code which can be communicated publicly so that whoever experiences the exception can refer
	 * to it and a developer can find more information about it in the system log.
	 *
	 * @return string
	 * @api
	 */
	public function getReferenceCode() {
		if (!isset($this->referenceCode)) {
			$this->referenceCode = date('YmdHis' , $_SERVER['REQUEST_TIME']) . substr(md5(rand()), 0, 6);
		}
		return $this->referenceCode;
	}

	/**
	 * Returns the HTTP status code this exception corresponds to (defaults to 500).
	 *
	 * @return integer
	 * @api
	 */
	public function getStatusCode() {
		return $this->statusCode;
	}
}

?>
