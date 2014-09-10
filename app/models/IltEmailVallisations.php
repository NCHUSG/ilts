<?php
class IltEmailVallisations extends Eloquent {

    protected $table        = 'ilt_email_vallidations';
    protected $primaryKey   = 'id';
    protected $guarded      = array('id');
    protected $softDelete   = true;

    public function identity()
    {
        return $this->hasOne('IltIdentity','i_id','i_id');
    }

}
