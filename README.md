# garak plugin for CakePHP3
Recentry, the amount of feature phones are decreasing, though they are still existing.
Sometimes, we have to handle them, but there are some obstacles.
First, we can't use cookie to keep session.
Furthermore, the character code is not UTF-8 but SJIS.

This plugin will deal with the problems mentioned above.

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require gorogoroyasu/garak
```

## usage
just add few lines.
```
public class appController extends Controller
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Garak.Garak');
    }
}

public function redirect($url, $status = null, $exit = true){
    $url = $this->Garak->generateRedirectUrl($url);
    parent::redirect($url, $status, $exit);
}

```
## recomendation
I recommend you to user Themed (http://book.cakephp.org/3.0/ja/views/themes.html) to switch the view.
You can identify if the type of device by using isGarak method in Garak.Garak.
