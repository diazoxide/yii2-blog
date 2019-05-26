<?php
/*
 * Build page from pattern
 * */
namespace diazoxide\blog\components;

use diazoxide\blog\Module;
use ReflectionMethod;
use Yii;
use yii\base\Component;

class ViewPatternHelper extends Component
{
    public static $classPattern = '(?>(?<class>.*?)\:\:(?>(?<method>\w+)))';
    public static $viewPattern = '(?<view>(?>[\w+@\/\-\_])+)';
    public static $varPattern = '(?>\$(?<var>(?>[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(?>(?>\-\>[\w\s]+)+)?))';
    public static $configPattern = '(?>\`(?<config>.*)\`)';
    public static $codePattern = '(?>\`\`\`(?<code>.*)\`\`\`)';

    /**
     * Building final pattern
     * @return string
     */
    public static function pattern(){

        $class = self::$classPattern;
        $view = self::$viewPattern;
        $var = self::$varPattern;
        $config = self::$configPattern;
        $code = self::$codePattern;

        /*
         * Constructing instances pattern
         * */
        $instance = "(?>$class|$view|$var)";

        /*
         * Building final pattern
         * */
        $pattern = "/\[(?>$code|(?>$instance(?>\s*)$config?))\]/";
        return $pattern;
    }

    private static function initConfig($config, $dependencies)
    {
        foreach ($config as $key => $option) {
            if (is_string($option)) {
                preg_match('/^(?>\$(?<var>(?>[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(?>(?>\-\>[\w\s]+)+)?))$/',
                    $option, $option_matches);
                if (isset($option_matches['var'])) {
                    $config[$key] = $dependencies[$option_matches['var']];
                }
            } elseif (is_array($option)) {
                $config[$key] = self::initConfig($option, $dependencies);
            }
        }

        return $config;
    }

    public static function extract($pattern, $dependencies)
    {
        return preg_replace_callback(self::pattern(),
            function ($matches) use ($dependencies) {

                $code = isset($matches['code']) ? $matches['code'] : null;

                if($code){
                    return eval($code);
                }

                $view = isset($matches['view']) ? $matches['view'] : null;

                $var = isset($matches['var']) ? $matches['var'] : null;

                $config = isset($matches['config']) ? $matches['config'] : null;


                if ($config) {
                    $config = self::initConfig(json_decode($config, true), $dependencies);
                } else {
                    $config = [];
                }

                if ($view) {
                    $result = Yii::$app->view->render($view, $config);

                    return $result === false ? $matches[0] : $result;
                } elseif ($var) {
                    $path = explode('->', $var);
                    $elem = null;
                    foreach ($path as $key => $prop) {
                        if ($key == 0) {
                            $elem = $dependencies[$prop];
                        } else {
                            if (is_array($elem)) {
                                $elem = $elem[$prop];
                            } elseif (is_object($elem)) {
                                $elem = $elem->{$prop};
                            } else {
                                $elem = Module::t('', 'Wrong Variable.');
                            }
                        }
                    }

                    return $elem;
                }

                $class = isset($matches['class']) ? $matches['class'] : null;
                if ($class) {

                    $method = isset($matches['method']) ? $matches['method'] : null;

                    $MethodChecker = new ReflectionMethod($class, $method);
                    if ($MethodChecker->isStatic()) {
                        return $MethodChecker->invokeArgs(new $class, $config);
                        /*return call_user_func($class . '::' . $method, $config);*/
                    } else {
                        $obj = new $class($config);

                        return $obj->{$method};
                    }
                }

                /*
                 * Return Full match as string
                 * */
                return $matches[0];

            }, $pattern);

    }

}