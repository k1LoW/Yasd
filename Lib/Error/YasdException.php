<?php
/**
 * YasdException
 *
 */
class YasdException extends CakeException {

    public function __construct($message = null, $code = 500) {
        if (empty($message)) {
            $message = __('Yasd Error.');
        }
        parent::__construct($message, $code);
    }
}
