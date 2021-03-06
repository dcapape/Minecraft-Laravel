<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {
    protected $table = "users";
    protected $hidden = ["password"];
    public static $rules = array(
        'nick' => 'required|alpha_num|between:2,16|unique:users',
        'email' => 'required|email|min:6|unique:users',
        'password' => 'required|alpha_num|between:6,100|confirmed',
        'password_confirmation' => 'required|alpha_num|between:6,100'
    );
    public static $rulespremium = array(
        'email' => 'required|email|min:6|unique:users',
        'password' => 'required|alpha_num|between:6,100',
    );
    public static function Id($value){
        return User::where('id', $value)->first();
    }
    public static function getIdByNick($value){
        return User::where('nick', $value)->first();
    }
    public static function getNickById($value){
        return User::select('nick')->where('id', $value)->first();
    }
    public static function getuuidByNick($value){
        return User::select('uuid')->where('nick', $value)->first();
    }
    public static function isPremium($value){
        return User::select('premium')->where('uuid', $value)->first()->premium;
    }
    public static function imAdmin(){
        if (!Auth::guest())
          return (User::getGroup(Auth::user()) == "admin") ? true : false;
        else
          return false;
    }
    public static function getGroup($user){
      if ($user->uuid == "")
        return null;
      else{
        if(UPermsUser::getByUUID($user->uuid) !== null){
            if (UPermsUser::getByUUID($user->uuid)->groupName != "")
              return strtolower(UPermsUser::getByUUID($user->uuid)->groupName);
            else
              return "No group";
        }else{
            return "No group";
        }
      }

    }
    public function getAuthIdentifier() {
        return $this->getKey();
    }
    public function getAuthPassword() {
        return $this->password;
    }
    public function getRememberToken() {
        return $this->remember_token;
    }
    public function setRememberToken($value) {
        $this->remember_token = $value;
    }
    public function getRememberTokenName() {
        return "remember_token";
    }
    public function getReminderEmail(){
        return $this->email;
    }
    public static function isAvailable($username){
      $json = file_get_contents('https://axis.iaero.me/accinfo?username='.$username.'&format=json');
      $obj = json_decode($json);

      try {
      if ($obj->data->username == null)
        return false;
      else
        return true;
      }catch(Exception $e) {
        return false;
      }
      /* // OLD USERNAME CHECK SYSTEM
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, "https://visage.surgeplay.com/full/16/".strtolower($username));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $output = curl_exec($ch);

      if(curl_getinfo($ch, CURLINFO_HTTP_CODE) == 400) {
        curl_close($ch);
        return false;
      }if(curl_getinfo($ch, CURLINFO_HTTP_CODE) == 500) {
        curl_close($ch);
        return false;
      }else{
        curl_close($ch);
        return true;
      }
      */

    }
}
