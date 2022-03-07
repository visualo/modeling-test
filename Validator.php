<?php

class Validator {

    private $_errors = [];

    public function validate($src, $rules = [] ){

        foreach($src as $item => $item_value){
            if(key_exists($item, $rules)){
                foreach($rules[$item] as $rule => $rule_value){

                    if(is_int($rule))
                         $rule = $rule_value;

                    switch ($rule){
                        case 'required':
                        if(empty($item_value) && $rule_value){
                            $this->addError($item,ucwords($item). ' required');
                        }
                        break;
                        case 'isString':
                        if(!is_string($item_value) && $rule_value){
                            $this->addError($item, ucwords($item). ' should be a string');
                        }
                        break;
                        case 'isArray':
                        if(!is_array($item_value) && $rule_value){
                            $this->addError($item, ucwords($item). ' should be an array');
                        }
                        break;
                        case 'isAlpha':
                        if(!ctype_alpha($item_value) && $rule_value){
                            $this->addError($item, ucwords($item). ' should be alphabetic characters');
                        }
                        break;
                        case 'min':
                        if(strlen($item_value) < $rule_value){
                            $this->addError($item, ucwords($item). ' should be minimum '.$rule_value. ' characters');
                        }       
                        break;
                        case 'max':
                        if(strlen($item_value) > $rule_value){
                            $this->addError($item, ucwords($item). ' should be maximum '.$rule_value. ' characters');
                        }
                        break;
                    }
                }
            }
        }    
    }

    private function addError($item, $error){
        $this->_errors[$item][] = $error;
    }

    public function error(){
        if(empty($this->_errors)) return false;
        return $this->_errors;
    }
}