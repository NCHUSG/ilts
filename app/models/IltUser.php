<?php

class IltUser extends Eloquent {

    protected $table        = 'ilt_users';
    protected $primaryKey   = 'u_id';
    protected $guarded      = array('u_id');

    public static function get($throwNotFoundException = false)
    {
        if (isset($GLOBALS['user']))
            return $GLOBALS['user'];
        if (Session::has('user_being.u_id')){
            $user = IltUser::findOrFail(Session::get('user_being.u_id'));
            $GLOBALS['user'] = $user;
            return $user;
        }

        if ($throwNotFoundException)
            throw new Exception("User not found!");
        else
            return false;
    }

    public static function logout()
    {
        return Session::forget('user_being');
    }

    public function isAdmin()
    {
        return !!($this->groups()->where('g_status','like','%' . Config::get('sites.g_status_admin_value') . '%')->count());
    }

    public function isDev()
    {
        return !!($this->groups()->where('g_status','like','%' . Config::get('sites.g_status_dev_value') . '%')->count());
    }

    public function providers()
    {
        return $this->hasMany('IltUserProvider','u_id');
    }

    public function option()
    {
        return $this->hasOne('IltUserOptions','u_id');
    }

    public function access_tokens()
    {
        return $this->hasMany('OAuthAccessToken','user_id');
    }

    public function identities()
    {
        return $this->hasMany('IltIdentity', 'u_id');
    }

    public function groups()
    {
        return $this->belongsToMany('IltGroup', 'ilt_identity_tags','u_id','g_id');
    }

    public function join($group,$isAdmin = false)
    {
        if ($isAdmin)
            return IltIdentity::admin($this,$group);
        else
            return IltIdentity::member($this,$group);
    }

    public function getByPage($page = 1, $limit = 10)
    {
      $results = StdClass;
      $results->page = $page;
      $results->limit = $limit;
      $results->totalItems = 0;
      $results->items = array();
     
      $users = $this->model->skip($limit * ($page - 1))->take($limit)->get();
     
      $results->totalItems = $this->model->count();
      $results->items = $users->all();
     
      return $results;
    }

}
