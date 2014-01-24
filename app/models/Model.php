<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model
 *
 * @author Administrator
 */
use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent {

    /**
     * 错误信息
     * @var array
     */
    public $errors = array();

    /**
     * 获取所有错误信息
     * @return array
     */
    public function getErrors(){
        return $this->errors;
    }

    /**
     * 设置所有错误信息
     * @param array $errors
     * @return array
     */
    public function setErrors(array $errors){
        return $this->errors = $errors;
    }

    /**
     * 添加一条错误信息
     * @param string $attribute
     * @param mixed $error
     * @return mixed
     */
    public function addError($attribute,$error){
        return $this->errors[$attribute][] = $error;
    }

    /**
     * 获取一条错误信息
     * @param string $attribute
     * @return array
     */
    public function getError($attribute=null){
        if(!isset($this->errors[$attribute])){
            return $this->errors[$attribute];
        }
        return array();
    }

    /**
     * 根据验证规则验证数据有效性
     * @return bool
     */
    public function validate(){
        $validator = $this->getValidator();
        if ($validator->fails()) {
            $this->setErrors($validator->messages()->toArray());
            return false;
        }
        return true;
    }

    /**
     * 验证规则
     * @return \Illuminate\Validation\Validator
     */
    public function getValidator() {
        return Validator::make(
            $this->getAttributes(), array(
        ));
    }


}
