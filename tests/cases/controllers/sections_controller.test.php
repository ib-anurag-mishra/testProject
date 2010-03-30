<?php
/* Sections Test cases generated on: 2010-03-25 12:03:58 : 1269535438*/
App::import('Controller', 'Sections');

class TestSectionsController extends SectionsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class SectionsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.section', 'app.question', 'app.genre', 'app.metadata', 'app.physicalproduct', 'app.availability', 'app.product', 'app.p_r_o_d_u_c_t__o_f_f_e_r', 'app.s_a_l_e_s__t_e_r_r_i_t_o_r_y', 'app.graphic', 'app.files', 'app.audio', 'app.featuredartist', 'app.newartist', 'app.category');

	function startTest() {
		$this->Sections =& new TestSectionsController();
		$this->Sections->constructClasses();
	}

	function endTest() {
		unset($this->Sections);
		ClassRegistry::flush();
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