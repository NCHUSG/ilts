<?php
class IltGroup extends Eloquent {

    protected $table        = 'ilt_groups';
    protected $primaryKey   = 'g_id';
    protected $guarded      = array('g_id');

    private $g_status_delimiter = ',';

    private $g_status_admin_value = 'admin';
    private $g_status_dev_value = 'dev';
    private $g_status_public_value = 'public';

    public function users()
    {
        return $this->belongsToMany('IltUser', 'IltIdentity');
    }

    public static function get($code,$throwNotFoundException = false)
    {
        $g_key = 'group.' . $code;
        if (isset($GLOBALS[$g_key]))
            return $GLOBALS[$g_key];
        $group = IltGroup::where('g_code','=',$code);
        if ($group->count()) {
            $group = $group->first();
            $GLOBALS[$g_key] = $group;
            return $group;
        }

        if ($throwNotFoundException)
            throw new Exception("Group not found!");
        else
            return false;
    }

    public function getOption($key,$default = "")
    {
        $opt = $this->hasMany('IltGroupOptions','g_id')->where('g_o_key','=',$key);
        if ($opt->count())
            return $opt->first()->g_o_value;
        else
            return $default;
    }

    public function getBoolOption($key,$default = false)
    {
        $opt = $this->hasMany('IltGroupOptions','g_id')->where('g_o_key','=',$key);
        if ($opt->count())
            return filter_var($opt->first()->g_o_value, FILTER_VALIDATE_BOOLEAN);
        else
            return Config::get('default.group_options.' . $key,$default);
    }

    public function options( $options = array() )
    {
        $opt = $this->hasMany('IltGroupOptions','g_id')->get()->all();
        $toReturn = array();

        foreach ($opt as $o) {
            $key = $o->g_o_key;
            if (isset($options[$key])) {
                if ($options[$key]) {
                    $o->g_o_value = $options[$key];
                    $o->save();
                    $toReturn[$key] = $options[$key];
                }
                else
                    $o->delete();
                unset($options[$key]);
            }
            else
                $toReturn[$key] = $o->g_o_value;
        }

        foreach ($options as $key => $value) {
            $newOpt = new IltGroupOptions();
            $newOpt->g_id = $this->getKey();
            $newOpt->g_o_key = $key;
            $newOpt->g_o_value = $value;
            $newOpt->save();
            $toReturn[$key] = $value;
        }

        return $toReturn;
    }

    public function getParent()
    {
        return IltGroup::find($this->g_parent_id);
    }

    public function children()
    {
        return IltGroup::where('g_parent_id','=',$this->getKey());
    }

    public function createChild()
    {
        $g = new IltGroup;
        $g->g_parent_id = $this->getKey();
        $g->g_level_sort = $this->g_level_sort + 1;
        return $g;
    }

    public function isStatus($status)
    {
        return !!(strpos($this->g_status, $status));
    }

    public function setStatus($status)
    {
        if (!strpos($this->g_status, $status))
            $this->g_status = $this->g_status . $this->g_status_delimiter . $status;
        return $this;
    }

    public function unsetStatus($status)
    {
        if (strpos($this->g_status, $status)){
            $this->g_status = preg_replace($status, "", $this->g_status);
            $this->g_status = preg_replace($this->g_status_delimiter . $this->g_status_delimiter, $this->g_status_delimiter, $this->g_status);
        }
        return $this;
    }

    public function isAdmin()
    {
        return $this->isStatus(Config::get('sites.g_status_admin_value'));
    }

    public function setAdmin($value = true)
    {
        if ($value)
            return $this->setStatus(Config::get('sites.g_status_admin_value'));
        else
            return $this->unsetStatus(Config::get('sites.g_status_admin_value'));
    }

    public function isDev()
    {
        return $this->isStatus(Config::get('sites.g_status_admin_value'));
    }

    public function setDev($value = true)
    {
        if ($value)
            return $this->setStatus(Config::get('sites.g_status_dev_value'));
        else
            return $this->unsetStatus(Config::get('sites.g_status_dev_value'));
    }

    public function setPublic($value = true)
    {
        if ($value)
            return $this->setStatus(Config::get('sites.g_status_public_value'));
        else
            return $this->unsetStatus(Config::get('sites.g_status_public_value'));
    }

    public function recruit($user,$isAdmin = false)
    {
        if ($isAdmin)
            return IltIdentity::admin($user,$this);
        else
            return IltIdentity::member($user,$this);
    }

    public function setAdministor($user)
    {
        $id = IltIdentity::get($user,$this);
        $id->i_authority = Config::get('sites.i_authority_admin_value');
        return $id->save();
    }

    public function isAdministor($user)
    {
        $id = IltIdentity::get($user,$this);
        return $id->i_authority == Config::get('sites.i_authority_admin_value');
    }

}
