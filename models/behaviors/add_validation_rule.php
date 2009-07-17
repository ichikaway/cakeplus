<?php

/**
 * 独自のバリデーションルールを追加するbehavior プラグイン
 * 内部文字コードはUTF-8（バリデーションで渡す文字データはUTF-8となります）
 *
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2009, Yasushi Ichikawa. (http://d.hatena.ne.jp/cakephper/)
 * @link          http://d.hatena.ne.jp/cakephper/
 * @package       cakeplus
 * @subpackage    cakeplus
 * @version       0.02
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 *
 * =====利用方法=====
 * 各モデルファイルで、下記のように使う。app_modelにactsAsで指定しても可
 *	var $actsAs = array('Cakeplus.AddValidationRule');
 *
 * 各モデルファイル内のバリデーションの書き方は下記を参考に。
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
 * Authコンポーネントでパスワードフィールドがハッシュ化されている場合は、
 * checkCompareの第3配列にtrueを指定する
 * 	var $validate = array(
 * 		'password' => array(
 *			"rule" => array('rule' => array('checkCompare', '_conf',true),
 * 				'message' => '値が違います'
 * 			),
 * 		),
 * 	);
 *
 *
 */
class AddValidationRuleBehavior extends ModelBehavior {

    function setup(&$model, $config = array())
    {
        //$this->settings = $config;
        mb_internal_encoding("UTF-8");
    }


	/**
	 * マルチバイト用バリデーション　文字数上限チェック
	 *
	 * @param array &$model
	 * @param array $wordvalue
	 * @param int $length
	 * @return boolean
	 */
	function maxLengthJP( &$model, $wordvalue, $length ) {
		$word = array_shift($wordvalue);
		//return( mb_strlen( $word, mb_detect_encoding( $word ) ) <= $length );
		return( mb_strlen( $word ) <= $length );
	}

	/**
	 * マルチバイト用バリデーション　文字数下限チェック
	 *
	 * @param array &$model
	 * @param array $wordvalue
	 * @param int $length
	 * @return boolean
	 */
	function minLengthJP( &$model, $wordvalue, $length ) {
		$word = array_shift($wordvalue);
		return( mb_strlen( $word ) >= $length );
	}


	/**
	 * フィールド値の比較
	 * emailとemail_confフィールドを比較する場合などに利用
	 * _confは$suffixによって変更可能
	 * authにtrueを指定すると、Authコンポーネントのパスワードフィールドを前提として
	 * 　比較するpassword_confフィールドの値をハッシュ化する
	 *
	 * @param array &$model
	 * @param array $wordvalue
	 * @param string $suffix
	 * @param boolean $auth
	 * @return boolean
	 */
	function checkCompare( &$model, $wordvalue , $suffix = '_conf', $auth = false ){

		$fieldname = key($wordvalue);
		if( $auth === true ){
			return ( $model->data[$model->alias][$fieldname] === Security::hash($model->data[$model->alias][ $fieldname . $suffix ], null, true) );
		}else{
			return ( $model->data[$model->alias][$fieldname] === $model->data[$model->alias][ $fieldname . $suffix ] );
		}



	}



	/**
	 * 全角カタカナ以外が含まれていればエラーとするバリデーションチェック
	 *
	 *
	 * @param array &$model
	 * @param array $wordvalue
	 * @return boolean
	 */
	function katakana_only( &$model, $wordvalue){

	    $value = array_shift($wordvalue);

	    return preg_match("/^[ァ-ヶー゛゜]*$/u", $value);

	}




	/**
	 * 全角、半角スペースのみであればエラーとするバリデーションチェック
	 *
	 * @param array &$model
	 * @param array $wordvalue
	 * @return boolean
	 */
	function space_only( &$model, $wordvalue){

	    $value = array_shift($wordvalue);

	    if( mb_ereg_match("^(\s|　)+$", $value) ){

		    return false;
	    }else{
	        return true;
	    }
	}


	/**
	 * only Allow 0-9, a-z , A-Z
	 *
	 * @param array ref &$model
	 * @param array $wordvalue
	 * @return boolean
	 */
	function alpha_number( &$model, $wordvalue ){
		$value = array_shift($wordvalue);
		return preg_match( "/^[a-zA-Z0-9]*$/", $value );

	}

}

?>