<?php

namespace Garak\Garak;

use Woothee\Classifier;


class Detector
{
    private static $WootheeInstance;

    private static function getWootheeInstance()
    {
        if (!self::$WootheeInstance) self::$WootheeInstance = new \Woothee\Classifier;
        return self::$WootheeInstance;
    }

    public static function isGarak()
    {
        if (self::detectCategory() === 'mobilephone') {
            return true;
        }
        return false;
    }

    public static function characterCode()
    {
        if (self::isGarak()) {
            return "Shift_JIS";
        }
        return "UTF-8";
    }

    /**
    * Determine a carrier with Woothee
    *
    * @return string
    */
    private static function detectCategory() {
        $userAgent = env('HTTP_USER_AGENT');
        $classifier = self::getWootheeInstance();
        $r = $classifier->parse($userAgent);

        // mobilephone
        if ($r['category'] === 'mobilephone') {
            return 'mobilephone';
        }
        // smartphone
        if ($r['category'] === 'smartphone') {
            return 'smartphone';
        }
        // pc
        return 'pc';
    }
}
