<?php

/**
 * 独自のバリデーションルールを追加するbehavior プラグイン
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2009, Yasushi Ichikawa. (http://d.hatena.ne.jp/cakephper/)
 * @link          http://d.hatena.ne.jp/cakephper/
 * @package       cakeplus
 * @subpackage    cakeplus
 * @version       0.01
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 *
 * =====利用方法=====
 * 各モデルファイルで、下記のように使う。app_modelにactsAsで指定しても可
 *	var $actsAs = array('Cakeplus.AddValidationRule');
 *
 * バリデーションの書き方は下記を参考に。
 * 	var $validate = array(
 * 		'test' => array(
 *			"rule2" => array('rule' => array('maxLengthJP', 5),
 * 				'message' => '5文字以内です'
 * 			),
 *			"rule3" => array('rule' => array('minLengthJP', 2),
 * 				'message' => '2文字以上です'
 * 			),
 *			"rule4" => array('rule' => array('checkCompare', '_conf'),
 * 				'message' => '値が違います'
 * 			),
 * 			"rule5" => array('rule' => array('space_only'),
 * 				'message' => 'スペース以外も入力してください'
 * 			),
 * 			"rule6" => array('rule' => array('katakana_only'),
 *				'message' => 'カタカナのみ入力してください'
 * 			),
 * 		),
 * 	);
 *
 *
 */
class AddValidationRuleBehavior extends ModelBehavior {


	/**
	 * マルチバイト用バリデーション　文字数上限チェック
	 *
	 * 利用例 modelファイルのバリデーション定義にて
	 * 	var $validate = array(
	 *		'name' => array(
	 *			"rule1" => array('rule' => array('maxLengthJP', 10),
	 *				'message' => 'お名前は10文字以内です。'
	 *			),
	 * 		),
	 *	);
	 *
	 * @param array $this_data
	 * @param array $wordvalue
	 * @param int $length
	 * @return boolean
	 */
	function maxLengthJP( $this_data, $wordvalue, $length ) {

		//$wordvalueは連想配列で渡されるためarray_shiftで対応
		$word = array_shift($wordvalue);
		return( mb_strlen( $word, mb_detect_encoding( $word ) ) <= $length );

	}

	/**
	 * マルチバイト用バリデーション　文字数下限チェック
	 *
	 * 利用例 modelファイルのバリデーション定義にて
	 * 	var $validate = array(
	 *		'account' => array(
	 *			"rule1" => array('rule' => array('minLengthJP', 5 ),
	 *				'message' => 'アカウント名は5文字以上です。'
	 *			),
	 * 		),
	 *	);
	 *
	 * @param array $this_data
	 * @param array $wordvalue
	 * @param int $length
	 * @return boolean
	 */
	function minLengthJP( $this_data, $wordvalue, $length ) {
		$word = array_shift($wordvalue);
		return( mb_strlen( $word, mb_detect_encoding( $word ) ) >= $length );
	}


	/**
	 * フィールド値の比較
	 * emailとemail_confフィールドを比較する場合などに利用
	 * _confは$suffixによって変更可能
	 *
	 * 利用例 modelファイルのバリデーション定義にてemailとemail_confフィールドの比較
	 * 	var $validate = array(
	 *		'email' => array(
	 *			"rule1" => array('rule' => array('checkCompare', '_conf' ),
	 *				'message' => '【メールアドレス】 と【メールアドレス(確認)】の内容が異なります'
	 *			),
	 * 		),
	 *	);
	 *
	 * @param array $this_data
	 * @param array $wordvalue
	 * @param string $suffix
	 * @return boolean
	 */
	function checkCompare( $this_data, $wordvalue , $suffix  ){

		$fieldname = key($wordvalue);
		$this_name = $this_data->name;

		if( $this_data->data[$this_name][$fieldname] === $this_data->data[$this_name][ $fieldname . $suffix ]){
			return true;
		}

		return false;

	}



	/**
	 * 全角カタカナ以外が含まれていればエラーとするバリデーションチェック
	 *
	 * 	var $validate = array(
	 *		'text' => array(
	 *			"rule1" => array('rule' => array('katakana_only' ),
	 *				'message' => 'カタカナのみご入力下さい'
	 *			),
	 * 		),
	 *	);
	 *
	 *
	 * @param array $this_data
	 * @param array $wordvalue
	 * @return boolean
	 */
	function katakana_only( $this_data, $wordvalue){

	    $value = array_shift($wordvalue);

	    if( preg_match("/^[ア-ンヴァィゥェォャュョッー゛゜]*$/u", $value)){
	        return true;
	    }else{
	        return false;
	    }
	}




	/**
	 * 全角、半角スペースのみであればエラーとするバリデーションチェック
	 *
	 * 	var $validate = array(
	 *		'text' => array(
	 *			"rule1" => array('rule' => array('space_only' ),
	 *				'message' => 'スペース以外も入力下さい'
	 *			),
	 * 		),
	 *	);
	 *
	 * @param array $this_data
	 * @param array $wordvalue
	 * @return boolean
	 */
	function space_only( $this_data, $wordvalue){

	    $value = array_shift($wordvalue);

	    if( mb_ereg_match("^(\s|　)+$", $value) ){

		    return false;
	    }else{
	        return true;
	    }
	}

}

?>