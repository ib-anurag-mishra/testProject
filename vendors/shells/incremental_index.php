<?php

App::import('Core', 'HttpSocket');

class IncrementalIndexShell extends Shell {

	var $core1 =  'freegalmusic';
	var $core2 =  'freegalmusicvideos';

	var $songsIndexUrl = "http://192.168.100.24:8080/solr/freegalmusic/dataimport";

	var $videosIndexUrl = "http://192.168.100.24:8080/solr/freegalmusicvideos/dataimport";

	var $query = "command=delta-import&clean=false";

	var $statusQuery = "command=status";

	var $sleepTime = 300;

	var $emailList = 'kiran.pyati@infobeans.com';

	function main() {

		//log
		$logId = strtotime(date('Y-m-d h:i:s'));
		$logData = PHP_EOL."----------Request (".$logId.") Start----------------".PHP_EOL;
		$logData .= date('Y-m-d h:i:s').' > Start Time: '.date('Y-m-d h:i:s').PHP_EOL;

		$this->processing( $this->songsIndexUrl,  $this->core1, $logId, $logData );
		$this->processing( $this->videosIndexUrl, $this->core2, $logId, $logData );
	}

	public function processing( $url, $coreName, $logId, $logData  ) {

		$httpSocket = new HttpSocket();

		$response = $httpSocket->get( $url, $this->query);

		// object type-casted
		$checkValidXml = null;
		$checkValidXml = simplexml_load_string( $response );

		if( $checkValidXml ) {

			$response = $httpSocket->get( $url, $this->statusQuery );

			// object type-casted
			$arrData = (array) simplexml_load_string( $response );

			if( 'busy' == strtolower( trim( $arrData['str'][1] ) ) ) {

				$logData .= date('Y-m-d h:i:s').' > Incremental Indexing Started ( Response: '.$response.' )'.PHP_EOL;
				$logData .= "\n\n";

				$msg = 'Incremental Indexing Started ('.$response.')';

				mail( $this->emailList, 'Apache Solr Indexer ' . $coreName .' ('.date('Y-m-d h:i').'-'.$logId.') Status', 'Status :- "'.$msg.'"' );

				$status = 1;
					
				while( $status ) {
					sleep ( $this->sleepTime );
					$response = $httpSocket->get( $url, $this->statusQuery );
					$status   = $this->getOperationStatus( $response, $logId, $coreName );
				}

			} else {

				$logData .= date('Y-m-d h:i:s').' > Indexing failed to start ( Response: '.$response.' )'.PHP_EOL;
				$logData .= "\n\n";
				$logData .= date('Y-m-d h:i:s').' > End Time: '.date('Y-m-d h:i:s').PHP_EOL;
				$logData .= PHP_EOL."---------Request (".$logId.") End----------------";
					
				$msg = 'Indexing Failed To Start: Internal Error ('.$response.')';
				mail( $this->emailList, 'Apache Solr Indexer ' . $coreName . ' ('.date('Y-m-d h:i').'-'.$logId.') Status', 'Status :- "'.$msg.'"' );
				writeToLog($logData);
			}

		} else {

			$logData .= date('Y-m-d h:i:s').' > Indexing failed to start ( Response: '.$response.' )'.PHP_EOL;
			$logData .= "\n\n";
			$logData .= date('Y-m-d h:i:s').' > Indexing failed to start ( Response: '.$response.' )'.PHP_EOL;
			$logData .= date('Y-m-d h:i:s').' > End Time: '.date('Y-m-d h:i:s').PHP_EOL;
			$logData .= PHP_EOL."---------Request (".$logId.") End----------------";

			$msg = 'Indexing Failed To Start: Valid response XML not sent ('.$response.')';
			mail($this->emailList, 'Apache Solr Indexer ' . $coreName . ' ('.date('Y-m-d h:i').'-'.$logId.') Status', 'Status :- "'.$msg.'"');
			writeToLog($logData);
		}

	}

	public function getOperationStatus($response, $logId, $coreName) {

		$arrData = (array) simplexml_load_string($response);
			
		if('busy' == strtolower($arrData['str'][1])) {
			return 1;
		} else {
			
			$msg = 'Response of '. $coreName . ' Core:' . PHP_EOL . $response ;
			mail($this->emailList, 'Apache Solr Indexer ' . $coreName . ' ('.date('Y-m-d h:i').'-'.$logId.') Status', 'Status :- "'.$msg.'"');

			return 0;
		}
	}
}