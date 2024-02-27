<?php
    if (!class_exists('cl_action')) {
        class cl_action {
            
            static function returnResult($res)
            {
                echo '<wpadm>' . cl_core::_pack( $res ) . '</wpadm>'; 
                exit;
            }
            
            static function ping()
            {
                $return = array('result' => 'pong', 'pl' => 'content-links');
                self::returnResult($return);
            }
        }
    }
?>
