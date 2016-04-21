<?php
require_once('class.DhbwSSOErrorhandler.php');

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
 * This is the verboseErrorhandler. It makes redirects and output an error directly.
 * There are two other errorhandlers in this package.
 *
 * @author Jan Kristoffer Roth <roth@dhbw-mosbach.de>
 * @copyright Copyright (c) 2011, DHBW Mosbach, Mosbach, Germany
 * @version 0.1.0
 * @package dhbwnawsigsso
 * @subpackage errorhandler
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class DhbwSSOVerboseErrorhandler extends DhbwSSOErrorhandler {
	
	public function __construct() {
		parent::__construct();
	}	
	
	/**
	 * Process an error
	 *
	 * Do something with the error...
	 * This is an debug Errorhandler. It returns DEBUG Output and do non redirects.
	 * Use other Errorhandler or your own to do something else.
	 * @param string $key the errorcode
	 * @param string $additional additional error-informations
	 * @return boolean always false
	 */		
	public function processError($key, $additional = "") {			
		if ($this->isUrl($key)) {
			header("Location: ".$this->errorMessages[$key]);
		}
		else {
			printf('<br />'.$this->getErrorMessage());
		}	
		die();
		return false;
	}
		
	/**
	 * Prints some debugging if enabled
	 *
	 * Direct output of debug-informations to client.
	 * Disable DEBUG for production-use
	 * @param string $msg the label
	 * @param mixed $data debug-informations
	 * @return void
	 */			
	protected function debug($msg, $data = true) {
		if (self::DEBUG) {
			printf('<br />DEBUG: '.$msg.': ');
			var_dump($data);
		}
	}
}

?>