<?php
use Cake\Core\Configure;
use Garak\Garak\Detector;
use Cake\Utility\Hash;


if (Detector::isGarak()) {
    if (!Configure::read('Garak.Session')) {
        Configure::write('Garak.Session', Configure::read('Session'));
    }
    if (Configure::read('Garak.Session.ini')) {
        Configure::write('Garak.Session.ini',
                            Hash::merge(Configure::read('Garak.Session.ini'),
                                    array('session.use_cookies' => 0,
                                            'session.use_only_cookies' => 0,
                                            'session.name' => Configure::read('Session.cookie'),
                                            'url_rewriter.tags' => 'a=href,area=href,frame=src,input=src,form=fakeentry,fieldset=',
                                            'session.use_trans_sid' => 1,
                                    )));
        Configure::write('Session', Configure::read('Garak.Session'));
    } else {
        Configure::write('Garak.Session.ini',
                                    ['session.use_cookies' => 0,
                                            'session.use_only_cookies' => 0,
                                            'session.name' => Configure::read('Session.cookie'),
                                            'url_rewriter.tags' => 'a=href,area=href,frame=src,input=src,form=fakeentry,fieldset=',
                                            'session.use_trans_sid' => 1,
                                    ]);
        Configure::write('Session', Configure::read('Garak.Session'));
    }
}
