<?php

/**
 * Doctrine_Validator_Confirm
 *
 * @package     Doctrine
 * @subpackage  Validator
 * @author      Mike Wyatt <wyatt.mike@gmail.com>
 */
class Doctrine_Validator_Confirm
{
    /**
     * fetches the post data of a field named $this->field . '_confirmation'
     * and compares it to our own value
     *
     * @param mixed $value
     * @return boolean
     */
    public function validate($value)
    {
		$array = String::lowercase(Inflector::singularize(get_class($this->invoker)));
		$key = $this->field . '_confirmation';
		return isset($_POST[$array]) && isset($_POST[$array][$key]) && $_POST[$array][$key] == $value;
    }
}