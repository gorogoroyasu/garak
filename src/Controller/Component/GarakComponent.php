<?php

namespace Garak\Controller\Component;

use Garak\Garak\Detector;
use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Event\Event;

class GarakComponent extends Component
{
    private $category = 'pc';

    public function initialize(array $array)
    {
        // TODO: Image 系の何か。何をやってるかわからないのでいつか実装する。
        // $this->emoji->setImageUrl(Router::url('/') . 'yak/img/');
        $this->request->session()->start();
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
    *  NOTE: http://takagi-hiromitsu.jp/diary/20100520.html#p01(2010年5月現在で、Docomo以外はCookie に対応してるっぽい。が、ガラケーは基本これで。)
    * generateRedirectUrl
    *
    * @param $url
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
                $url .= sprintf("%s=%s", session_name(), urlencode(session_id()));
            }
            return $url;
        }
        return $url;
    }

    /**
     * TODO: beforeRender で何かやってるので調べる。
     *
     */
     public function beforeRender(Event $Event) {
         if (Detector::isGarak()) {
             $this->response->type('xhtml');
             $this->response->charset('Shift_JIS');
         } else {
             $this->response->type('html');
             $this->response->charset('UTF-8');
         }
     }
 }
