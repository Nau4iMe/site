<?php
/**
* Performing validation in model - MVC style
* @author Dayle Rees
* @link http://daylerees.com/trick-validation-within-models
*/
trait Validation
{

    protected $errors;
    
    /**
    * @param $data - what to validate
    * @param $this->rules - Rules to validate to
    */
    public function validate($data)
    {
        $v = Validator::make($data, $this->rules);

        if ($v->fails()) {
            $this->errors = $v->errors();
            return false;
        }
        return true;
    }

    public function getErrors()
    {
        return $this->errors;
    }
    
}
