<?php
require_once 'Zend/Log/Writer/Abstract.php';

/**
 * Sends an error message to the FogBugz instance log whenever an
 * error occurs.
 * 
 * @see SS_Log for more information on using writers
 * @uses Zend_Log_Writer_Abstract
 * @package framework
 * @subpackage dev
 */
class BugzScoutLogWriter extends Zend_Log_Writer_Abstract {

	protected $description;
	protected $extra;
	protected $host;
	protected $scoutUserName;
	protected $scoutProject;
	protected $scoutArea;

	public function __construct($config ) {
		$this->host          = $config['host'];		
		$this->scoutUserName = $config['scoutUserName'];		
		$this->scoutProject  = $config['scoutProject'];		
		$this->scoutArea     = $config['scoutArea'];		
	}

	public function host() {
		return $this->host;
	}

	
	static public function factory($config) {
		return new BugzScoutLogWriter($config );
	}

	public function _write($event) {

		if(!$this->_formatter) {
			$formatter = new LogErrorBugzScoutFormatter();
			$this->setFormatter($formatter);
		}

		$formattedData     = $this->_formatter->format($event);
		$this->description = $formattedData['subject'];
		$this->extra       = $formattedData['data'];

		$cache_expiry = 1;
		$request = new RestfulService($this->host . "/scoutSubmit.asp", $cache_expiry);

		$params = array(
			'ScoutUserName' => $this->scoutUserName,
			'ScoutProject'  => $this->scoutProject,
			'ScoutArea'     => $this->scoutArea,
			'Description'   => $this->description,
			'Extra'         => $this->extra,
		);

		$conn = $request->request(null, 'POST', $params, null,array(
			CURLOPT_CONNECTTIMEOUT => 10,
		));
	}

}
