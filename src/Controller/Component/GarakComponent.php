<?php

namespace Garak\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Routing\Router;
use Cake\Core\Configure;
use Cake\Event\Event;
use Woothee\Classifier;

class GarakComponent extends Component
{
    private $category = 'pc';

    public function initialize(array $array)
    {
        $this->category = self::getStaticInstance();
        if ($this->category === 'pc' || $this->category === 'iphone') {
            Configure::write('Garak.charset', 'UTF-8');
        } else {
            Configure::write('Garak.charset', 'shift_jis');
        }
    }

    public function startup($event)
    {
        $event->subject->helpers += [
            'Garak.Encode'
        ];
    }


    /**
    *  Yak から流用。
    *  たぶん、 $url['?'] にsession_name と session_id を持たせる処理
    * generateRedirectUrl
    *
    * @param $url
    * @return $url
    */
    public function generateRedirectUrl($url)
    {
        if ($this->category == 'docomo') {
            if (is_array($url)) {
                if (!isset($url['?'])) {
                    $url['?'] = array();
                }
                $url['?'][session_name()] = session_id();
            } else {
                if (strpos($url, '?') === false) {
                    $url .= '?';
                } else {
                    $url .= '&';
                }
                $url .= sprintf("%s=%s", session_name(), urlencode(session_id()));
            }
            return $url;
        }
        return $url;
    }



    public static function getStaticInstance($carrier = null) {
        static $instances = array();
        $aliases = array(
            'docomo'   => 'docomo',
            'i-mode'   => 'docomo',
            'imode'    => 'docomo',
            'au'       => 'au',
            'kddi'     => 'au',
            'ezweb'    => 'au',
            'aumail'   => 'aumail',
            'softbank' => 'softbank',
            'disney'   => 'softbank',
            'vodafone' => 'softbank',
            'iphone'   => 'iphone',
            'j-phone'  => 'jphone',
            'jphone'   => 'jphone',
            'willcom'  => 'docomo',
            'emobile'  => 'docomo',
        );

        if (isset($carrier) === false) {
            $carrier = self::detectCarrier();
        }
        $carrier = strtolower($carrier);
        $carrier = isset($aliases[$carrier]) ? $aliases[$carrier] : 'pc';
        return $carrier;
    }

    /**
    * Determine a carrier with Woothee
    *
    * @return string
    */
    private static function detectCarrier() {
        $userAgent = env('HTTP_USER_AGENT');
        $classifier = new \Woothee\Classifier;
        $r = $classifier->parse($userAgent);

        // smartphone
        if ($r['category'] === 'smartphone') {
            return 'iPhone';
        }

        // mobilephone
        if ($r['category'] === 'mobilephone') {
            if (preg_match('/^J-PHONE/', $userAgent)) {
                return 'J-PHONE';
            }
            switch ($r['os']) {
                case 'docomo':
                return 'docomo';
                case 'au':
                return 'au';
                case 'SoftBank':
                return 'SoftBank';
                case 'WILLCOM':
                return 'WILLCOM';
                case 'emobile':
                return 'EMOBILE';
            }
            return 'docomo';
        }

        // pc
        return 'PC';
    }

}
