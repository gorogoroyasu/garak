<?php

namespace Garak\View\Helper;

use Cake\View\Helper;
use Cake\View\View;
use Cake\Event\Event;
use Garak\Garak\Detector;

/**
 * Escape helper
 * To convert special characters of variables to HTML entities
 * that is passeed to View object.
 */
class EncodeHelper extends Helper
{
    /**
     * afterLayout convert utf-8 to shift_jis.
     *
     * @param Event  $event      [description]
     *
     * @return [type] [description]
     */
    public function afterLayout(Event $event, $layoutFile)
    {
        if (Detector::characterCode() !== 'UTF-8') {
            $content = $event->getSubject()->Blocks->get('content');
            $encoded = mb_convert_kana($content, "ka", "UTF-8");
            $encoded = mb_convert_encoding($encoded, 'shift_jis', 'utf-8');
            $event->getSubject()->Blocks->set('content', $encoded);
        }
    }
}
