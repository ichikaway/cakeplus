<?php
/**
 * 独自のバリデーションルールを追加するbehavior プラグイン
 * 内部文字コードはデフォルトUTF-8（オプションで変更可能）
 * Behavior of some validation rules.
 * Internal encoding is UTF-8, can change it with parameter.
 *
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2009, Yasushi Ichikawa. (http://d.hatena.ne.jp/cakephper/)
 * @link          http://d.hatena.ne.jp/cakephper/
 * @package       cakeplus
 * @subpackage    add_validation_rule
 * @version       0.04
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 *
 * =====利用方法=====
 * 各モデルファイルで、下記のように使う。app_modelにactsAsで指定しても可
 * In each model class or app_model, write code as follow.
 *		var $actsAs = array('Cakeplus.AddValidationRule');
 *
 * 内部文字コードを変更したい場合は、オプションで変更可能（デフォルトはUTF-8）
 * If you want to change encoding(UTF-8), write code as follow.
 *		var $actsAs = array('Cakeplus.AddValidationRule' => array('encoding' => 'EUC') );
 *
 *
 * 各モデルファイル内のバリデーションの書き方は下記を参考に。
 * Example: validation definition in a model.
 *		var $validate = array(
 *			'test' => array(
 *				"rule2" => array('rule' => array('maxLengthJP', 5),
 *					'message' => '5文字以内です'
 *				),
 *				"rule3" => array('rule' => array('minLengthJP', 2),
 *					'message' => '2文字以上です'
 *				),
 *				"rule4" => array('rule' => array('compare2fields', 'test_conf'),
 *					'message' => '値が違います'
 *				),
 *				"rule5" => array('rule' => array('spaceOnly'),
 *					'message' => 'スペース以外も入力してください'
 *				),
 *				"rule6" => array('rule' => array('katakanaOnly'),
 *					'message' => 'カタカナのみ入力してください'
 *				),
 *				"rule7" => array('rule' => array('betweenJP', 5, 10),
 *					'message' => '5文字以上、10文字以内です'
 *				),
 *				"rule8" => array('rule' => array('hiraganaOnly'),
 *					'message' => 'ひらがなのみ入力してください'
 *				),
 *				"rule9" => array('rule' => array('zenkakuOnly'),
 *					'message' => '全角文字のみ入力してください'
 *				),
 *				"rule10" => array('rule' => array('datetime'),
 *					'message' => '正しい日時を入力してください'
 *				),
 *			),
 *		);
 *
 * Authコンポーネントでパスワードフィールドがハッシュ化されている場合は、compare2fieldsの第3配列にtrueを指定する
 * Using Auth component, If you want compare password and password confirm field,
 * set "true" in 3rd parameter of compare2fields validation, password_conf field is encrypted.
 *		var $validate = array(
 *			'password' => array(
 *				"rule" => array('rule' => array('compare2fields', 'password_conf',true),
 *					'message' => '値が違います'
 *				),
 *			),
 *		);
 *
 *
 */
class AddValidationRuleBehavior extends ModelBehavior {

	public function setup(Model $model, $config = array()){

		//change encoding with parameter option.
		if( !empty( $config['encoding'] ) ){
			mb_internal_encoding($config['encoding']);
		}else{
			mb_internal_encoding("UTF-8");
		}
	}


	/**
	 * マルチバイト用バリデーション　文字数上限チェック
	 * check max length with Multibyte character.
	 *
	 * @param array &$model	 model object, automatically set
	 * @param array $wordvalue	field value, automatically set
	 * @param int $length max length number
	 * @return boolean
	 */
	public function maxLengthJP(Model $model, $wordvalue, $length ) {
		$word = array_shift($wordvalue);
		return( mb_strlen( $word ) <= $length );
	}

	/**
	 * マルチバイト用バリデーション　文字数下限チェック
	 * check min length with Multibyte character.
	 *
	 * @param array &$model model object, automatically set
	 * @param array $wordvalue field value, automatically set
	 * @param int $length min length number
	 * @return boolean
	 */
	public function minLengthJP(Model $model, $wordvalue, $length ) {
		$word = array_shift($wordvalue);
		return( mb_strlen( $word ) >= $length );
	}


