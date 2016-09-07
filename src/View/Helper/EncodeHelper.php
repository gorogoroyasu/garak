<?php
namespace Garak\View\Helper;

use Cake\View\Helper;
use Cake\View\View;
use Cake\Core\Configure;
use Cake\ORM\Entity;
use Cake\Event\Event;

/**
 * Escape helper
 * To convert special characters of variables to HTML entities
 * that is passeed to View object
 */
class EncodeHelper extends Helper
{

    private $charset = "UTF-8";

    public function initialize(array $array)
    {
        $this->charset = Configure::read('Garak.charset');
    }

    public function afterLayout(Event $Event, $layoutFile)
    {
        if ($this->charset !== "UTF-8") {
            $content = $Event->subject->Blocks->get('content');
            $encoded = mb_convert_encoding($content, "shift_jis", "utf-8");
            $Event->subject->Blocks->set('content', $encoded);
        }
    }
}
