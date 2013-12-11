<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

/**
 * @property string $username 用户名
 * @property string $password 密码
 * @property string $email 邮箱
 */
class User extends Model implements UserInterface, RemindableInterface {

    /**
     * 关系定义，拥有多个Board，我创建的board
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function myBoards(){
        return $this->hasMany('board','id','creator_id');
    }

    /**
     * 关系定义，多对多于Board，获取当前用户参与的所有board模型
     * @return mixed
     */
    public function boards(){
        return $this->belongsToMany('Board','board_members','user_id','board_id');
    }

    /**
     * active soft delete
     * 
     * @var bool  
     */
    protected $softDelete = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password','deleted_at');

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier() {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword() {
        return $this->password;
    }

    public static function boot() {

        parent::boot();

        static::saving(function($model)
        {
            if (Hash::needsRehash($model->password)) {
                $model->password = Hash::make($model->password);
            }
            return true;
        });
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail() {
        return $this->email;
    }
    
    public function getRegisterValidator() {
        return Validator::make(
                        $this->getAttributes(), array(
                    'username' => 'required|min:5|max:32|unique:users',
                    'password' => 'required|min:8',
                    'email' => 'required|email|unique:users'
        ));
    }

}
