<?php
class OAuthAccessToken extends Eloquent {

    protected $table        = 'oauth_access_tokens';
    protected $primaryKey   = 'token_id';
    protected $guarded      = array('token_id');
    protected $softDelete   = true;

    public static function get($token)
    {
        return OAuthAccessToken::where('access_token','=',$token)->first();
    }

}
