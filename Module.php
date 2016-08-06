<?php

namespace MBtecZfEmailObfuscator;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;

/**
 * Class        Module
 * @package     MBtecZfEmailObfuscator
 * @author      Matthias Büsing <info@mb-tec.eu>
 * @copyright   2016 Matthias Büsing
 * @license     GNU General Public License
 * @link        http://mb-tec.eu
 */
class Module implements AutoloaderProviderInterface, ViewHelperProviderInterface
{
    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\ClassMapAutoloader' => [
                __DIR__ . '/autoload_classmap.php',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getViewHelperConfig()
    {
        return [
            'factories' => [
                'emailObfuscator' => View\Helper\EmailObfuscator::class,
            ],
        ];
    }
}
