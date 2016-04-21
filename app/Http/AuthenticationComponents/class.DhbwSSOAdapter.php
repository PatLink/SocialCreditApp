<?php

/**
 * Adapter for Signature-Based Single Sign-On Framework
 * SSO Agent (PHP)
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
 * @author Jan Kristoffer Roth <roth@dhbw-mosbach.de>
 * @copyright Copyright (c) 2011, DHBW Mosbach, Mosbach, Germany
 * @version 0.1.0
 * @package dhbwnawsigsso
 * @subpackage adapter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class DhbwSSOAdapter {
		
	/**
	 * Type of the adapter
	 *
	 * There are three adaptertypes. The classic ones (php and cmd) and the new one (class)
	 * @var string
	 */
	protected $type = "class";
	
	/**
	 * Uri of the adapter
	 * 
	 * If you use an oldstyle adapter the uri to the adapter will be stored here
	 * @var string
	 */
	protected $uri = "";
	
	/**
	 * Params of the adapter
	 *
	 * Params are stored here
	 * @var array
	 */
	protected $params = array();
	
	/**
	 * Constructor
	 *
	 * Create a new adapter. You can do this via code or via configfile given to the Agentconfig
	 * @param string $type The adaptertype
	 * @param string $uri The optional adapterfile
	 * @param array $params Optional parameters like redirect uri etc.
	 * @return void
	 */	
	public function __construct($type = "class", $uri = "", $params = array()) {
		$this->setType($type);
		$this->setUri($uri);
		$this->setParams($params);
	}
	
	/**
	 * Setter for type
	 *
	 * @param string $type type
	 * @return void
	 */
	public function setType($type) {
		$this->type = strtolower($type);
	}

	/**
	 * Getter for type
	 *
	 * @return string type
	 */
	public function getType() {
		return $this->type;
	}
	/**
	 * Setter for uri
	 *
	 * @param string $uri uri
	 * @return void
	 */
	public function setUri($uri) {
		$this->uri = $uri;
	}

	/**
	 * Getter for uri
	 *
	 * @return string uri
	 */
	public function getUri() {
		return $this->uri;
	}
	/**
	 * Setter for params
	 *
	 * @param array $params params
	 * @return void
	 */
	public function setParams($params) {
		$this->params = $params;
	}

	/**
	 * Getter for params
	 *
	 * @return array params
	 */
	public function getParams() {
		return $this->params;
	}
	
	/**
	 * Getter for param
	 *
	 * @param string $key
	 * @return string value
	 */
	public function getParam($key) {
		if (isset($this->params[$key])) {
			return $this->params[$key];
		}
		return null;
	}
}
?>