<?php
namespace nanaksr\validator;

use \Exception;

use nanaksr\validator\rules as rules;

class validation extends rules
{
    protected $Errors  = [];
    protected $Results = [];
    
    private $param = [];
    private $name = null;
    private $dataunset = [];
    
    private $templates = [
        'required' => [
            'id' => 'Bidang {{name}} harus diisi',
            'en' => 'Field of "{{name}}" required'
        ],
        'input' => [
            'id' => 'Format penulisan "{{name}}" Salah',
            'en' => 'Invalid {{name}} Format'
        ],
        'select' => [
            'id' => '{{name}} tidak tersedia',
            'en' => '{{name}} not available'
        ],
        'equal' => [
            'id' => '{{name}} tidak sama',
            'en' => '{{name}} not equal'
        ]
    ];
    
    public function __construct($lang = 'id'){
        if (!in_array($lang, ['id','en'])){
            $lang = 'id';
        }
        $this->lang = $lang;
    }
    
    private function cekParam(){
        if (!is_array($this->param)){
            throw new exception("parameters not found ~v::param(params)");
        }
    }
    
    public function params($params = array()){
        $this->param = $params;
        return $this;
    }
    
    public function setParamKey($paramKey){
        $this->cekParam();
        $this->paramId      = array_search($paramKey, array_keys($this->param));
        $this->paramKey     = $paramKey;
        $this->paramValue   = $this->param[$paramKey];  
        return $this;
    }
    
    public function setName($valName){
        $this->cekParam();
        $this->name = $valName;
        return $this;
    }
    
    private function builErrMsg($type = 'input'){
        $prefix = (is_null($this->name) OR $this->name == null) ? $this->paramValue : $this->name;
        return $this->parseMsg($prefix, $this->templates[$type][$this->lang]);
    }
    
    private function parseMsg($prefix, $message){
        return preg_replace('/{{(\w+)}}/', $prefix, $message);
    }
    
    /** Rule Case **/
    
    public function setRule($datarules){
        $this->cekParam();
        $this->rule = $datarules;
        
        if (!array_key_exists($this->paramKey, $this->param))
        {
            $this->Errors[$this->paramKey] = $this->builErrMsg('required');
        }
        else
        {
            if (method_exists(new rules, $datarules))
            {
                $push = $this->{$datarules}($this->paramValue);
                if (!$push){
                    $this->Errors[$this->paramKey] = $this->builErrMsg('input');
                }
            }
            else
            {
                if (is_array($datarules)){
                    if(!preg_match('/^('.implode('|', $datarules).')$/', $this->paramValue)){
                        $this->Errors[$this->paramKey] = $this->builErrMsg('select');
                    }
                }
                else
                {
                    if (!preg_match($datarules, $this->paramValue)){
                    $this->Errors[$this->paramKey] = $this->builErrMsg('input');
                    }
                }
                $this->Results[$this->paramKey] = $this->paramValue;
            }
        }
        unset($this->name);
    }
    
    /** End Rule Case **/
    
    public function equal($paramEqual){
        $this->cekParam();
        if (!array_key_exists($this->paramKey, $this->param))
        {
            $this->Errors[$this->paramKey] = $this->builErrMsg('required');
        }
        else
        {
            if (!array_key_exists($paramEqual, $this->param)){
                $this->Errors[$this->paramKey] = 'Parameter "'.$paramEqual.'" not found';
            }else{
                if ($this->param[$paramEqual] != $this->paramValue){
                    $this->Errors[$this->paramKey] = $this->builErrMsg('equal');
                }
            }
        }
        $this->Results[$this->paramKey] = $this->paramValue;
    }
    
    public function unsetParamKey(){
        $dataUnset = func_get_args();
        foreach($dataUnset as $data){
            if (!array_key_exists($data, $this->param)){
                $this->Errors[$data] = sprintf('unsetParamKey Error, param "%s" must be require', $data);
            }
        }
        $this->dataunset = $dataUnset;
        return $this;
    }
    
    public function hasError(){
        if (count($this->Errors) > 0){
            return true;
        }
        return false;
    }
    
    public function getErrors(){
        return $this->Errors;
    }
    
    public function hasSuccess(){
        if (count($this->Errors) == 0){
            return true;
        }
        return false;
    }
    
    public function getResults(){
        $replyResult = array_merge($this->param, $this->Results);
        foreach($this->dataunset as $data){
            unset($replyResult[$data]);
        }
        return $replyResult;
    }    
}

?>