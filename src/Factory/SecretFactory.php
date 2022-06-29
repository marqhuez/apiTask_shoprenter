<?php

namespace App\Factory;

use App\Entity\Secret;

class SecretFactory
{
    public static function createSecret($secret, $expireAfterViews, $expireAfter): Secret
    {
        $secretEntity = new Secret();
        $secretEntity->setSecret($secret);
        $secretEntity->setExpireAfterViews($expireAfterViews);
        $secretEntity->setExpireAfter($expireAfter);

        return $secretEntity;
    }
}
