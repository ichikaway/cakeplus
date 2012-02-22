<?php

App::import('Controller', 'Controller');
App::import('Helper', 'Html');
App::import('Helper', 'Form');
App::import('Helper', 'Cakeplus.Formhidden');

class ContactTestController extends Controller {
/**
 * name property
 *
 * @var string 'ContactTest'
 * @access public
 */
	var $name = 'ContactTest';
/**
 * uses property
 *
 * @var mixed null
 * @access public
 */
	var $uses = null;

}


class FormhiddenHelperTest extends CakeTestCase {

	function setUp(){
		parent::setUp();
		$this->Controller = new ContactTestController();
		$this->View = new View($this->Controller);

		$this->Formhidden = new FormhiddenHelper($this->View);
		$this->Formhidden->Form = new FormHelper($this->View);
		$this->Formhidden->Form->Html = new HtmlHelper($this->View);
	}

	function tearDown() {
		ClassRegistry::removeObject('view');
		unset($this->Formhidden, $this->Controller, $this->View);
	}

	function startCase() {
	}

	function endCase() {
	}

	function startTest($method) {
	}

	function endTest($method) {
	}


	//test for using parameter
	function test_basic_hidden_param_data(){
		$data = array( 'Contact' => array(
				'id' => '1',
				'text' => 'aaaa',
				'body' => 'あいうえおテスト日本語1234abcd',
			)
		);

		$expected = array(
			array( 'input' => array('type' => 'hidden', 'name' => 'data[Contact][id]', 'value' => '1', 'id' => 'ContactId'), ),
			array( 'input' => array('type' => 'hidden', 'name' => 'data[Contact][text]', 'value' => 'aaaa', 'id' => 'ContactText'), ),
			array( 'input' => array('type' => 'hidden', 'name' => 'data[Contact][body]', 'value' => 'あいうえおテスト日本語1234abcd', 'id' => 'ContactBody'), ),
		);

		//check not using.
		$this->Formhidden->data = array( 'Hoge' => array( 'id' => '199', 'hoge' => 'eeeee' ) );

		// for using Form->hidden() method which uses $this->data to create hidden tag.
		$this->Formhidden->Form->data = $data;

		$result = $this->Formhidden->hiddenVars($data);

		$this->assertTags($result, $expected);
	}


	//test for using $this->data
	function test_basic_hidden_this_data(){
		$data = array( 'Contact' => array(
				'id' => '1',
				'text' => 'aaaa',
				'body' => 'あいうえおテスト日本語1234abcd',
			)
		);

		$expected = array(
			array( 'input' => array('type' => 'hidden', 'name' => 'data[Contact][id]', 'value' => '1', 'id' => 'ContactId'), ),
			array( 'input' => array('type' => 'hidden', 'name' => 'data[Contact][text]', 'value' => 'aaaa', 'id' => 'ContactText'), ),
			array( 'input' => array('type' => 'hidden', 'name' => 'data[Contact][body]', 'value' => 'あいうえおテスト日本語1234abcd', 'id' => 'ContactBody'), ),
		);

		$this->Formhidden->data = $data;
		$this->Formhidden->Form->data = $data;
		$result = $this->Formhidden->hiddenVars();
		$this->assertTags($result, $expected);
	}


	//test for no data
	function test_basic_hidden_null(){
		$data = array();

		$this->Formhidden->data = $data;
		$this->Formhidden->Form->data = $data;

		$result = $this->Formhidden->hiddenVars();
		$this->assertNull($result);

		$result = $this->Formhidden->hiddenVars($data);
		$this->assertNull($result);
	}
}