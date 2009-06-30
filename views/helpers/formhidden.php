<?php


/**
 * Formhidden Helper: construct html hidden tag from Post data .
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
 * =====Usage=====
 * //in controller
 *   var $helpers = array('Cakeplus.Formhidden');
 *
 * //in view(ctp file)
 *   <?php echo $formhidden->hiddenVars(); ?>
 * ===============
 *
 */
class FormhiddenHelper extends Helper {
    var $helpers = array('Form');


    function hiddenVars() {
        if( empty($this->data) ){ return; }

        $this->_createHidden( $this->data );

        return ;
    }


	function _createHidden( $data, $parent_key = null ){
		if( is_array( $data ) ){

			foreach( $data as $key => $val ){
				$parent_key_arr = ( isset($parent_key) ) ? $parent_key . '.' . $key : $key ;
				self::_createHidden( $val, $parent_key_arr );
			}

		}else{
			echo $this->Form->hidden( $parent_key )."\n";
		}

	}


}

?>
