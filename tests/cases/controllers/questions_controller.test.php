<?php
/* Questions Test cases generated on: 2010-03-25 12:03:39 : 1269536139*/
App::import('Controller', 'Questions');

class TestQuestionsController extends QuestionsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class QuestionsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.question', 'app.section', 'app.genre', 'app.metadata', 'app.physicalproduct', 'app.availability', 'app.product', 'app.p_r_o_d_u_c_t__o_f_f_e_r', 'app.s_a_l_e_s__t_e_r_r_i_t_o_r_y', 'app.graphic', 'app.files', 'app.audio', 'app.featuredartist', 'app.newartist', 'app.category');

	function startTest() {
		$this->Questions =& new TestQuestionsController();
		$this->Questions->constructClasses();
	}

	function endTest() {
		unset($this->Questions);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testView() {

	}

	function testAdd() {

	}

	function testEdit() {

	}

	function testDelete() {

	}

	function testAdminIndex() {

	}

	function testAdminView() {

	}

	function testAdminAdd() {

	}

	function testAdminEdit() {

	}

	function testAdminDelete() {

	}

}
?>