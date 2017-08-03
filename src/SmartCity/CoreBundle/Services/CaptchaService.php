<?php
namespace SmartCity\CoreBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Monolog\Logger;

/**
 * Class CaptchaService
 * @package SmartCity\CoreBundle\Services
 */
class CaptchaService
{
    /**
     * @var session
     */
    protected $session;

    /**
     * @var string captcha
     */
    protected $captcha;


    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->session = new Session();
    }

    public function generate($name = 'jahadPlatformCaptcha')
    {
        $this->captcha = rand(10000,99999);
        $this->session->set($name, $this->captcha);
    }

    public function getCaptcha($width, $height, $name = 'jahadPlatformCaptcha')
    {
        $this->captcha = $this->session->get($name);
        $img = imagecreatetruecolor($width, $height);
        $backgroundColor = imagecolorallocate($img, 255, 255, 255);
        imagefill($img, 0, 0, $backgroundColor);

        $text_color = imagecolorallocate($img, 255-rand(75,255), 255-rand(75,255), 255-rand(75,255));
        $textLen = (14*5);
        $textImg = imagettftext($img, 16, rand(-5, 10), ($width-$textLen)/2, $height/2 + 8, $text_color, '../src/SmartCity/CoreBundle/Resources/public/font/ArabicsHarfi-Slant.ttf', $this->captcha);

        header("Content-type:image/jpeg");
        header("Content-Disposition:inline; filename=secure.jpg");
        imagejpeg($img);
    }

    public function verify($code, $name = 'jahadPlatformCaptcha')
    {
        $this->captcha = $this->session->get($name);
        if ($this->captcha == $code) {
            return true;
        }
        else {
            return false;
        }
    }
}
