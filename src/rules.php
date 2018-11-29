<?php
namespace nanaksr\validator;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation; 

class rules
{
    protected function ValidateFullName($value){
        $this->Results[$this->paramKey] = Ucwords(rtrim($value));
        if (!preg_match("/^[A-Za-z]([a-zA-Z. ]{2,100}[a-zA-Z.]{1})$/",$value)){
            return false;
        }
        return true;
    }
    
    protected function ValidateEmail($value){
        $this->Results[$this->paramKey] = strtolower($value);
        $emailValdtr = new EmailValidator();
        return $emailValdtr->isValid($value, new RFCValidation());
    }
    
    protected function ValidatePhone($value){
        $this->Results[$this->paramKey] = rtrim($value);
        if (!preg_match("/^\+\d{1,3}\d[0-9]{8,14}$/",$value)){
            return false;
        }
        return true;
    }
    
    protected function ValidateAlpha($value){
        $this->Results[$this->paramKey] = $value;
        if (!ctype_alpha($value)){
            return false;
        }
        return $this;
    }
    
    protected function ValidateAlNum($value){
        $this->Results[$this->paramKey] = $value;
        if (!ctype_alnum($value)){
            return false;
        }
        return $this;
    }
    
    protected function ValidateDecimal($value){
        $this->Results[$this->paramKey] = number_format((float)$value, 2, '.', '');
        if(!preg_match('/^([1-9]{1,}|[0-91-9]{1,}.[0-9]{1,2})$/', $value)){
            return false;
        }
        return $this;
    }
    
    protected function ValidateInt($value){
        $this->Results[$this->paramKey] = rtrim(base_convert($value, 10, 8));
        if(!filter_var($value, FILTER_VALIDATE_INT)){
            return false;
        }
        return $this;
    }
    
    protected function ValidateStringDigit($value){
        $this->Results[$this->paramKey] = $value;
        if (!is_string($value)){
            return false;
        }
        if(!ctype_digit($value)){
            return false;
        }
        return true;
    }
}
?>