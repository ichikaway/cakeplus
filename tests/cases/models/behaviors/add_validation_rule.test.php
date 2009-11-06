<?php

App::import('Component', 'Security');
App::import('Component', 'Auth');


/**
 * Base model that to load AddValidationRule behavior on every test model.
 *
 * @package app.tests
 * @subpackage app.tests.cases.behaviors
 */
class AddValidationRuleTestModel extends CakeTestModel
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
	var $useDbConfig = "test";


	var $validate = array(
		'valuediff' => array(
			"rule1" => array('rule' => array('compare2fields', 'valuediff_conf'),
				'message' => '【メールアドレス】 と【メールアドレス(確認)】の内容が異なります'
			),
		),
		'password' => array(
			"rule1" => array('rule' => array('compare2fields', 'password_conf',true),
				'message' => 'パスワード と パスワード(確認)の内容が異なります'
			),
		),

		'spaceonly' => array(
			"rule5" => array('rule' => array('space_only'),
				'message' => 'スペース以外も入力してください'
			),
		),
		'alphanumber' => array(
			"rule7" => array('rule' => array('alpha_number'),
				'message' => '英数字のみで入力してください'
			),
		),
		'maxlengthjp' => array(
			"rule2" => array('rule' => array('maxLengthJP', 10),
				'message' => '10文字以内です'
			),

		),
		'minlengthjp' => array(
			"rule3" => array('rule' => array('minLengthJP', 2),
				'message' => '2文字以上です'
			),

		),
		'katakanaonly' => array(
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
		echo '<h2>Starting Test Case</h2>';
		$this->ValidationRule =& ClassRegistry::init('ValidationRule');
	}

	function endCase() {
		echo '<h2>Ending Test Case</h2>';
	}

	function startTest($method) {
		echo '<h3>Starting method '.$method.'</h3>';
	}

	function endTest($method) {
		echo '<hr/>';
	}


	//全てバリデーションに引っかかるテスト
	function testValidataionAllFail(){

		$data = array(
			'ValidationRule' => array(
				'valuediff'	=>	'a',
				'valuediff_conf'	=>	's',
				'spaceonly'	=>	' 　',
				'alphanumber'	=>	'あ',
				'maxlengthjp'	=>	'あああああああああああ',
				'minlengthjp'	=>	'あ',
				'katakanaonly'	=>	'あ',

			),
		);

		$this->assertTrue( $this->ValidationRule->create( $data ) );

		$this->assertFalse( $this->ValidationRule->validates() );

		$this->assertTrue( array_key_exists("valuediff" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("spaceonly" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("alphanumber" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("maxlengthjp" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("minlengthjp" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("katakanaonly" , $this->ValidationRule->validationErrors ) );

	}

	//全てバリデーションで成功するテスト
	function testValidataionAllSuccess(){

		$data = array(
			'ValidationRule' => array(
				'valuediff'	=>	'あいうえお',
				'valuediff_conf'	=>	'あいうえお',
				'spaceonly'	=>	' 　ええ',
				'alphanumber'	=>	'onlyAlpharNumeric123456789',
				'maxlengthjp'	=>	'10ああああああああ',
				'minlengthjp'	=>	'あa',
				'katakanaonly'	=>	'カタカナノミァィゥェォー゛゜',

			),
		);

		$this->assertTrue( $this->ValidationRule->create( $data ) );
		$this->assertTrue( $this->ValidationRule->validates() );


		$this->assertFalse( array_key_exists("valuediff" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("spaceonly" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("alphanumber" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("maxlengthjp" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("minlengthjp" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("katakanaonly" , $this->ValidationRule->validationErrors ) );
	}

	//spaceonly, alphanum, katakanaonlyフィールドのみバリデーションに引っかかるテスト
	function testValidataion_spaceonly_alphanum_katakanaonly_Fail(){

		$data = array(
			'ValidationRule' => array(
				'valuediff'	=>	'abcdefg 12345',
				'valuediff_conf'	=>	'abcdefg 12345',
				'spaceonly'	=>	'　',
				'alphanumber'	=>	'only AlpharNumeric 123456789',
				'maxlengthjp'	=>	'1234567abc',
				'minlengthjp'	=>	'ab',
				'katakanaonly'	=>	'ﾊﾝｶｸｶﾅ',

			),
		);


		$this->assertTrue( $this->ValidationRule->create( $data ) );
		$this->assertFalse( $this->ValidationRule->validates() );


		$this->assertFalse( array_key_exists("valuediff" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("spaceonly" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("alphanumber" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("maxlengthjp" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("minlengthjp" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("katakanaonly" , $this->ValidationRule->validationErrors ) );
	}


	//Authコンポーネント系テスト
	function testAuthHash(){
		//passwordフィールドがハッシュ化されなかった場合はエラー
		$data = array(
			'ValidationRule' => array(
				'password'	=>	'abc123',
				'password_conf'	=>	'abc123',
			),
		);
		$this->assertTrue( $this->ValidationRule->create( $data ) );
		$this->assertFalse( $this->ValidationRule->validates() );
		$this->assertTrue( array_key_exists("password" , $this->ValidationRule->validationErrors ) );


		//AuthComponent::passwordを使ってハッシュ化　同一値でバリデーションエラーがないことを確認
		$data = array(
			'ValidationRule' => array(
				'password'	=>	AuthComponent::password('abc123cvb'),
				'password_conf'	=>	'abc123cvb',
			),
		);
		$this->assertTrue( $this->ValidationRule->create( $data ) );
		$this->assertTrue( $this->ValidationRule->validates() );
		$this->assertFalse( array_key_exists("password" , $this->ValidationRule->validationErrors ) );


		//AuthComponent::passwordを使ってハッシュ化　異なる値でバリデーションエラーに引っかかるテスト
		$data = array(
			'ValidationRule' => array(
				'password'	=>	AuthComponent::password('abc123cvb'),
				'password_conf'	=>	'hoge111',
			),
		);
		$this->assertTrue( $this->ValidationRule->create( $data ) );
		$this->assertFalse( $this->ValidationRule->validates() );
		$this->assertTrue( array_key_exists("password" , $this->ValidationRule->validationErrors ) );
	}




}

?>