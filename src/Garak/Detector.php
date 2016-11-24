<?php

namespace Garak\Garak;

class Detector
{
    private static $WootheeInstance;

    /**
     * Singleton.
     *
     * @return [type] [description]
     */
    private static function getWootheeInstance()
    {
        if (!self::$WootheeInstance) {
            self::$WootheeInstance = new \Woothee\Classifier();
        }

        return self::$WootheeInstance;
    }

    /**
     * isSmartphone if the UA seemed to be garaK, return true;.
     *
     * @return bool [description]
     */
    public static function isGarak()
    {
        if (self::detectCategory() === 'mobilephone') {
            return true;
        }

        return false;
    }

    /**
     * isSmartphone if the UA seemed to be smartphone, return true;.
     *
     * @return bool [description]
     */
    public static function isSmartphone()
    {
        if (self::detectCategory() === 'smartphone') {
            return true;
        }

        return false;
    }

    /**
     * characterCode if the UA seemed to be garaK,
     *                  the characterCode should be Shift_JIS.
     *
     * @return [type] [description]
     */
    public static function characterCode()
    {
        if (self::isGarak()) {
            return 'Shift_JIS';
        }

        return 'UTF-8';
    }

    /**
     * Determine a carrier with Woothee.
     *
     * @return string
     */
    private static function detectCategory()
    {
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
