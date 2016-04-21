<?php
require_once('class.DhbwSSOErrorhandler.php');

/** 
 * Configobject for Signature-Based Single Sign-On Frameworks SSO Agent (PHP)
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
 * Usage:
 * <code>
 * <?php
 * $sigssoAgentConfig = new DhbwSSOAgentConfig();
 * $sigssoAgentConfig->setWindowsServer(false);
 * $sigssoAgentConfig->setPublicSSLKey('/usr/local/sigsso/etc/sigsso_public.key');
 * $sigssoAgentConfig->setTokensfile('/usr/local/sigsso/etc/usedtokens.txt');
 * $sigssoAgentConfig->setLogfile('/var/log/sigsso.log');
 * $sigssoAgentConfig->setTmpSignatureDir('/tmp');
 * $sigssoAgentConfig->setTmpSignaturePrefix('sign_');
 * $sigssoAgentConfig->setLoglevel(4);
 * $sigssoAgentConfig->setExternalOpenSSL(true);
 * ?>
 * </code>
 *
 * @author Jan Kristoffer Roth <roth@dhbw-mosbach.de>
 * @copyright Copyright (c) 2011, DHBW Mosbach, Mosbach, Germany
 * @version 0.1.0
 * @package dhbwnawsigsso
 * @subpackage agent
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class DhbwSSOAgentConfig {
		
	/**
	 * Configarray
	 *
	 * Array with parsed data from configfile
	 * @var array
	 */
	protected $config;
	
	/**
	 * Errorhandler
	 *
	 * The Errorhandler
	 * @var DhbwSSOErrorhandler
	 */
	protected $errorhandler = false;
	
	/**
	 * Constructor
	 *
	 * Reads and checks all needed default-values, request-vars and inifiles
	 * @param string $configfile the configfile to parse (optional)
	 * @param DhbwSSOErrorhandler $errorhandler an alternative errorhandler
	 * @return void
	 */	
	public function __construct($configfile = false, $errorhandler = false) {
		if (is_a($errorhandler, "DhbwSSOErrorhandler")) {
			$this->errorhandler = $errorhandler;
		}
		else {
			$this->errorhandler = new DhbwSSOErrorhandler();
		}
		$this->config["settings"] = array();
		$this->config["tpas"] = array();
		$this->config["settings"]["loglevel"] = 0;
		$this->config["settings"]["windows_server"] = false;
		$this->config["settings"]["externalOpenssl"] = false;
		$this->config["settings"]["tmp_signature_dir"] = "/tmp";
		$this->config["settings"]["tmp_signature_prefix"] = "sign_";
		
		// Load the configfile
		if ($configfile) {
			if (file_exists($configfile)) {
				$this->initConfigfile($configfile);
			}
			else {
				$this->errorhandler->throwError("no_config", $configfile);
			}
		}
	}

	/**
	 * Reads the configfile if exists
	 *
	 * @return void
	 */			
	protected function initConfigfile($configfile) {
		$lines = file($configfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		if ($lines === false) {
			return false;
		}
		$section = '';
		foreach ($lines as $line) {
			if (preg_match("/^\[(\S+?)\]$/", $line, $matches)) {
				$section = strtolower($matches[1]);
				continue;
			}
			if ((strpos(trim($line), "#") === 0) || empty($section)) {
				continue;
			}
			$entry = explode(":",$line,2);
			if (method_exists($this, "parseSection".ucfirst($section))) {
				call_user_func(array($this, "parseSection".ucfirst($section)), $entry);
			}
		}
	}	
	
	/**
	 * Parses an entry from the configfiles global-section
	 *
	 * @param array entry
	 * @return void
	 */	
	protected function parseSectionGlobal(array $entry) {
		$this->config['settings'][trim($entry[0])] = trim($entry[1]);
	}	
		
	/**
	 * Gets the errorhandler
	 *
	 * Returns the assigned errorhandler for errormessages.
	 * @return DhbwSSOErrorhandler
	 */	
	public function getErrorhandler() {
		return $this->errorhandler;
	}		
			
	/**
	 * Sets the errorhandler
	 *
	 * @return void
	 */	
	public function setErrorhandler($errorhandler) {
		$this->errorhandler = $errorhandler;
	}	
	
	/**
	 * Sets the path to the public key
	 *
	 * Should be outside htdocs
	 * @param string $publicSSLKey
	 * @return void
	 */	
	public function setPublicSSLKey($publicSSLKey) {
		$this->config["settings"]["public_ssl_key"] = $publicSSLKey;
	}	
		
	/**
	 * Sets the path to the used-tokens file
	 *
	 * Should be outside htdocs
	 * @param string $tokensfile
	 * @return void
	 */	
	public function setTokensfile($tokensfile) {
		$this->config["settings"]["tokensfile"] = $tokensfile;
	}
		
	/**
	 * Sets the path to the log file
	 *
	 * Should be outside htdocs
	 * @param string $logfile
	 * @return void
	 */	
	public function setLogfile($logfile) {
		$this->config["settings"]["logfile"] = $logfile;
	}
		
	/**
	 * Sets the loglevel
	 *
	 * 0: no logging
	 * 1: errors (tpa_id + user)
	 * 2: success and errors (tpa_id + user)
	 * 3: errors (tpa_id + user + expires + signature)
	 * 4: success and errors (tpa_id + user + expires + signature)
	 * @param integer $loglevel
	 * @return void
	 */	
	public function setLoglevel($loglevel) {
		$this->config["settings"]["loglevel"] = $loglevel;
	}
			
	/**
	 * Use externam openSSL
	 *
	 * Must be in %path%
	 * @param boolean $externalOpenSSL
	 * @return void
	 */	
	public function setExternalOpenSSL($externalOpenSSL) {
		$this->config["settings"]["externalOpenssl"] = $externalOpenSSL;
	}
			
	/**
	 * Windows-switch
	 *	 
	 * This is a windows Server
	 * @param boolean $windowsServer
	 * @return void
	 */	
	public function setWindowsServer($windowsServer) {
		$this->config["settings"]["windows_server"] = $windowsServer;
	}
			
	/**
	 * Sets the temporary signature directory
	 *
	 * If you use external openSSL you need an temporary directory to save the signatures
	 * @param string $tmpSignatureDir
	 * @return void
	 */	
	public function setTmpSignatureDir($tmpSignatureDir) {
		$this->config["settings"]["tmp_signature_dir"] = $tmpSignatureDir;
	}
			
	/**
	 * Sets the prefix for temporary signaturefile
	 *
	 * @param string $tmpSignaturePrefix
	 * @return void
	 */	
	public function setTmpSignaturePrefix($tmpSignaturePrefix) {
		$this->config["settings"]["tmp_signature_prefix"] = $tmpSignaturePrefix;
	}
									
	/**
	 * Parses an entry from the configfiles errorcodes-section
	 *
	 * Override a default errorcode
	 * @param array entry
	 * @return void
	 */		
	protected function parseSectionErrorCodes(array $entry) {
		if (strlen(trim($entry[1])) == 0) return;
		$this->getErrorhandler()->setErrorMessage(trim($entry[0]), trim($entry[1]));
	}
	
	/**
	 * Parses an entry from the configfiles main-section
	 *
	 * Makes a new instance of {@link DhbwSSOAdapter} for each adapter
	 * @param array entry
	 * @return void
	 */	
	protected function parseSectionMain(array $entry) {
		$tpa = new DhbwSSOAdapter();
		
		if (!preg_match('/^(\S+?):\/\/(\S+?)\s/', trim($entry[1]), $matches)) {
			if (!preg_match('/^class(\s|$)/', strtolower(trim($entry[1])))) {
				return;
			}
		}
		else {
			$tpa->setType(strtolower($matches[1]));
			$tpa->setUri($matches[2]);
			if (!(bool)$this->config["settings"]["windows_server"]) {
				$tpa->setUri('/'.$tpa->getUri());
			}			
		}		
		
		$parts = preg_split("/\s+/", trim($entry[1]));
		$params = array();
		foreach($parts as $part) {
			if (preg_match('/--(\S+?)=(\S+?)$/', $part, $_params)) {
				$params[$_params[1]] = $this->replaceVars($_params[2]);
				
			}
		}
		$tpa->setParams($params);
		
		$this->config['tpas'][trim($entry[0])] = $tpa;
	}	
	
	/**
	 * Replaces configfiles-variables
	 *
	 * This method  replaces the three placeholders
	 * %remote% = clients remote address
	 * %agent% = clients user agent
	 * %user% = the username
	 * @param string $value an variable
	 * @return string
	 */		
	protected function replaceVars($value) {
		$value = str_replace("%remote%", $_SERVER["REMOTE_ADDR"], $value);
		$value = str_replace("%agent%", "\"".$_SERVER["HTTP_USER_AGENT"]."\"", $value);
		$value = str_replace("%user%", "\"".DhbwSSOAgent::getVar('user')."\"", $value);
		return $value;
	}
	
	/**
	 * Adds an adapter
	 *
	 * Possibility to add a third party adapter (tpa) without the configfile
	 * Useful for thirdparty auth-extensions and class-type-adapters
	 * @param string $tpaId id of the tpa
	 * @param DhbwSSOAdapter $adapter
	 * @return boolean
	 */		
	public function addAdapter($tpaId, $adapter) {
		if (!isset($this->config["tpas"][$tpaId])) {
			$this->config["tpas"][$tpaId] = $adapter;
			return true;
		}
		return false;
	}

	/**
	 * Gets a setting-variable
	 *
	 * @param string $key
	 * @return string
	 */		
	public function getSetting($key) {
		if (isset($this->config["settings"])) {
			return $this->getValue("settings", $key);
		}
		return null;
	}		
	
	/**
	 * Gets a adapter
	 *
	 * @param string $key
	 * @return string
	 */		
	public function getTpa($key) {
		if (isset($this->config["tpas"])) {
			return $this->getValue("tpas", $key);
		}
		return null;
	}
	
	/**
	 * Gets a config-setting
	 *
	 * @param string $section
	 * @param string $key
	 * @return string
	 */		
	protected function getValue($section, $key) {
		if (isset($this->config[$section][$key])) {
			return $this->config[$section][$key];
		}
		return null;
	}	
}
?>