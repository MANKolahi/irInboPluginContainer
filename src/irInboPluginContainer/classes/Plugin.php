<?php
/**
 * @package             PluginPackage
 * @author              mohammad ali nassiri
 * @copyright           please_do_not_copy
 */


namespace irInboPluginContainer\classes;

use ArrayAccess;
use irInboPluginContainer\services\InboServiceInterface;

class Plugin implements ArrayAccess {
    protected $contents;

    public function __construct() {
        $this->contents = array();
    }

    public function offsetSet( $offset, $value ) {
        $this->contents[$offset] = $value;
    }

    public function offsetExists($offset) {
        return isset( $this->contents[$offset] );
    }

    public function offsetUnset($offset) {
        unset( $this->contents[$offset] );
    }

    public function offsetGet($offset) {
        if( is_callable($this->contents[$offset]) ){
            return call_user_func( $this->contents[$offset], $this );
        }
        return isset( $this->contents[$offset] ) ? $this->contents[$offset] : null;
    }

    public function run(){
        foreach( $this->contents as $key => $content ){ // Loop on contents
            if( $content instanceof InboServiceInterface){
                $content->run(); // Call run method on object
            }elseif (is_string($content)){
                define($key, $content);
            }
        }
    }
}
