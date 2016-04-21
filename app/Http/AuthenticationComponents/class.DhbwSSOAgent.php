<?php
require_once('class.DhbwSSOAgentConfig.php');
require_once('class.DhbwSSODebugErrorhandler.php');
require_once('class.DhbwSSOAdapter.php');

/**
 * SSO Agent (PHP) for Signature-Based Single Sign-On Framework
 *
 * This is an php agent for Typo3-extension naw_single_signon.
 * With this agent it is possible to use the old-style adapters for third party applications or integrate it in an third party auth-plugin as real class.
 * To use it as old-style agent you need also the file {@link sigsso_0.8.0.php} or above.
 *
 * Copyright notice
 *
 * (c) 2003-2006 Dietrich Heise <heise@naw.de>, net&works GmbH (<= 0.7.3)<br />
 * (c) 2011 Jan Kristoffer Roth <roth@dhbw-mosbach.de>, DHBW Mosbach (>= 0.8.0)
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
 * Old style usage:
 * <code>
 * <?php
 * $sigssoConfig = '/usr/local/sigsso/etc/sigsso.conf'
 * $sigsso = new DhbwSSOAgent(new DhbwSSOAgentConfig(
 *		$sigssoConfig, 
 *		new DhbwSSOVerboseErrorhandler)
 * );
 * $include = $sigsso->getInclude();
 * if ($include) {
 *         include_once($include);
 * }
 *
 * $sigsso->execute();
 * ?>
 * </code>
 *
 * New style usage:
 * <code>
 * <?php
 * $sigssoAgentConfig = new DhbwSSOAgentConfig();
 * [ .. configurationoptions .. ]
 *
 * $sigsso = new DhbwSSOAgent($sigssoAgentConfig);
 * if ($sigsso->execute()) {
 * 		$username = $sigsso->getUsername();
 *		[ .. do something with this valid username .. ]
 * }
 * else {
 *		echo $sigsso->getConfig()->getErrorhandler()->getErrorMessage();
 * }
 * ?>
 * </code>
 *
 * @author Jan Kristoffer Roth <roth@dhbw-mosbach.de>
 * @copyright Copyright (c) 2011, DHBW Mosbach, Mosbach, Germany
 * @version 0.8.0
 * @package dhbwnawsigsso
 * @subpackage agent
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class DhbwSSOAgent {	
	/**
	 * Debugging switch
	 *
	 * If true the class will output more informationsand redirects are disabled.
	 * NOTE: if true the {@link DhbwSSODebugErrorhandler} is assigned automatically
	 */
	const DEBUG = false;
	
	/**
	 * Versionstring
	 *
	 * The current version of this class
	 */
	const VERSION = "0.8.0";
	
	/**
	 * Object of DhbwSSOAgentConfig
	 *
	 * Stores the object with the configuration.
	 * Use {@link getConfig()} to access.
	 * @var DhbwSSOAgentConfig
	 */
	protected $config;
	
	/**
	 * The username given by the request
	 *
	 * Here is the place where the (nonvalidated) username is stored.
	 * Use {@link getUsername()} after {@link execute()} to get the validated one.
	 * @var string
	 */
	protected $user;
	
	/**
	 * The id of the third party agent given by the request
	 *
	 * Here is the place where the (nonvalidated) thirdparty adapter ID is stored.
	 * @var string
	 */
	protected $tpa;
	
	/**
	 * The expiretime given by the request
	 *
	 * Here is the place where the (nonvalidated) expire-timestamp is stored.
	 * @var integer
	 */
	protected $expires;
	
	/**
	 * Serverversion
	 *
	 * The (nonvalidated) versionstring of the sso-server given by the request (since version 2)
	 * @var string
	 */
	protected $version;
	
	/**
	 * Adapterversion
	 *
	 * The version of the sso-adapter given by the requested tpaId (since version 2).
	 * Only available after successful {@link execute()}.
	 * Use {@link getAdapterVersion()} after {@link execute()} to get the validated one.
	 * @var array
	 */
	protected $adapterVersion = array(1, 0);
	
	/**
	 * The requested action
	 *
	 * The (nonvalidated) name of the action given by the request (since version 2)	 
	 * Not really used at the moment. Default is logon.
	 * Use {@link getAction()} after {@link execute()} to get the validated one.
	 * @var string
	 */
	protected $action;
	
	/**
	 * Optional Flags
	 *
	 * The flags given by the request (since version 2).
	 * Use {@link getFlags()} after {@link execute()} to get the validated one.
	 * @var string
	 */
	protected $_flags;
	
	/**
	 * Optional Flags
	 *
	 * Parsed flags.
	 * Use {@link getFlags()} after {@link execute()} to get the validated one.
	 * @var array
	 */
	protected $flags;

	/**
	 * Optional userdata 
	 *
	 * Additional userdata given by the request (since version 2)
	 * taken from the Typo3 fe-, be_users table if configurated.
	 * Use {@link getUserdata()} after {@link execute()} to get the validated one.
	 * @var string
	 */
	protected $_userdata;
	
	/**
	 * Optional userdata 
	 *
	 * Parsed userdata
	 * Use {@link getUserdata()} after {@link execute()} to get the validated one.
	 * @var array
	 */
	protected $userdata;
	
	/**
	 * The openSSL signature
	 *
	 * Given by the request and generated with servers private-key.
	 * Public-key to verify must be part of this implementation (path set in config).
	 * @var string
	 */
	protected $signature;
	
	/**
	 * Execute switch
	 *
	 * This property is always true after first execution,
	 * use {@link execute()} to check if validation was successful or not.
	 * @var boolean
	 */
	protected $executed = false;

	/**
	 * Request vars
	 *
	 * @var array
	 */
	protected $vars = array();

	/**
	 * Initialize this agent.
	 *
	 * Make a new instance of this agent.
	 * @param DhbwSSOAgentConfig $config here are all configuration
	 * @return void
	 */	
	public function __construct(DhbwSSOAgentConfig $config) {
		if (is_a($config, "DhbwSSOAgentConfig")) {
			$this->config = $config;
			if (self::DEBUG) {
				$this->config->setErrorhandler(new DhbwSSODebugErrorhandler($this->config->getErrorhandler()));
			}
			$this->checkConfig();
		}
		else {
			$this->debug("Fatal Error: 'Config' is not an instance of DhbwSSOAgentConfig", $config);
			die();
		}
		$this->debug("used errorhandler", get_class($this->config->getErrorhandler()));
		// Get all request-vars
		$this->initVars();
		$this->checkVars();		
	}
	
	/**
	 * Getter for the username
	 *
	 * If executed with {@link execute()} and no error occurred this function will return the validated username, otherwise false
	 * @return mixed
	 */			
	public function getUsername() {
		if ($this->execute()) {
			return $this->user;
		}
		return false;
	}		
	
	/**
	 * Getter for the flags
	 *
	 * If executed with {@link execute()} and no error occurred this function will return an array with validated flags, otherwise false
	 * @return mixed
	 */	
	public function getFlags() {
		if ($this->execute()) {
			return $this->flags;
		}
		return false;
	}

	/**
	 * Getter for the userdata
	 *
	 * If executed with {@link execute()} and no error occurred this function will return a string with validated userdata, separated by | and = for pairs, otherwise false
	 * @return mixed
	 */		
	public function getUserdata() {
		if ($this->execute()) {
			return $this->userdata;
		}
		return false;
	}
	
	/**
	 * Getter for create_modify flag
	 *
	 * If executed with {@link execute()} and no error occurred this function will return the value of create_modify user. Given by the serverconfig.
	 * @return boolean
	 */	
	public function canCreateModify() {
		if ($this->execute()) {
			return (boolean)$this->flags["create_modify"];
		}
		return false;
	}	

	/**
	 * Makes a new log-entry
	 * 
	 * This function makes a new log-entry at the logfile.
	 * Logformat: D M j G:i:s T Y key:value [key:value ...]
	 * @param array $values array with pairs with loginfomations
	 * @return void
	 */	
	protected function logIt(array $values) {
		$logging = @fopen($this->config->getSetting('logfile'), "a");
		if (!$logging){            // can't open config
			return $this->throwError("logfile_missingfile");
		}
		if ($this->config->getSetting('loglevel') > 2) {
			$values["TIMESTAMP"] = $this->expires;
			$values["SIGNATURE"] = self::getVar('signature');
		}
		$msg = date("D M j G:i:s T Y");
		foreach ($values as $key=>$value) {
			if (!empty($value)) {
				$msg .= " ".$key.":".$value;
			}
		}
		fwrite($logging, $msg."\n");
		fclose($logging);
	}

	/**
	 * Gives information about validation
	 *
	 * Checks the requested variables and make a include of the adapter if necessary.
	 * Sends the cookies and makes the redirect if defined by a adapter or method.
	 * Must also be used to check if transfered request-parameters are valid befor {@link getUsername()} return a valid username.
	 * @return boolean
	 */		
	public function execute() {
		if (!$this->executed) {
			// Decode UserData (maybe later)?
			if (!$this->decodeData()) return $this->endExecution();
			
			if (!$this->config->getErrorhandler()->hasError()) {
				$tpa = $this->config->getTpa($this->tpa);
				if (method_exists($this, "sso".ucfirst($tpa->getType('type')))) {
					$this->debug("adapterType", $tpa->getType('type'));
					$ssoValues = call_user_func(array($this, "sso".ucfirst($tpa->getType('type'))));
				}
				
				$this->debug("ExecuteResult", $ssoValues);
				if (isset($ssoValues['Error']) && $ssoValues['Error'] != ""){
					return $this->throwError("tpa_error", $ssoValues['Error']);
				}
				else {
					if ($this->config->getSetting('loglevel') > 1){
						$this->logIt(array("IP" => $_SERVER["REMOTE_ADDR"], "USER" => $this->user, "TPA_ID" => $this->tpa));
					}
					
					if (is_array($ssoValues)) {
						foreach ($ssoValues as $value) {
							if (is_array($value)) {
								if (isset($value["CookieName"])) {
									if (self::DEBUG) {
										$this->debug("cookie would be set now", $value);
									}
									else {
										$cookie = setcookie($value["CookieName"], $value["CookieValue"], $value["CookieExpires"], ((isset($value[$i]["CookiePath"]))?$value["CookiePath"]:'/'), $value["CookieDomain"], $value["CookieSecure"]);
									}
								}
							}
						}
						
						if (isset($ssoValues["redirecturl"])) {
							if (self::DEBUG) {
								$this->debug("redirect would be set now", $ssoValues["redirecturl"]);
							}
							else {
								header("Location: ".$ssoValues["redirecturl"]);
								die();
							}
						}
					}
				}
			}
		}
		return $this->endExecution();
	}
	
	/**
	 * Finalize the execution
	 *
	 * Internal method to finalize the execution
	 * @return boolean
	 */
	protected function endExecution() {
		if ($this->executed === false) {
			$this->debug("execution returns", !$this->config->getErrorhandler()->hasError());
		}
		$this->executed = true;
		return (!$this->config->getErrorhandler()->hasError());
	}
	
	/**
	 * Determine the version
	 
	 * Internal method to parse the version from version-string. Returns an array with each number.
	 * @param string the versions-string
	 * @return array
	 */	
	protected function parseAdapterVersion($versionString = "1.0") {
		$adapterVersion = array(1, 0);	
		$version = array();
		if (preg_match("/(\d+\.\d+)/", $versionString, $version)) {
			$adapterVersion = explode(".", $version[1]);
		}
		$this->debug("adapterVersion", implode(".", $adapterVersion));
		$this->adapterVersion = $adapterVersion;
		return $adapterVersion;
	}
	
	/**
	 * Sso-process for type class
	 *
	 * Is called if the adaptertype is set to class.
	 * Means that no php-adapter or cmd-program is called.
	 * Could be overridden, but in most cases {@link getUsername()} is enough
	 * @return array
	 */
	protected function ssoClass() {
		$this->adapterVersion = array(3, 0);
		return array();
	}
	
	/**
	 * Sso-process for type cmd
	 *
	 * If you use an non-php thirdparty adapter this method is internally called.
	 * Look at {@link http://www.single-signon.com www.single-signon.com} for reference-adapters
	 * @return array
	 */	
	protected function ssoCmd() {
		if (!file_exists($this->config->getTpa($this->tpa)->getUri())) {
			return $this->throwError("adapter_missingfile");
		}

		exec($this->config->getTpa($this->tpa)->getUri().' --get_version'."\n", $version);
		$adapterVersion = $this->parseAdapterVersion($version[0]);
				
		$cmd = $this->config->getTpa($this->tpa)->getUri();
		foreach($this->config->getTpa($this->tpa)->getParams() as $key=>$value) {
			$cmd .= " --".$key."=".$value;
		}	
		
		if ($adapterVersion[0] > 1) {
			switch($this->action){
				case 'logon':
					if ((boolean)$this->flags["create_modify"]) {
						$cmdCreateModify = $cmd." --version=".$this->version." --action=create_modify --userdata="."\"".$this->_userdata."\"";
						exec($cmdCreateModify."\n", $return);
						$this->debug('Command executed', $cmdCreateModify);
						$this->debug('Return values', $return);
					}
					if (!$return[0]) {
						$cmdLogon = $cmd." --version=".$this->version." --action=logon --userdata="."\"".$this->_userdata."\"";
						exec($cmdLogon."\n", $return);
						$this->debug('Command executed', $cmdLogon);
						$this->debug('Return values', $return);
					}
				break;
			}
		}
		else {
			$return ='';		
			exec ($cmd."\n", $return);
			$this->debug("Command executed", $cmd);
			$this->debug("Return values", $return);
		}
		
		$ssoValues = array();
		
		$cookie = array();
		foreach ($return as $i){
			$pieces = preg_split("/[\ \t]+/", $i, 2);  // split char whitespace
			if (in_array($pieces[0], array('redirecturl', 'Error'))) {
				$ssoValues[$pieces[0]] = $pieces[1];
				continue;
			}
			if (in_array($pieces[0], array('CookieName', 'CookieValue', 'CookieExpires', 'CookiePath', 'CookieDomain', 'CookieSecure'))) {
				$cookie[$pieces[0]] = $pieces[1];
			}
		}
		if (count($cookie) > 0) {
			$ssoValues[] = $cookie;
		}
		return $ssoValues;
	}
	
	/**
	 * Getter for include
	 *
	 * Return a filepath for include an oldstyle phpadapter
	 * @return mixed
	 */	
	public function getInclude() {
		if ($this->getConfig()->getTpa($this->tpa)->getType() == "php") {
			if (!file_exists($this->getConfig()->getTpa($this->tpa)->getUri()) || is_dir($this->getConfig()->getTpa($this->tpa)->getUri())) {
				return $this->throwError("adapter_missingfile");
			}
			return $this->getConfig()->getTpa($this->tpa)->getUri();
		}
		return false;
	}
	
	/**
	 * Sso-process for type php
	 *
	 * If u use an old php thirdparty adapter (< 3.0) this method is internally called.
	 * Look at {@link http://www.single-signon.com www.single-signon.com} for reference-adapters
	 * @return array
	 */
	protected function ssoPhp() {

		
		//$include = include($this->getConfig()->getTpa($this->tpa)->getUri());
		//$this->debug("Include '".$this->getConfig()->getTpa($this->tpa)->getUri()."'", $include);
		if (function_exists('get_version')) {
			$adapterVersion = get_version();
		}
		$adapterVersion = $this->parseAdapterVersion($adapterVersion);
		
		if (!function_exists('sso')) {
			return array("Error" => "Function 'sso' not found! Did you make the include?");
		}
		
		if ($adapterVersion[0] > 1) {
			$ssoValues = false;
			switch ($this->action) {
				case 'logon':
					if ((boolean)$this->flags["create_modify"]) {
						$ssoValues = sso($this->user, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"], $this->config->getTpa($this->tpa)->getParam('url'), $this->version, "create_modify", $this->_userdata);
						$this->debug('Executed function sso with params', array($this->user, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"], $this->config->getTpa($this->tpa)->getParam('url'), $this->version, 'create_modify', $this->_userdata));
					}
					if (!$ssoValues) {
						$ssoValues = sso($this->user, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"], $this->config->getTpa($this->tpa)->getParam('url'), $this->version, "logon", $this->_userdata);
						$this->debug('Executed function sso with params', array($this->user, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"], $this->config->getTpa($this->tpa)->getParam('url'), $this->version, 'logon', $this->_userdata));
					}
				break;			
			}
			return $ssoValues;
		}
		else {
			$this->debug('Executed function sso with params', array($this->user, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"], $this->config->getTpa($this->tpa)->getParam('url')));		
			return sso($this->user, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"], $this->config->getTpa($this->tpa)->getParam('url'));
		}
		return false;
	}
	
	/**
	 * Checks request-vars
	 *
	 * Internal method to check the validity of expire, tpaid, signature and token.
	 * If expired, unknown id, invalid signature or already-used token an error is thrown and {@link execute()} returns false.
	 * @return boolean false in case of error
	 */	
	protected function checkVars() {
		if ($this->expires < time()) {
			return $this->throwError("expires_exeeded");
		}	
		
		if ($this->config->getTpa($this->tpa) == null) {
			return $this->throwError("tpaid_unknown");
		}		
		if ($this->checkSignature()) {
			$this->checkToken();
		}
		return true;
	}
	
	/**
	 * Checks if request is already used
	 *
	 * Internal method to check if the link was already used.
	 * @return boolean false in case of error
	 */		
	protected function checkToken() {
		$content = array();
		$ok = true;
		if (!touch($this->config->getSetting('tokensfile'))){            // can't read tokensfile
			return $this->throwError("usedtokens_missingfile");
		}
		$lines = file($this->config->getSetting('tokensfile'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$file = @fopen($this->config->getSetting('tokensfile'), "w");
		if (!$file){            // can't read tokensfile
			return $this->throwError("usedtokens_missingfile");
		}
		foreach($lines as $line) {
			$tmp = explode(":", $line);
			if (($tmp[0] == $this->expires) && ($tmp[1] == $this->user) && ($tmp[2] == $this->tpa)) {
				$ok = false;
			}
			if ($tmp[0] > time()){
				$content[] = $line;
			}
			
		}
		if ($ok) {
			$content[] = $this->expires.':'.$this->user.':'.$this->tpa;
		}
		if (fwrite($file, implode("\n", $content)) === false) {
			fclose ($file);
			return $this->throwError("usedtokens_missingfile");
		}
		fclose ($file);		
		if (!$ok) {
			return $this->throwError("usedtokens_allreadyused");
		}
		return true;
	}
	
	/**
	 * Checks if signature is valid
	 *
	 * Internal method to check with openSSL if the signature id valid.
	 * @return boolean false in case of error
	 */	
	protected function checkSignature() {
		$version = explode(".", $this->version);
		switch ($version[0]) {
			case 2:
				$data = 'version='.$this->version.'&user='.$this->user.'&tpa_id='.$this->tpa.'&expires='.$this->expires.'&action='.$this->action.'&flags='.self::getVar('flags').'&userdata='.self::getVar('userdata');
				break;
			case 3:
				$data = 'tx_nawsinglesignon_pi1[version]='.$this->version.'&tx_nawsinglesignon_pi1[user]='.$this->user.'&tx_nawsinglesignon_pi1[tpa_id]='.$this->tpa.'&tx_nawsinglesignon_pi1[expires]='.$this->expires.'&tx_nawsinglesignon_pi1[action]='.$this->action.'&tx_nawsinglesignon_pi1[flags]='.self::getVar('flags').'&tx_nawsinglesignon_pi1[userdata]='.self::getVar('userdata');
				break;
			default:
				$data = 'user='.$this->user.'&tpa_id='.$this->tpa.'&expires='.$this->expires;
		}
		$ok = 0;
		if ((boolean)$this->config->getSetting('externalOpenssl')) {
			$this->debug("openSSL", "EXTERNAL");
			$tmp_signature_file = $this->config->getSetting('tmp_signature_dir')."/".uniqid($this->config->getSetting('tmp_signature_prefix'));
			$this->debug("tmp_signature_file", $tmp_signature_file);
			$tmp_file = @fopen ($tmp_signature_file, "w");
			if (!$tmp_file) {
				return $this->throwError("tmp_signature_dir_missingfile");
			}
			fwrite($tmp_file, $this->signature);
			fclose($tmp_file);
			$this->debug('Data to verify', $data);

			$verify = shell_exec("echo -n \"".$data."\"|openssl dgst -sha1 -verify \"".$this->config->getSetting('public_ssl_key')."\" -signature \"".$tmp_signature_file."\"");
			unlink($tmp_signature_file);
			$this->debug('Verification result string', $verify);

			if ($verify == "Verified OK\n"){
				$ok = 1;
			}
			else {
				return $this->throwError("signature_invalid");
			
			}
		}
		else {
			$this->debug("openSSL", "INTERNAL");
			$fp = @fopen($this->config->getSetting('public_ssl_key'), "r");
			if ($fp) {
				$cert = fread($fp, 8192);
				fclose($fp);
				$pubkeyid = openssl_get_publickey($cert);
				$this->debug('Data to verify', $data);
				$this->debug('Key', $cert);
				// compute signature
				$ok = @openssl_verify($data, $this->signature, $pubkeyid);
				// remove key from memory
				@openssl_free_key($pubkeyid);
			}
			else {
				return $this->throwError("sslkey_missingfile");
			}
		}
		if ($ok != 1) { // error in signature
			return $this->throwError("signature_invalid");
		}
		$this->debug("Signature", "OK");
		return true;
	}
	
	/**
	 * Decodes the signature
	 *
	 * Internal method to decode the ssl-signature
	 * @param string $data hexdata
	 * @return mixed the decoded value
	 */	
	protected function hex2bin($data) {
		$len = strlen($data);
		$newdata='';
		for($i=0;$i<$len;$i+=2) {
			$newdata .=  pack("C",hexdec(substr($data,$i,2)));
		}
		return $newdata;
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
	
	/**
	 * Decodes additional informations from the sso-server
	 *
	 * Internal use only.
	 * If userdata and flags transmitted it whould be decoded here.
	 * @return boolean
	 */			
	protected function decodeData() {
		if (self::getVar('userdata')) {
			$this->_userdata = base64_decode(self::getVar('userdata'));
			$tmpdata = explode("|", $this->_userdata);
			foreach ($tmpdata as $data){
				$tmpdata = explode("=", $data, 2);
				$this->userdata[$tmpdata[0]] = $tmpdata[1];
			}		
			$this->debug('submitted userdata', $this->userdata);
		}
		if (self::getVar('flags')) {
			$this->_flags = base64_decode(self::getVar('flags'));
			$tmpflags = explode("|", $this->_flags);
			foreach ($tmpflags as $flag){
				$tmpflag = explode("=", $flag, 2);
				$this->flags[$tmpflag[0]] = $tmpflag[1];
			}
			$this->debug('submitted flags', $this->flags);
		}
		return true;
	}
	
	static public function getVar($var) {
		$vars = $_REQUEST;
		if (isset($_REQUEST['tx_nawsinglesignon_pi1'])) {	
			$vars = $_REQUEST['tx_nawsinglesignon_pi1'];
		}
		if (isset($vars[$var])) {
			return $vars[$var];
		}
		return "";
	}
	
	/**
	 * Read request-vars
	 *
	 * Returns true if all needed variables are set.
	 * @return boolean false in case of error
	 */			
	protected function initVars() {
		if (!self::getVar('user')) {
	        return $this->throwError("user_missing");
		}
		$this->user = self::getVar('user');
		
		if (!self::getVar('tpa_id')) {
			return $this->throwError("tpaid_missing");
		}
		$this->tpa = self::getVar('tpa_id');
		
		if (!self::getVar('expires')) {
			return $this->throwError("expires_missing");
		}
		$this->expires = (integer)self::getVar('expires');
		$this->version = self::getVar('version');
		$this->action = self::getVar('action');
		$sign = self::getVar('signature');
		$this->signature = $this->hex2bin($sign);
		return true;
	}
	
	/**
	 * Checks the configuration
	 *
	 * Returns true if all needed configuration are available
	 * @return boolean false in case of error
	 */			
	protected function checkConfig() {
		$public_ssl_key = $this->config->getSetting('public_ssl_key');
		$this->debug("configcheck public_ssl_key", !empty($public_ssl_key));
		if (empty($public_ssl_key)) {
			return $this->throwError("sslkey_missingconf");
		}
		$this->debug("try to open '".$this->config->getSetting('public_ssl_key')."'", file_exists($this->config->getSetting('public_ssl_key')));
		if (!file_exists($this->config->getSetting('public_ssl_key'))) {
			return $this->throwError("sslkey_missingfile");
		}
		$tokensfile = $this->config->getSetting('tokensfile');
		$this->debug("configcheck tokensfile", !empty($tokensfile));
		if (empty($tokensfile)) {
			return $this->throwError("usedtokens_missingconf");
		}
		$loglevel = $this->config->getSetting('loglevel');
		if ((int)$this->config->getSetting('loglevel') == 0) {
			$this->debug("Logging is disabled", true);
		}
		else {
			$logfile = $this->config->getSetting('logfile');
			$this->debug("configcheck logfile", !empty($logfile));
			if (empty($logfile)) {
				return $this->throwError("logfile_missingconf");
			}
		}
		if ((boolean)$this->config->getSetting('externalOpenssl') && (!(boolean)$this->config->getSetting('tmp_signature_dir'))) {	
			return $this->throwError("tmp_signature_dir_missingconf");
		}
		if (self::DEBUG) {
			$this->config->getErrorhandler()->debugErrorMessages();
		}
		return true;
	}
	
	/**
	 * Throw an error
	 *
	 * This method logs and throws the specified error.
	 * Must always return false to stop next actions (performance).
	 * @param string $key error message Id
	 * @param string $additional additional message
	 * @return boolean false
	 */		
	public function throwError($key, $additional = "") {	
		if ($this->config->getSetting('loglevel') > 0){
			$errorMessages = $this->getConfig()->getErrorhandler()->getErrorMessages();
			$this->logIt(array("IP" => $_SERVER["REMOTE_ADDR"], "USER" => $this->user, "TPA_ID" => $this->tpa, "ERROR" => $errorMessages[$key]." (".$additional.")"));
		}
		$this->config->getErrorhandler()->throwError($key, $additional);
		return false;
	}

	/**
	 * Getter for actual config
	 * 
	 * Returns the configobject for doing some changes for example add adapter, read or set (custom) errormessages.
	 * @return DhbwSSOAgentConfig the configobject
	 */		
	public function getConfig() {
		return $this->config;
	}	
	
	/**
	 * Getter for agents version
	 *
	 * Information about this agents version.
	 * @return string the agentversion
	 */		
	public function getAgentVersion() {
		return self::VERSION;
	}	
	
	/**
	 * Getter for action
	 *
	 * Returns the requested action or false if request is not (yet) validated
	 * @return mixed string the $action or false
	 */		
	public function getAction() {
		if ($this->execute()) {
			return $this->version;
		}
		return false;
	}
	
	/**
	 * Getter for requestservers version
	 *
	 * Information about the requesting servers version.
	 * Returns false if request is not (yet) validated
	 * @return mixed string the serverversion or false
	 */		
	public function getServerVersion() {
		if ($this->execute()) {
			return $this->version;
		}
		return false;
	}	
	
	/**
	 * Getter for adapters version
	 *
	 * Information about the used adapter version.
	 * Returns false if request is not (yet) validated
	 * @return mixed string the adapterversion or false
	 */		
	public function getAdapterVersion() {
		if ($this->execute()) {
			return implode(".", $this->adapterVersion);
		}
		return false;
	}
	
	/**
	 * Getter for adapters version
	 *
	 * Information about the used adapter version.
	 * Returns false if request is not (yet) validated
	 * @return mixed string the adapterversion or false
	 */		
	public function getAdapter() {
		if ($this->execute()) {
			return $this->getConfig()->getTpa($this->tpa);
		}
		return false;
	}	
}
?>
