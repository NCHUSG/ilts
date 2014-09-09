<?php
class IltUserProvider extends Eloquent {

    protected $table        = 'ilt_user_providers';
    protected $primaryKey   = 'u_p_id';
    protected $softDelete   = true;

    protected $fillable = array('u_p_identifier', 'u_p_type', 'u_p_email');

    public function user()
    {
        return $this->hasOne('IltUser','u_id','u_id');
    }

}
