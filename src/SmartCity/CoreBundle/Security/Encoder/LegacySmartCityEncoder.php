<?php

namespace SmartCity\CoreBundle\Security\Encoder;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class LegacySmartCityEncoder implements PasswordEncoderInterface {
    public function encodePassword($raw, $salt)
    {
        return sha1(mb_convert_encoding($raw, 'UTF-16LE'));
    }

    public function isPasswordValid($encoded, $raw, $salt)
    {
        return $encoded === $this->encodePassword($raw, $salt);
    }
};
