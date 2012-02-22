<?php


/**
 * Formhidden Helper: create html hidden tags.
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
 * =====Usage=====
 * //in controller
 *   var $helpers = array('Cakeplus.Formhidden');
 *
 * //in view(ctp file) for using $this->data
 *   <?php echo $formhidden->hiddenVars(); ?>
 *
 * //in view(ctp file) for using  $data_arr parameter
 *   <?php echo $formhidden->hiddenVars($data_arr); ?>
 *
 * ===============
 *
 */
class FormhiddenHelper extends Helper {
    var $helpers = array('Form');

    // String data of Hidden tags.
    var $hidden_output = null;


    /**
     * construct html hidden tag
     *
     * @param array $data_arr //if not set, using $this->data
     * @return String
     */
    function hiddenVars( $data_arr = null ) {
        $data = $this->request->data;

        if( empty($data) && empty($data_arr) ){ return; }
        if( !is_array($data_arr) || empty($data_arr) ){
            $data_arr = $data;
        }

        $this->_createHidden( $data_arr );

        $output = $this->hidden_output;
        $this->hidden_output = null;
        return $output;
    }


    function _createHidden( $data, $parent_key = null ){
        if( is_array( $data ) ){

            foreach( $data as $key => $val ){
                $parent_key_arr = ( isset($parent_key) ) ? $parent_key . '.' . $key : $key ;
                self::_createHidden( $val, $parent_key_arr );
            }

        }else{
            $this->hidden_output .= $this->Form->hidden( $parent_key )."\n";

        }

    }


}