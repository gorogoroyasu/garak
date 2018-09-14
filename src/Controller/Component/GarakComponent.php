<?php

namespace Garak\Controller\Component;

use Garak\Garak\Detector;
use Cake\Controller\Component;
use Cake\Event\Event;

class GarakComponent extends Component
{
    public function initialize(array $array)
    {
        $this->getController()->getRequest()->getSession()->start();
    }

    public function startup($event)
    {
        $event->getSubject()->helpers += [
            'Garak.Encode',
        ];
    }

    /**
     *  brought from github.com/k1LoW/Yak
     *  if the UA was GaraK, URL will hold the Session ID on its url.
     *  NOTE: http://takagi-hiromitsu.jp/diary/20100520.html#p01
     *  the article above says that we can use cookie except for Docomo's Garak,
     *  though because of the article is very old and for safety,
     *  every GaraK will hold its Session ID on their URL.
     *
     * generateRedirectUrl
     *
     * @param $url
     *
     * @return $url
     */
    public function generateRedirectUrl($url)
    {
        if (Detector::isGarak()) {
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
                $url .= sprintf('%s=%s', session_name(), urlencode(session_id()));
            }

            return $url;
        }

        return $url;
    }

    /**
     * beforeRender determine the response type and characterCode.
     *
     * @param Event $event [description]
     *
     * @return [type] [description]
     */
    public function beforeRender(Event $event)
    {
        if (Detector::isGarak()) {
            $this->getController()->response = $this->getController()->response
                ->withType('xhtml')
                ->withCharset('Shift_JIS');
        } else {
            $this->getController()->response = $this->getController()->response
                ->withType('html')
                ->withCharset('UTF-8');
        }
    }
}
