<?php

namespace App\Factory;

use App\Entity\Secret;

class SecretFactory
{
    public static function createSecret($secret, $expireAfterViews, $expireAfter): Secret
    {
        $secretEntity = new Secret($expireAfter);
		$secretEntity->setSecretText($secret);
        $secretEntity->setRemainingViews($expireAfterViews);

        return $secretEntity;
    }
}
