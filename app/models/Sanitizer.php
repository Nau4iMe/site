<?php

class Sanitizer
{
    protected $rules = 'trim|strip_tags';
    protected $filtered = null;

    public function __construct($input = array(), $rules = null)
    {
        $this->rules = explode('|', $rules === null ? $this->rules : $rules);
        $this->apply_sanitize($input);
    }

    protected function apply_sanitize($input)
    {
        if (is_array($input)) {
            foreach($input as $key => $value) {
                foreach ($this->rules as $rule) {
                    if(!function_exists($rule)) {
                        throw new Exception("Function {$rule} does not exist!", 1);
                    }
                    $this->filtered[$key] = call_user_func($rule, $value);
                }
            }
        }
    }

    public function get()
    {
        return $this->filtered;
    }

}
