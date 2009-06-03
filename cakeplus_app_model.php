<?php

class CakeplusAppModel extends AppModel {


	/**
	 * マルチバイト用バリデーション　文字数上限チェック
	 *
	 * 利用例 modelファイルのバリデーション定義にて
	 * 	var $validate = array(
	 *		'name' => array(
	 *			"rule1" => array('rule' => array('maxLengthJP', 10),
	 *				'message' => 'お名前は10文字以内です。'
	 *			),
	 *	);
	 *
	 * @param array $wordvalue
	 * @param int $length
	 * @return boolean
	 */
	function maxLengthJP($wordvalue, $length) {
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
	 *	);
	 *
	 * @param array $wordvalue
	 * @param int $length
	 * @return boolean
	 */
	function minLengthJP($wordvalue, $length) {
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
	 *			"rule1" => array('rule' => array('checkCompare', '_conf'),
	 *				'message' => '【メールアドレス】 と【メールアドレス(確認)】の内容が異なります'
	 *			),
	 *	);
	 *
	 *
	 * @param array $valid_field
	 * @param string $suffix
	 * @return boolean
	 */
	function checkCompare( $valid_field , $suffix ){

		$fieldname = key($valid_field);

		if($this->data[$this->name][$fieldname] === $this->data[$this->name][ $fieldname . $suffix ]){
			return true;
		}

		return false;

	}



	/**
	 * 全角カタカナ以外が含まれていればエラーとするバリデーションチェック
	 *
	 * @param array $wordvalue
	 * @return boolean
	 */
	function katakana_only($wordvalue){

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
	 * @param array $wordvalue
	 * @return boolean
	 */
	function space_only($wordvalue){

	    $value = array_shift($wordvalue);

	    if( mb_ereg_match("^(\s|　)+$", $value) ){

		    return false;
	    }else{
	        return true;
	    }
	}

}

?>