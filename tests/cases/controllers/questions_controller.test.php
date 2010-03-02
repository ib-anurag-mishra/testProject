<?php
/* Questions Test cases generated on: 2010-03-02 08:03:59 : 1267535339*/
App::import('Controller', 'Questions');

class TestQuestionsController extends QuestionsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class QuestionsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.question', 'app.genre', 'app.metadata', 'app.product', 'app.availability', 'app.p_r_o_d_u_c_t__o_f_f_e_r', 'app.s_a_l_e_s__t_e_r_r_i_t_o_r_y', 'app.physicalproduct', 'app.featuredartist', 'app.newartist');

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