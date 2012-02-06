<?php

App::import('Component', 'Security');
App::import('Component', 'Auth');


/**
 * Base model that to load AddValidationRule behavior on every test model.
 *
 * @package app.tests
 * @subpackage app.tests.cases.behaviors
 */
class ValidationRule extends CakeTestModel
{

	/**
	 * Name for this model
	 *
	 * @var string
	 * @access public
	 */
	var $name = 'ValidationRule';
	/**
	 * Behaviors for this model
	 *
	 * @var array
	 * @access public
	 */
	var $actsAs = array('Cakeplus.AddValidationRule');

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
				"rule5" => array('rule' => array('spaceOnly'),
					'message' => 'スペース以外も入力してください'
					),
				),
			'alphanumber' => array(
				"rule7" => array('rule' => array('alphaNumber'),
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
					"rule6" => array('rule' => array('katakanaOnly'),
						'message' => 'カタカナのみ入力してください'
						),
					),
			'betweenJP' => array(
					"rule7" => array('rule' => array('betweenJP', 5, 10),
						'message' => '5文字以上10文字以内です'
						),
					),
			'hiraganaOnly' => array(
					"rule8" => array('rule' => array('hiraganaOnly'),
						'message' => 'ひらがなのみ入力してください'
						),
					),
			'zenkakuOnly' => array(
					"rule9" => array('rule' => array('zenkakuOnly'),
						'message' => '全角のみ入力してください'
						),
					),
			'telFaxJp' => array(
					"rule10" => array('rule' => array('telFaxJp'),
						'message' => '正しい電話番号を入力してください'
						),
					),
			'mobileEmailJp' => array(
					"rule11" => array('rule' => array('mobileEmailJp'),
						'message' => '正しい携帯メールアドレスを入力して下さい'
						),
					),
			'passwordValid' => array(
					"rule12" => array('rule' => array('passwordValid','password_conf', 5, 10),
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

	public $fixtures = array('plugin.cakeplus.validation_rule');

	function setUp() {
		$this->ValidationRule = ClassRegistry::init('ValidationRule');
	}

	function startTest($method) {
	}

	function endTest($method) {
	}

	/**
	 * 複数のテストをまとめて実行するメソッド
	 * 失敗ケースの値と、成功ケースの値をそれぞれ配列でセットする
	 */
	function _failSuccessTest($setFailData = array(),$setSuccessData = array(),$field ) {

		//失敗パターン
		$data = array();
		foreach($setFailData as $key => $value){
			$data['ValidationRule'][$field] = $value;
			$this->assertIdentical( $this->ValidationRule->create( $data ) , $data);
			$this->assertFalse( $this->ValidationRule->validates() );
			$this->assertTrue( array_key_exists($field , $this->ValidationRule->validationErrors ) );
		}

		//成功パターン
		$data = array();
		foreach($setSuccessData as $key => $value){
			$data['ValidationRule'][$field] = $value;
			$this->assertIdentical( $this->ValidationRule->create( $data ) , $data);
			$this->assertTrue( $this->ValidationRule->validates() );
			$this->assertFalse( array_key_exists($field , $this->ValidationRule->validationErrors ) );
		}


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
					'hiraganaOnly'	=>	'カタカナ',
					'zenkakuOnly'	=>	'090abc',
					'telFaxJp'	=>	'abcde',
					'mobileEmailJp'	=>	'aaaaaaa',
					'passwordValid'	=>	'aa',
					'password_conf'	=>	'aa',

					),
				);

		$this->assertIdentical( $this->ValidationRule->create( $data ) , $data);

		$this->assertFalse( $this->ValidationRule->validates() );

		$this->assertTrue( array_key_exists("valuediff" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("spaceonly" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("alphanumber" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("maxlengthjp" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("minlengthjp" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("katakanaonly" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("betweenJP" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("hiraganaOnly" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("zenkakuOnly" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("telFaxJp" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("mobileEmailJp" , $this->ValidationRule->validationErrors ) );
		$this->assertTrue( array_key_exists("passwordValid" , $this->ValidationRule->validationErrors ) );

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
					'hiraganaOnly'	=>	'ひらがな',
					'zenkakuOnly'	=>	'全角のみです',
					'telFaxJp'	=>	'03-1111-2222',
					'mobileEmailJp'	=>	'hoge..aa@softbank.ne.jp',
					'passwordValid'	=>	'hoge1245',
					'password_conf'	=>	'hoge1245',
					),
				);

		$this->assertIdentical( $this->ValidationRule->create( $data ) , $data);
		$this->assertTrue( $this->ValidationRule->validates() );


		$this->assertFalse( array_key_exists("valuediff" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("spaceonly" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("alphanumber" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("maxlengthjp" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("minlengthjp" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("katakanaonly" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("betweenJP" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("hiraganaOnly" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("zenkakuOnly" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("telFaxJp" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("mobileEmailJp" , $this->ValidationRule->validationErrors ) );
		$this->assertFalse( array_key_exists("passwordValid" , $this->ValidationRule->validationErrors ) );

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

		$this->assertIdentical( $this->ValidationRule->create( $data ) , $data);
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
		$this->assertIdentical( $this->ValidationRule->create( $data ) , $data);
		$this->assertFalse( $this->ValidationRule->validates() );
		$this->assertTrue( array_key_exists("password" , $this->ValidationRule->validationErrors ) );


		//AuthComponent::passwordを使ってハッシュ化　同一値でバリデーションエラーがないことを確認
		$data = array(
				'ValidationRule' => array(
					'password'	=>	AuthComponent::password('abc123cvb'),
					'password_conf'	=>	'abc123cvb',
					),
				);
		$this->assertIdentical( $this->ValidationRule->create( $data ) , $data);
		$this->assertTrue( $this->ValidationRule->validates() );
		$this->assertFalse( array_key_exists("password" , $this->ValidationRule->validationErrors ) );


		//AuthComponent::passwordを使ってハッシュ化　異なる値でバリデーションエラーに引っかかるテスト
		$data = array(
				'ValidationRule' => array(
					'password'	=>	AuthComponent::password('abc123cvb'),
					'password_conf'	=>	'hoge111',
					),
				);
		$this->assertIdentical( $this->ValidationRule->create( $data ) , $data);
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

	//hiraganaOnly テスト
	function testValidataionHiraganaOnly(){

		$setFailData = array('あカナ','abあい', '0011ええおお','漢字も' );
		$setSuccessData = array('がぎぁ', 'たーいへーいよー', 'にゃぴょにょ');

		$field = 'hiraganaOnly';

		$this->_failSuccessTest($setFailData, $setSuccessData, $field);
	}


	//zenkakuOnly テスト
	function testValidataionZenkakuOnly(){

		$setFailData = array('*カナ','abあい', '0011ええおお','漢字も!' );
		$setSuccessData = array('漢字も', 'カタカナも', '今日はグッド！！');

		$field = 'zenkakuOnly';

		$this->_failSuccessTest($setFailData, $setSuccessData, $field);
	}


	//telFaxJp テスト
	function testValidataionTelFaxJp(){

		$setFailData = array('03-111111-22222', 'aaa-cc-111', 'あああ-222' );
		$setSuccessData = array('03-1111-2222', '0565-23-2222', '011-222-1111');

		$field = 'telFaxJp';

		$this->_failSuccessTest($setFailData, $setSuccessData, $field);
	}


	//mobileEmailJp テスト
	function testValidataionMobileEmailJp(){

		$setFailData = array('hoge', 'aa@aaaa', 'aa#!"@aa.com' );
		$setSuccessData = array('hoge@docomo.ne.jp', 'hoge..aa@ezweb.ne.jp', 'a_._.e@softbank.ne.jp');

		$field = 'mobileEmailJp';

		$this->_failSuccessTest($setFailData, $setSuccessData, $field);

	}


	//passwordValid テスト
	function testValidataionPasswordValid(){

		$setFailData = array('hoge', 'aa@aaaa', 'aa#!"@aa.com','あああああ','123456789aa' );
		$setSuccessData = array('hogeaaaa', '12345567', 'aaa13', '123456789a');

		$field = 'passwordValid';
		$field_conf = 'password_conf';

		//失敗パターン
		$data = array();
		foreach($setFailData as $key => $value){
			$data['ValidationRule'][$field] = $value;
			$data['ValidationRule'][$field_conf] = $value;
			$this->assertIdentical( $this->ValidationRule->create( $data ) , $data);
			$this->assertFalse( $this->ValidationRule->validates() );
			$this->assertTrue( array_key_exists($field , $this->ValidationRule->validationErrors ) );
		}

		//成功パターン
		$data = array();
		foreach($setSuccessData as $key => $value){
			$data['ValidationRule'][$field] = $value;
			$data['ValidationRule'][$field_conf] = $value;
			$this->assertIdentical( $this->ValidationRule->create( $data ) , $data);
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

		$this->assertIdentical( $this->ValidationRule->create( $data ) , $data);
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