	/**
	 * マルチバイト用のbetweenバリデーション
	 *
	 *
	 * @param array &$model
	 * @param array $wordvalue
	 * @param int $low
	 * @param int $high
	 * @return boolean
	 */
	public function betweenJP(Model $model, $wordvalue, $low, $high) {
		$value = array_shift($wordvalue);
		if ( mb_strlen($value) >= $low && mb_strlen($value) <= $high ) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * フィールド値の比較
	 * emailとemail_confフィールドを比較する場合などに利用
	 * $compare_filedに比較したいフィールド名をセットする（必須）
	 * Compare 2 fields value. Example, email field and email_conf field.
	 * Set field name for comparison in $compare_filed
	 *
	 * authにtrueを指定すると、Authコンポーネントのパスワードフィールドを前提として
	 * 比較するpassword_confフィールドの値をハッシュ化する
	 * If set "true" in $auth, $compare_filed is encrypted with Security::hash.
	 *
	 * @param array &$model	 model object, automatically set
	 * @param array $wordvalue	field value, automatically set
	 * @param string $compare_filed	 set field name for comparison
	 * @param boolean $auth set true, $compare_filed is encrypted with Security::hash
	 * @return boolean
	 */
	public function compare2fields(Model $model, $wordvalue , $compare_field , $auth = false ){

		$field = current($wordvalue);
		$compare = isset($model->data[$model->alias][$compare_field]) ? $model->data[$model->alias][$compare_field] : null;
		if( $auth === true ){
			App::import('Component','Auth');
			return $field === AuthComponent::password($compare);
		} else {
			return $field === $compare;
		}
	}


	/**
	 * 全角ひらがな以外が含まれていればエラーとするバリデーションチェック
	 * 全角ダッシュ「ー」のみ必要と考えられるので追加
	 * Japanese HIRAGANA Validation
	 * @param array &$model
	 * @param array $wordvalue
	 * @return boolean
	 */
	public function hiraganaOnly(Model $model, $wordvalue){
		$value = array_shift($wordvalue);
		return preg_match("/^(\xe3(\x81[\x81-\xbf]|\x82[\x80-\x93]|\x83\xbc))*$/", $value);
	}

	/**
	 * 全角ひらがな以外が含まれていればエラーとするバリデーションチェック
	 * Japanese HIRAGANA Validation
	 * (old name, keep this for backward compatibility)
	 */
	public function hiragana_only(Model $model, $wordvalue){
		return $this->hiraganaOnly($model, $wordvalue);
	}

	/**
	 * 全角カタカナ以外が含まれていればエラーとするバリデーションチェック
	 * Japanese KATAKANA Validation
	 *
	 * @param array &$model
	 * @param array $wordvalue
	 * @return boolean
	 */
	public function katakanaOnly(Model $model, $wordvalue){
		$value = array_shift($wordvalue);

		//\xe3\x82\x9b 濁点゛
		//\xe3\x82\x9c 半濁点゜
		return preg_match("/^(\xe3(\x82[\xa1-\xbf]|\x83[\x80-\xb6]|\x83\xbc|\x82\x9b|\x82\x9c))*$/", $value);
	}

	/**
	 * 全角カタカナ以外が含まれていればエラーとするバリデーションチェック
	 * Japanese KATAKANA Validation
	 * (old name, keep this for backward compatibility)
	 */
	public function katakana_only(Model $model, $wordvalue){
		return $this->katakanaOnly($model, $wordvalue);
	}


	/**
	 * マルチバイト文字以外が含まれていればエラーとするバリデーションチェック
	 * Japanese ZENKAKU Validation
	 *
	 * @param array &$model
	 * @param array $wordvalue
	 * @return boolean
	 */
	public function zenkakuOnly(Model $model, $wordvalue){
		$value = array_shift($wordvalue);
		return !preg_match("/(?:\xEF\xBD[\xA1-\xBF]|\xEF\xBE[\x80-\x9F])|[\x20-\x7E]/", $value);
	}

	/**
	 * マルチバイト文字以外が含まれていればエラーとするバリデーションチェック
	 * Japanese ZENKAKU Validation
	 * (old name, keep this for backward compatibility)
	 */
	public function zenkaku_only(Model $model, $wordvalue){
		return $this->zenkakuOnly($model, $wordvalue);
	}



	/**
	 * 全角、半角スペースのみであればエラーとするバリデーションチェック
	 * Japanese Space only validation
	 *
	 * @param array &$model
	 * @param array $wordvalue
	 * @return boolean
	 */
	public function spaceOnly(Model $model, $wordvalue){
		$value = array_shift($wordvalue);
		if( mb_ereg_match("^(\s|　)+$", $value) ){
			return false;
		}else{
			return true;
		}
	}

	/**
	 * 全角、半角スペースのみであればエラーとするバリデーションチェック
	 * Japanese Space only validation
	 * (old name, keep this for backward compatibility)
	 *
	 */
	public function space_only(Model $model, $wordvalue){
		return $this->spaceOnly($model, $wordvalue);
	}


	/**
	 * only Allow 0-9, a-z , A-Z
	 * check it including Multibyte characters.
	 *
	 * @param array ref &$model
	 * @param array $wordvalue
	 * @return boolean
	 */
	public function alphaNumber(Model $model, $wordvalue ){
		$value = array_shift($wordvalue);
		return preg_match( "/^[a-zA-Z0-9]*$/", $value );
	}

	/**
	 * only Allow 0-9, a-z , A-Z
	 * check it including Multibyte characters.
	 * (old name, keep this for backward compatibility)
	 *
	 */
	public function alpha_number(Model $model, $wordvalue ){
		return $this->alphaNumber($model, $wordvalue);
	}


	/**
	 * Japan Telephone and Fax validation
	 *
	 */
	public function telFaxJp(Model $model, $wordvalue) {
		$value = array_shift($wordvalue);
		$pattern = '/^(0\d{1,4}[\s-]?\d{1,4}[\s-]?\d{1,4}|\+\d{1,3}[\s-]?\d{1,4}[\s-]?\d{1,4}[\s-]?\d{1,4})$/';
		return preg_match( $pattern, $value );
	}

	/**
	 * Japan Telephone and Fax validation
	 * (old name, keep this for backward compatibility)
	 *
	 */
	public function tel_fax_jp(Model $model, $wordvalue) {
		return $this->telFaxJp($model, $wordvalue);
	}


	/**
	 * Mobile Email validation
	 *
	 */
	public function mobileEmailJp(Model $model, $wordvalue) {
		$value = array_shift($wordvalue);
		$pattern = '/^[a-z0-9\._-]{3,30}@(?:[a-z0-9][-a-z0-9]*\.)*(?:[a-z0-9][-a-z0-9]{0,62})\.(?:(?:[a-z]{2}\.)?[a-z]{2,4})$/i';
		return preg_match( $pattern, $value );
	}

	/**
	 * Mobile Email validation
	 * (old name, keep this for backward compatibility)
	 *
	 */
	public function mobile_email_jp(Model $model, $wordvalue) {
		return $this->mobileEmailJp($model, $wordvalue);
	}


	/**
	 * password validation
	 * Only AlphaNumeric , check letter length
	 */
	public function passwordValid(Model $model, $wordvalue , $compare_filed , $min=5, $max=15 ){
		$pass_val = $model->data[$model->alias][ $compare_filed ];
		$pattern = '/^[a-zA-Z0-9]{'. $min .','. $max  .'}$/';
		return preg_match($pattern, $pass_val);

	}
	
	/**
	 * password validation
	 * (old name, keep this for backward compatibility)
	 */
	public function password_valid(Model $model, $wordvalue , $compare_filed , $min=5, $max=15 ){
		return $this->passwordValid($model, $wordvalue , $compare_filed , $min, $max);
	}

	/**
	 * Datetime validation, determines if the string passed is a valid datetime.
	 * Using self date and time validation methods.
	 *
	 * @param string $check a valid datetime string
	 * @param mixed $format Use a string or an array of the keys below. Arrays should be passed as array('dmy', 'mdy', etc)
	 * @param string $regex If a custom regular expression is used this is the only validation that will occur.
	 * @return boolean Success
	 * @access public
	 */
	public function datetime(Model $model, $wordvalue, $format = 'ymd', $regex = null) {
		$_this = new Validation;

		$value = array_shift($wordvalue);

		$pattern = '%^(.+) (\d+:\d+[APap][Mm])$|^(.+) (\d+:\d+)$%';
		preg_match($pattern, $value, $match);
		if(!empty($match[1]) && !empty($match[2])) {
			$date = $match[1];
			$time = $match[2];
		} else if(!empty($match[3]) && !empty($match[4])) {
			$date = $match[3];
			$time = $match[4];
		}

		if(empty($date) || empty($time)){
			return false;
		}

		return $_this->date($date, $format, $regex) && $_this->time($time);
	}

}