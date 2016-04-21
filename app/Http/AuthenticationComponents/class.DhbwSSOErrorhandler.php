<?php
/** 
 * Errorhandler for Signature-Based Single Sign-On Frameworks SSO Agent (PHP)
 *
 * Copyright notice
 *
 * (c) 2011 Jan Kristoffer Roth <roth@dhbw-mosbach.de>, DHBW Mosbach
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 *
 * This is the default errorhandler. It is silent and only do redirects if defined.
 * There are two other errorhandlers in this package.
 *
 * @author Jan Kristoffer Roth <roth@dhbw-mosbach.de>
 * @copyright Copyright (c) 2011, DHBW Mosbach, Mosbach, Germany
 * @version 0.1.0
 * @package dhbwnawsigsso
 * @subpackage errorhandler
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class DhbwSSOErrorhandler {
	/**
	 * Last occurred error
	 *
	 * An array if a error is occurred, otherwise false.
	 * @var mixed
	 */
	protected $lastError = false;	

	/**
	 * Error messages
	 *
	 * An array with all defined error messages
	 * @var array
	 */
	protected $errorMessages;	
	
	/**
	 * Contructor for this class
	 *
	 * Set all default error messages. 
	 * @return void
	 */	
	public function __construct() {
		$this->errorMessages = array(
			"no_config" => "sigsso: file access error - sigsso config file",
			"user_missing" => "sigsso: Invocation error - missing USER",
			"tpaid_missing" => "sigsso: Invocation error - missing TPA_ID",
			"expires_missing" => "sigsso: Invocation error - missing ExpirationTime",
			"signature_missing" => "sigsso: Invocation error - missing signature",
			"sslkey_missingconf" => "sigsso: error in configfile - missing public_ssl_key",
			"usedtokens_missingconf" => "sigsso: error in configfile - missing tokensfile entry",
			"logfile_missingconf" => "sigsso: error in configfile - missing logfile",
			"tmp_signature_dir_missingconf" => "sigsso: error in configfile - missing tmp_signature_dir",
			"sslkey_missingfile" => "sigsso: file access error - SSL public key file",
			"usedtokens_missingfile" => "sigsso: file access error - UsedTokens file",
			"logfile_missingfile" => "sigsso: file access error - log file",
			"tmp_signature_dir_missingfile" => "sigsso: file access error - tmp signature file",
			"adapter_missingfile" => "sigsso: file access error - adapter not found",
			"tpaid_unknown" => "sigsso: validation error - TPA_ID is invalid or not configured",
			"usedtokens_allreadyused" => "sigsso: validation error - SSO Link has been used before",
			"signature_invalid" => "sigsso: validation error - signature invalid",
			"expires_exeeded" => "sigsso: validation error - SSO Link expired (or system clock out of sync?)!",
			"tpa_error" => "sigsso: An error in the Third Party Application Adapter occurred. It said: "
		);
	}

	/**
	 * Setter for error messages
	 *
	 * Overrides an error message
	 * @param string $key message id
	 * @param string $value messagetext
	 * @return boolean
	 */	
	final public function setErrorMessage($key, $value) {
		if (isset($this->errorMessages[$key])) {
			$this->errorMessages[$key] = $value;
			return true;
		}
		return false;
	}

	/**
	 * Checks if an URL
	 *
	 * If the defined error message is an url return true
	 * @param string $key of the message
	 * @return boolean
	 */		
	protected function isUrl($key) {
		if (isset($this->errorMessages[$key])) {
			return (boolean)preg_match("/^https?:\/\//", $this->errorMessages[$key]);
		}
		return false;
	}
	
	/**
	 * Throw an error
	 *
	 * Store lastError and displays it if enabled or redirects to defined errorpage
	 * @param string $key the errorcode
	 * @param string $additional additional error-informations
	 * @return boolean always false
	 */			
	final public function throwError($key, $additional = "") {
		$this->lastError = array($key, $additional);
		$this->processError($key, $additional);
		return false;
	}
	
	/**
	 * Checks for occurred errors
	 *
	 * @return boolean
	 */			
	final public function hasError() {
		return ($this->lastError !== false);
	}
	
	/**
	 * Process an error
	 *
	 * Do something with the error...
	 * This is an silent Errorhandler. It only redirects if an URL.
	 * Use another Errorhandler or your own to do something else.
	 * @param string $key the errorcode
	 * @param string $additional additional error-informations
	 * @return boolean always false
	 */		
	public function processError($key, $additional = "") {			
		if ($this->isUrl($key)) {
			header("Location: ".$this->errorMessages[$key]);
			die();
		}
		return false;
	}
	
	/**
	 * Getter for error message
	 *
	 * This method returns the errormessage.
	 * @return string
	 */		
	public function getErrorMessage() {
		if ($this->hasError()) {
			$additional = "";
			if (isset($this->lastError[1]) && !empty($this->lastError[1])) {
				$additional = " (".$this->lastError[1].")";
			}
			if (isset($this->errorMessages[$this->lastError[0]])) {
				return $this->errorMessages[$this->lastError[0]].$additional;
			}
			else {
				return "sigsso: unknown error '".$this->lastError[0]."'".$additional."!! Be sure that you have called 'parent::__construct' in your errorhandler!";
			}
		}
		return "";
	}
	
	/**
	 * Getter for error messages
	 *
	 * This method returns all errormessages.
	 * @return string
	 */		
	public function getErrorMessages() {
		return $this->errorMessages;
	}
}

?>