<?php
class IltIdentity extends Eloquent {

    protected $table        = 'ilt_identity_tags';
    protected $primaryKey   = 'i_id';
    protected $guarded      = array('i_id');

    protected $fillable = array('u_id', 'g_id');

    public static function get($user,$group,$throwNotFoundException = false)
    {
        $id = IltIdentity::where('u_id','=',$user->getKey())->where('g_id','=',$group->getKey());
        if ($id->count())
            return $id->first();

        if ($throwNotFoundException)
            throw new Exception("Relationship not found!");
        else
            return false;
    }

    public static function status($user,$group,$throwNotFoundException = false)
    {
        $id = IltIdentity::get($user,$group);
        if ($id)
            return $id->i_status;
        
        if ($throwNotFoundException)
            throw new Exception("Relationship not found!");
        else
            return false;
    }

    public static function authority($user,$group,$throwNotFoundException = false)
    {
        $id = IltIdentity::get($user,$group);
        if ($id)
            return $id->i_authority;

        if ($throwNotFoundException)
            throw new Exception("Relationship not found!");
        else
            return false;
    }

    public static function isAuth($user,$group,$auth)
    {
        $id = IltIdentity::get($user,$group);
        if ($id){
            return $id->i_authority == $auth;
        }
        else
            return false;
    }

    public static function isAdmin($user,$group)
    {
        return IltIdentity::isAuth($user,$group,Config::get('sites.i_authority_admin_value'));
    }

    public static function isMember($user,$group)
    {
        return IltIdentity::isAuth($user,$group,Config::get('sites.i_authority_member_value'));
    }

    public static function isPending($user,$group)
    {
        return IltIdentity::isAuth($user,$group,Config::get('sites.i_authority_pending_value'));
    }

    public static function isGuest($user,$group)
    {
        return !IltIdentity::get($user,$group);
    }

    public function is_auth($auth)
    {
        return $this->i_authority == $auth;
    }

    public function is_admin()
    {
        return $this->is_auth(Config::get('sites.i_authority_admin_value'));
    }

    public function is_member()
    {
        return $this->is_auth(Config::get('sites.i_authority_member_value'));
    }

    public function is_pending()
    {
        return $this->is_auth(Config::get('sites.i_authority_pending_value'));
    }

    public static function authOrHigher($user,$group,$auth)
    {
        $id = IltIdentity::get($user,$group);
        if ($id){
            return $id->i_authority >= $auth;
        }
        else
            return false;
    }

    public static function adminOrHigher($user,$group)
    {
        return IltIdentity::authOrHigher($user,$group,Config::get('sites.i_authority_admin_value'));
    }

    public static function memberOrHigher($user,$group)
    {
        return IltIdentity::authOrHigher($user,$group,Config::get('sites.i_authority_member_value'));
    }

    public static function pendingOrHigher($user,$group)
    {
        //return IltIdentity::authOrHigher($user,$group,Config::get('sites.i_authority_pending_value'));
        return !!IltIdentity::get($user,$group);
    }

    public function auth_or_higher($auth)
    {
        return $this->i_authority >= $auth;
    }

    public function admin_or_higher()
    {
        return $this->auth_or_higher(Config::get('sites.i_authority_admin_value'));
    }

    public function member_or_higher()
    {
        return $this->auth_or_higher(Config::get('sites.i_authority_member_value'));
    }

    public function pending_or_higher()
    {
        //return $this->auth_or_higher(Config::get('sites.i_authority_admin_value'));
        return true;
    }

    public static function establish($user,$group,$authority = false)
    {
        if($group->g_level_sort != 0)
            if(IltIdentity::get($user,$group->getParent(),true)->i_authority < Config::get('sites.i_authority_member_value'))
                throw new Exception("您尚未加入上層組織！");
            
        $id = IltIdentity::firstOrCreate(array('g_id' => $group->getKey(), 'u_id' => $user->getKey()));

        if ($authority)
            $id->i_authority = $authority;
        else
            $id->i_authority = Config::get('sites.i_authority_member_value');

        $id->save();
        return $id;
    }

    public static function admin($user,$group)
    {
        return IltIdentity::establish($user,$group,Config::get('sites.i_authority_admin_value'));
    }

    public static function member($user,$group)
    {
        return IltIdentity::establish($user,$group,Config::get('sites.i_authority_member_value'));
    }

    public static function pending($user,$group)
    {
        return IltIdentity::establish($user,$group,Config::get('sites.i_authority_pending_value'));
    }

    public function setAdmin()
    {
        $this->i_authority = Config::get('sites.i_authority_admin_value');
        return $this->save();
    }

    public function setMember()
    {
        $this->i_authority = Config::get('sites.i_authority_member_value');
        return $this->save();
    }

    public function setPending()
    {
        $this->i_authority = Config::get('sites.i_authority_pending_value');
        return $this->save();
    }

    public function user()
    {
        return $this->hasOne('IltUser','u_id','u_id');
    }

    public function group()
    {
        return $this->hasOne('IltGroup','g_id','g_id');
    }

    public function email_validation()
    {
        return $this->hasOne('IltEmailVallisations','i_id');
    }

    public function is_last_admin()
    {
        if($this->i_authority == Config::get('sites.i_authority_admin_value'))
            return ($this->group()->first()->has_only_one_admin());
        else
            return false;
    }

}
