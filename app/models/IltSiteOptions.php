<?php
class IltSiteOptions extends Eloquent {

    protected $table        = 'ilt_site_options';
    protected $primaryKey   = 's_id';
    protected $guarded      = array('s_id');

    public $timestamps = false;

    public static function get($key,$default = "")
    {
        // Use Cache in the future...
        // Cache::get('key');
        $opt = IltSiteOptions::where('s_key', '=', $key);

        if ($opt->count())
            return $opt->first()->s_value;
        else
            return $default;
    }

    public static function getBool($key,$default = false)
    {
        // Use Cache in the future...
        // Cache::get('key');
        $opt = IltSiteOptions::where('s_key', '=', $key);

        if ($opt->count())
            return filter_var($opt->first()->s_value, FILTER_VALIDATE_BOOLEAN);
        else
            return $default;
    }

    public static function put($key,$value)
    {
        // Use Cache in the future...
        // Cache::forget('key');
        // Cache::put('key', 'value', $minutes);
        $opt = IltSiteOptions::where('s_key', '=', $key);

        if (!$opt->count())
            $opt = new IltSiteOptions;
        else
            $opt = $opt->first();

        if (!$value) {
            return $opt->delete();
        }

        $opt->s_key = $key;
        $opt->s_value = $value;

        return $opt->save();
    }

    public static function getAll()
    {
        $opts =  IltSiteOptions::all()->all();
        $result = array();
        foreach ($opts as $key => $opt) {
            $result[$opt->s_key]=$opt->s_value;
        }
        return $result;
    }

    public function getParent()
    {
        return IltSiteOptions::where('s_key', '=', $this->parent_s_id)->first();
    }

    public function getChildren($key)
    {
        return IltSiteOptions::where('parent_s_id', '=', $this->getKey())->where('s_key', '=', $key)->first();
    }

}
