<?php
  /**
   * ValidationPatternsBehavior
   *
   * @uses ModelBehavior
   * @copyright Copyright 2010, Kagasawa-san.
   * @package cakeplus
   * @subpackage validation_patterns
   * @author Kagasawa-san
   * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
   */
  /*
   * ------- Usage ----------------------
   * app_modelで、下記のように$actsAsに設定してください。各モデルにactsAsで指定しても可
   * In each model class or app_model, write code as follow.
   *  var $actsAs = array('Cakeplus.ValidationPatterns');
   *
   * モデルファイル内で$validation_patternsに「'パターン名' => バリデーション」を設定する
   * app_modelで設定すると他のモデルでパターンを利用できます
   * 例)
   *    public $validation_patterns = array(
   *                             // email
   *                             'email_pattern' => array(
   *                                              'notEmptyMail' => array(
   *                                                                      'rule' => 'notempty',
   *                                                                      'allowEmpty' => false,
   *                                                                      'last' => true,
   *                                                                      ),
   *                                              'validMail' => array(
   *                                                                   'rule' => 'email',
   *                                                                   'allowEmpty' => true,
   *                                                                   'last' => true,
   *                                                                   ),
   *                                              'uniqueMail' => array(
   *                                                                    'rule' => 'isUnique',
   *                                                                    'on' => 'create'
   *                                                                    ),
   *                                              ),
   *                             );
   *
   * app_modelで、下記のようにbeforeFilterに設定することで設定したバリデーションパターンが有効になります。
   * In AppModel, write code as follow.
   *
   *  class AppModel extends Model {
   *      var $actsAs = array('Cakeplus.ValidationPatterns');
   *
   *      function beforeValidate(){
   *          $this->setValidationPatterns();
   *          return true;
   *      }
   *  }
   *
   * バリデーションパターンを使用したいバリデーションフィールドでパターン名を指定することで
   * パターンに指定されたバリデーションを設定できます
   * 例)
   *    public $validate = array(
   *                             // email
   *                             'email' => 'email_pattern'
   *                             );
   *
   */
class ValidationPatternsBehavior extends ModelBehavior {

    private $model = array();
    public $settings = array();

    public function setup(Model $model, $config = array()) {
        $this->settings[$model->alias] = $config;
    }

    public function setValidationPatterns(Model $model) {

        if ( !empty($model->validate) ) {
            foreach ($model->validate as $key => $val) {
                if ( !is_array($val) ) {
                    if ( isset($model->validation_patterns[$val]) ) {
                        $model->validate[$key] = $model->validation_patterns[$val];
                    }
                } else {
                    $valids = array();
                    foreach ($val as $key2 => $val2) {
                        if ($key2 === 'rule') {
                            continue 2;
                        }
                        if ( !is_array($val2) ) {
                            if ( isset($model->validation_patterns[$val2]) ) {
                                $valids = array_merge($valids, $model->validation_patterns[$val2]);
                            }
                        } else {
                            $valids[$key2] = $val2;
                        }
                    }

                    $model->validate[$key] = $valids;
                }
            }
        }

    }
  }
