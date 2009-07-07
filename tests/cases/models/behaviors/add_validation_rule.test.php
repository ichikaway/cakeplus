<?php

//App::import('Behavior', 'add_validation_rule');
App::import('Core', array('AppModel', 'Model'));

/**
 * Base model that to load AddValidationRule behavior on every test model.
 *
 * @package app.tests
 * @subpackage app.tests.cases.behaviors
 */
//class AddValidationRuleTestModel extends CakeTestModel
class AddValidationRuleTestModel extends AppModel
{
	/**
	 * Behaviors for this model
	 *
	 * @var array
	 * @access public
	 */
	var $actsAs = array('cakeplus.AddValidationRule');


}


/**
 * Model used in test case.
 *
 * @package	app.tests
 * @subpackage app.tests.cases.behaviors
 */
class ValidationRule extends AddValidationRuleTestModel
{
	/**
	 * Name for this model
	 *
	 * @var string
	 * @access public
	 */
	var $name = 'ValidationRule';
	var $useTable = false;



  	var $validate = array(
 		'hoge' => array(
 			"rule1" => array('rule' => array('checkCompare', '_conf'),
 				'message' => '【メールアドレス】 と【メールアドレス(確認)】の内容が異なります'
 			),
 		),

 		'hoge2' => array(
 			"rule5" => array('rule' => array('space_only'),
 				'message' => 'スペース以外も入力してください'
 			),
 		),
  		'hoge3' => array(
  			"rule7" => array('rule' => array('alpha_number'),
 				'message' => '英数字のみで入力してください'
 			),
 		),
 		'hoge4' => array(
			"rule2" => array('rule' => array('maxLengthJP', 10),
 				'message' => '10文字以内です'
 			),

 		),
 		'hoge5' => array(
			"rule3" => array('rule' => array('minLengthJP', 2),
 				'message' => '2文字以上です'
 			),

 		),
 		'hoge6' => array(
  			"rule6" => array('rule' => array('katakana_only'),
 				'message' => 'カタカナのみ入力してください'
 			),
 		),

 	);
}





class AddValidationRuleTestCase extends CakeTestCase
{
	/**
	 * @var ValidationRule
	 */
	var $ValidationRule = null;

	var $fixtures = null;


    function startCase() {
        echo '<h1>Starting Test Case</h1>';
    }

    function endCase() {
        echo '<h1>Ending Test Case</h1>';
    }

    function startTest($method) {
        echo '<h3>Starting method '.$method.'</h3>';

        $this->ValidationRule =& ClassRegistry::init('ValidationRule');

    }

    function endTest($method) {
        echo '<hr/>';
    }



	function testValidataionAllFail(){

		$data = array(
			'ValidationRule' => array(
				'hoge'	=>	'a',
				'hoge_conf'	=>	's',
				'hoge2'	=>	' 　',
				'hoge3'	=>	'あ',
				'hoge4'	=>	'あああああああああああ',
				'hoge5'	=>	'あ',
				'hoge6'	=>	'あ',

			),
		);

		$this->assertTrue( $this->ValidationRule->create( $data ) );

		$this->assertFalse( $this->ValidationRule->validates() );
		//pr($this->ValidationRule->validationErrors);
		//pr($this->ValidationRule);

		$this->assertTrue( array_key_exists("hoge" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("hoge2" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("hoge3" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("hoge4" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("hoge5" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("hoge6" , $this->ValidationRule->validationErrors ) );
		//pr($this->ValidationRule);
	}


	function testValidataionAllSuccess(){

		$data = array(
			'ValidationRule' => array(
				'hoge'	=>	'あいうえお',
				'hoge_conf'	=>	'あいうえお',
				'hoge2'	=>	' 　ええ',
				'hoge3'	=>	'onlyAlpharNumeric123456789',
				'hoge4'	=>	'10ああああああああ',
				'hoge5'	=>	'ああ',
				'hoge6'	=>	'カタカナノミ',

			),
		);

		$this->assertTrue( $this->ValidationRule->create( $data ) );
		$this->assertTrue( $this->ValidationRule->validates() );


		$this->assertFalse( array_key_exists("hoge" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("hoge2" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("hoge3" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("hoge4" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("hoge5" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("hoge6" , $this->ValidationRule->validationErrors ) );
	}

}

?>