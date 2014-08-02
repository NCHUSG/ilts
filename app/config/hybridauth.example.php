<?php
return array(
    "base_url"   => "http://localhost/",
    "providers"  => array (
        "Google"     => array (
            "enabled"    => true,
            "keys"       => array ( "id" => "ID", "secret" => "SECRET" ),
            ),
        "Facebook"   => array (
            "enabled"    => false,
            "keys"       => array ( "id" => "ID", "secret" => "SECRET" ),
            ),
        "Twitter"    => array (
            "enabled"    => false,
            "keys"       => array ( "key" => "ID", "secret" => "SECRET" )
            )
    ),
);
