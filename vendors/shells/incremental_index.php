<?php

App::import('Core', 'HttpSocket');

class IncrementalIndexShell extends Shell {

	var $core1 = 'freegalmusicstage';
	var $core2 = 'freegalmusicvideos';
	
	var $songsIndexUrl = "http://192.168.100.24:8080/solr/freegalmusicstage/dataimport";

	var $videosIndexUrl = "http://192.168.100.24:8080/solr/freegalmusicvideos/dataimport";

	var $query = "command=delta-import&clean=false";

	var $statusQuery = "command=status";

	var $sleepTime = 5;

	var $emailList = 'ghanshyam.agrawal@infobeans.com';

	function main() {

		$httpSocket = new HttpSocket();

		// start songs data indexing

		//log
		$logId = strtotime(date('Y-m-d h:i:s'));
		$logData = PHP_EOL."----------Request (".$logId.") Start----------------".PHP_EOL;
		$logData .= date('Y-m-d h:i:s').' > Start Time: '.date('Y-m-d h:i:s').PHP_EOL;

		$response = $httpSocket->get($this->songsIndexUrl,$this->query);

		// object type-casted
		$checkValidXml = null;
		$checkValidXml = simplexml_load_string($response);

		// executes IF for valid xml response
		if($checkValidXml) {
			$response = $httpSocket->get($this->songsIndexUrl,$this->statusQuery);

			// object type-casted
			$objXmlResponse = simplexml_load_string($response);

			// type-casted to array format
			$arrData = (array)$objXmlResponse;
			if('busy' == strtolower($arrData['str'][1])) {
				// valid

				$logData .= date('Y-m-d h:i:s').' > Incremental Indexing Started ( Response: '.$response.' )'.PHP_EOL;
				$logData .= "\n\n";

				$msg = 'Incremental Indexing Started ('.$response.')';

				mail($this->emailList, 'Apache Solr Indexer ' . $this->core1 .' ('.date('Y-m-d h:i').'-'.$logId.') Status', 'Status :- "'.$msg.'"');

				// sleep perodic
				sleep ( $this->sleepTime );

				$status = 1;

				while($status){
					sleep ( $this->sleepTime );
					$response = $httpSocket->get($this->songsIndexUrl,$this->statusQuery);
					$status   = $this->getOperationStatus($response, $logId);
				}

			} else {
				$logData .= date('Y-m-d h:i:s').' > Indexing failed to start ( Response: '.$response.' )'.PHP_EOL;
				$logData .= "\n\n";
				$logData .= date('Y-m-d h:i:s').' > End Time: '.date('Y-m-d h:i:s').PHP_EOL;
				$logData .= PHP_EOL."---------Request (".$logId.") End----------------";

				$msg = 'Indexing Failed To Start: Internal Error ('.$response.')';
				mail($this->emailList, 'Apache Solr Indexer ' . $this->core1 . ' ('.date('Y-m-d h:i').'-'.$logId.') Status', 'Status :- "'.$msg.'"');
			}
		} else {

			$logData .= date('Y-m-d h:i:s').' > Indexing failed to start ( Response: '.$response.' )'.PHP_EOL;
			$logData .= "\n\n";
			$logData .= date('Y-m-d h:i:s').' > Indexing failed to start ( Response: '.$response.' )'.PHP_EOL;
			$logData .= date('Y-m-d h:i:s').' > End Time: '.date('Y-m-d h:i:s').PHP_EOL;
			$logData .= PHP_EOL."---------Request (".$logId.") End----------------";
			$msg = 'Indexing Failed To Start: Valid response XML not sent ('.$response.')';
			mail($emailList, 'Apache Solr Indexer ' . $this->core1 . ' ('.date('Y-m-d h:i').'-'.$logId.') Status', 'Status :- "'.$msg.'"');
			writeToLog($logData);
		}//for invalid xml response

		$response = $httpSocket->get($this->videosIndexUrl, $this->query);

		// object type-casted
		$checkValidXml = null;
		$checkValidXml = simplexml_load_string($response);

		// executes IF for valid xml response
		if($checkValidXml) {

			$response = $httpSocket->get($this->videosIndexUrl, $this->statusQuery);

			// object type-casted
			$objXmlResponse = simplexml_load_string($response);
				
			// type-casted to array format
			$arrData = (array)$objXmlResponse;
			
			if('busy' == strtolower($arrData['str'][1])) {
				// valid
			
				$logData .= date('Y-m-d h:i:s').' > Incremental Indexing Started ( Response: '.$response.' )'.PHP_EOL;
				$logData .= "\n\n";
			
				$msg = 'Incremental Indexing Started ('.$response.')';
			
				mail($this->emailList, 'Apache Solr Indexer ' . $this->core2 .' ('.date('Y-m-d h:i').'-'.$logId.') Status', 'Status :- "'.$msg.'"');
			
				// sleep perodic
				sleep ( $this->sleepTime );
			
				$status = 1;
			
				while($status){
					sleep ( $this->sleepTime );
					$response = $httpSocket->get($this->videosIndexUrl,$this->statusQuery);
					$status   = $this->parseStatusResponse($response, $logId);
				}
			
			} else {
				$logData .= date('Y-m-d h:i:s').' > Indexing failed to start ( Response: '.$response.' )'.PHP_EOL;
				$logData .= "\n\n";
				$logData .= date('Y-m-d h:i:s').' > End Time: '.date('Y-m-d h:i:s').PHP_EOL;
				$logData .= PHP_EOL."---------Request (".$logId.") End----------------";
			
				$msg = 'Indexing Failed To Start: Internal Error ('.$response.')';
				mail($this->emailList, 'Apache Solr Indexer ' . $this->core2 . ' ('.date('Y-m-d h:i').'-'.$logId.') Status', 'Status :- "'.$msg.'"');
			}
		} else {
			
			$logData .= date('Y-m-d h:i:s').' > Indexing failed to start ( Response: '.$response.' )'.PHP_EOL;
			$logData .= "\n\n";
			$logData .= date('Y-m-d h:i:s').' > Indexing failed to start ( Response: '.$response.' )'.PHP_EOL;
			$logData .= date('Y-m-d h:i:s').' > End Time: '.date('Y-m-d h:i:s').PHP_EOL;
			$logData .= PHP_EOL."---------Request (".$logId.") End----------------";
			$msg = 'Indexing Failed To Start: Valid response XML not sent ('.$response.')';
			mail($emailList, 'Apache Solr Indexer ' . $this->core2 . ' ('.date('Y-m-d h:i').'-'.$logId.') Status', 'Status :- "'.$msg.'"');
			writeToLog($logData);
		}

		function parseStatusResponse($response, $logId) {
			$objXmlResponse = simplexml_load_string($response);

			// type-casted to array format
			$arrData = (array)$objXmlResponse;

			if('busy' == strtolower($arrData['str'][1])) {
				return 1;
			} else {
				//Total Documents Processed
				$total_documents_processed = null;
				if(isset($arrData['lst'][2]->str[8])) {
					$test = (array)$arrData['lst'][2]->str[8];
					$total_documents_processed = $test[0];
				}

				//Total Time taken to process documents
				$total_time = null;
				if(isset($arrData['lst'][2]->str[9])) {
					$test = (array)$arrData['lst'][2]->str[9];
					$total_time = $test[0];
				}

				if( (!empty($total_documents_processed)) && (!empty($total_time)) ) {
					$msg = 'Indexer processed '.$total_documents_processed. ' documents in '.$total_time.' hours.';
					mail($emailList, 'Apache Solr Indexer ' . $this->core2 . ' ('.date('Y-m-d h:i').'-'.$logId.') Status', 'Status :- "'.$msg.'"');
				} else {
					$msg = 'Indexer failed to complete ('.$response.')';
					mail($emailList, 'Apache Solr Indexer ' . $this->core2 . ' ('.date('Y-m-d h:i').'-'.$logId.') Status', 'Status :- "'.$msg.'"');
				}

				return 0;
			}
		}
		
		function getOperationStatus($response, $logId) {

			$objXmlResponse = simplexml_load_string($response);
			
			// type-casted to array format
			$arrData = (array)$objXmlResponse;
			
			if('busy' == strtolower($arrData['str'][1])) {
				return 1;
			} else {
				//Total Documents Processed
				$total_documents_processed = null;
				if(isset($arrData['lst'][2]->str[10])) {
					$test = (array)$arrData['lst'][2]->str[10];
					$total_documents_processed = $test[0];
				}

				//Total Time taken to process documents
				$total_time = null;
				if(isset($arrData['lst'][2]->str[11])) {
					$test = (array)$arrData['lst'][2]->str[11];
					$total_time = $test[0];
				}
			
				if( (!empty($total_documents_processed)) && (!empty($total_time)) ) {
					$msg = 'Indexer processed '.$total_documents_processed. ' documents in '.$total_time.' hours.';
					mail($emailList, 'Apache Solr Indexer ' . $this->core1 . ' ('.date('Y-m-d h:i').'-'.$logId.') Status', 'Status :- "'.$msg.'"');
				} else {
					$msg = 'Indexer failed to complete ('.$response.')';
					mail($emailList, 'Apache Solr Indexer ' . $this->core1 . ' ('.date('Y-m-d h:i').'-'.$logId.') Status', 'Status :- "'.$msg.'"');
				}
			
				return 0;
			}
		}
	}
}