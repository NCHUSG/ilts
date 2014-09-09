<?php
class IltGroupOptions extends Eloquent {

    protected $table        = 'ilt_group_options';
    protected $primaryKey   = 'g_o_id';
    protected $guarded      = array('g_id');

    public $timestamps = false;

    public function group()
    {
        return $this->hasOne('IltGroup','g_id','g_id');
    }

}
