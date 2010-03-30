<?php
	$output = array();
	if($this->validationErrors) {
		$errorArr = explode("|", $errors['message']);
		$output = Set::insert($output, 'errors', array('message' => $errorArr[0]));
		$output['errors']['stepNum'] = $errorArr[1];
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
