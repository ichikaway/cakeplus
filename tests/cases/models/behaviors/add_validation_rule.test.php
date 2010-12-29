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
			'betweenJP' => array(
					"rule7" => array('rule' => array('betweenJP', 5, 10),
						'message' => '5文字以上10文字以内です'
						),
					),
			'hiragana_only' => array(
					"rule8" => array('rule' => array('hiragana_only'),
						'message' => 'ひらがなのみ入力してください'
						),
					),
			'zenkaku_only' => array(
					"rule9" => array('rule' => array('zenkaku_only'),
						'message' => '全角のみ入力してください'
						),
					),
			'tel_fax_jp' => array(
					"rule10" => array('rule' => array('tel_fax_jp'),
						'message' => '正しい電話番号を入力してください'
						),
					),
			'mobile_email_jp' => array(
					"rule11" => array('rule' => array('mobile_email_jp'),
						'message' => '正しい携帯メールアドレスを入力して下さい'
						),
					),
			'password_valid' => array(
					"rule12" => array('rule' => array('password_valid','password_conf', 5, 10),
						'message' => '正しいパスワードを入力して下さい'
						),
					),
			'datetime_valid' => array(
					"rule13" => array('rule' => array('datetime', 'ymd', null),
						'message' => '正しい日付を入力して下さい'
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
					'betweenJP'	=>	'あいうえおかきくけこさしすせそ',
					'hiragana_only'	=>	'カタカナ',
					'zenkaku_only'	=>	'090abc',
					'tel_fax_jp'	=>	'abcde',
					'mobile_email_jp'	=>	'aaaaaaa',
					'password_valid'	=>	'aa',
					'password_conf'	=>	'aa',

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
		$this->assertTrue( array_key_exists("betweenJP" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("hiragana_only" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("zenkaku_only" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("tel_fax_jp" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("mobile_email_jp" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("password_valid" , $this->ValidationRule->validationErrors ) );

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
					'betweenJP'	=>	'あいうえおかきくけこ',
					'hiragana_only'	=>	'ひらがな',
					'zenkaku_only'	=>	'全角のみです',
					'tel_fax_jp'	=>	'03-1111-2222',
					'mobile_email_jp'	=>	'hoge..aa@softbank.ne.jp',
					'password_valid'	=>	'hoge1245',
					'password_conf'	=>	'hoge1245',
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
		$this->assertFalse( array_key_exists("betweenJP" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("hiragana_only" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("zenkaku_only" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("tel_fax_jp" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("mobile_email_jp" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("password_valid" , $this->ValidationRule->validationErrors ) );

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

	//betweenJP テスト
	function testValidataionBetweenJP(){

		$setFailData = array('ああ','abあい', 'aabbccddええおお' );
		$setSuccessData = array('abcde', 'aabbccddええ', '1122334');

		$field = 'betweenJP';

		$this->_failSuccessTest($setFailData, $setSuccessData, $field);

	}

	//hiragana_only テスト
	function testValidataionHiraganaOnly(){

		$setFailData = array('あカナ','abあい', '0011ええおお','漢字も' );
		$setSuccessData = array('がぎぁ', 'たーいへーいよー', 'にゃぴょにょ');

		$field = 'hiragana_only';

		$this->_failSuccessTest($setFailData, $setSuccessData, $field);
	}


	//zenkaku_only テスト
	function testValidataionZenkakuOnly(){

		$setFailData = array('*カナ','abあい', '0011ええおお','漢字も!' );
		$setSuccessData = array('漢字も', 'カタカナも', '今日はグッド！！');

		$field = 'zenkaku_only';

		$this->_failSuccessTest($setFailData, $setSuccessData, $field);
	}


	//tel_fax_jp テスト
	function testValidataionTelFaxJp(){

		$setFailData = array('03-111111-22222', 'aaa-cc-111', 'あああ-222' );
		$setSuccessData = array('03-1111-2222', '0565-23-2222', '011-222-1111');

		$field = 'tel_fax_jp';

		$this->_failSuccessTest($setFailData, $setSuccessData, $field);
	}


	//mobile_email_jp テスト
	function testValidataionMobileEmailJp(){

		$setFailData = array('hoge', 'aa@aaaa', 'aa#!"@aa.com' );
		$setSuccessData = array('hoge@docomo.ne.jp', 'hoge..aa@ezweb.ne.jp', 'a_._.e@softbank.ne.jp');

		$field = 'mobile_email_jp';

		$this->_failSuccessTest($setFailData, $setSuccessData, $field);

	}


	//password_valid テスト
	function testValidataionPasswordValid(){

		$setFailData = array('hoge', 'aa@aaaa', 'aa#!"@aa.com','あああああ','123456789aa' );
		$setSuccessData = array('hogeaaaa', '12345567', 'aaa13', '123456789a');

		$field = 'password_valid';
		$field_conf = 'password_conf';

		//失敗パターン
		$data = array();
		foreach($setFailData as $key => $value){
			$data['ValidationRule'][$field] = $value;
			$data['ValidationRule'][$field_conf] = $value;
			$this->assertTrue( $this->ValidationRule->create( $data ) );
			$this->assertFalse( $this->ValidationRule->validates() );
			$this->assertTrue( array_key_exists($field , $this->ValidationRule->validationErrors ) );
		}

		//成功パターン
		$data = array();
		foreach($setSuccessData as $key => $value){
			$data['ValidationRule'][$field] = $value;
			$data['ValidationRule'][$field_conf] = $value;
			$this->assertTrue( $this->ValidationRule->create( $data ) );
			$this->assertTrue( $this->ValidationRule->validates() );
			$this->assertFalse( array_key_exists($field , $this->ValidationRule->validationErrors ) );
		}


	}


	function _failSuccessTest($setFailData = array(),$setSuccessData = array(),$field ) {

		//失敗パターン
		$data = array();
		foreach($setFailData as $key => $value){
			$data['ValidationRule'][$field] = $value;
			$this->assertTrue( $this->ValidationRule->create( $data ) );
			$this->assertFalse( $this->ValidationRule->validates() );
			$this->assertTrue( array_key_exists($field , $this->ValidationRule->validationErrors ) );
		}

		//成功パターン
		$data = array();
		foreach($setSuccessData as $key => $value){
			$data['ValidationRule'][$field] = $value;
			$this->assertTrue( $this->ValidationRule->create( $data ) );
			$this->assertTrue( $this->ValidationRule->validates() );
			$this->assertFalse( array_key_exists($field , $this->ValidationRule->validationErrors ) );
		}


	}


	//比較対象のフィールドが存在しない場合でもエラーが出ないか確認テスト
	function testValidataionCompare2fieldWithEmptyField(){

		$data = array(
				'ValidationRule' => array(
					'valuediff'	=>	'あいうえお',
					),
				);

		$this->assertTrue( $this->ValidationRule->create( $data ) );
		$this->assertFalse( $this->ValidationRule->validates() );

		$this->assertTrue( array_key_exists("valuediff" , $this->ValidationRule->validationErrors ) );

	}


	/**
	 * testDatetimeYyyymmdd method
	 *
	 * @access public
	 * @return void
	 */
	function testDatetimeYyyymmdd() {

		$setSuccessData = array('2006-12-27 12:22', '2006.12.27 12:22AM', '2006/12/27 12:22PM', '2006 12 27 12:22' );
		$setFailData = array('2006-11-31 12:22', '2006.11.31 12:22', '2006/11/31 12:22', '2006 11 31 12:22', '');

		$field = 'datetime_valid';

		$this->_failSuccessTest($setFailData, $setSuccessData, $field);

	}



}

?>
