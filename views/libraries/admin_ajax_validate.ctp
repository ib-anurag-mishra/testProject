<?php
	$output = array();
	if($this->validationErrors) {
		$output = Set::insert($output, 'errors', array('message' => $errors['message']));
		foreach ($errors['data'] as $model => $errs) {
			foreach ($errs as $field => $message) {
				$output['errors']['data'][$model][$field] = $message;
			}
		}
	} elseif ($success) {
		$output = Set::insert($output, 'success', array(
			'message' => $success['message'],
			'data' => $success['data']
		));
	}
	echo $javascript->object($output);
?>
