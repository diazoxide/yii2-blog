<?php
namespace diazoxide\blog\components;
use diazoxide\blog\Module;
use ReflectionMethod;
use Yii;
use yii\base\Component;
use yii\web\View;

class ViewPatternHelper extends Component
{

	public static function extract($pattern,$dependencies ){

		return preg_replace_callback( '/\[(?>(?>(?<class>.*?)\:(?>(?<method>\w+)))|(?<view>(?>[\w+@\/\-\_])+)|(?>\$(?<var>(?>[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(?>(?>\-\>[\w\s]+)+)?)))(?>\s*)(?<config>[\{\[].*[\}\]])?\]/', function ( $matches ) use ( $dependencies ) {

			$view = isset( $matches['view'] ) ? $matches['view'] : null;

			$var = isset( $matches['var'] ) ? $matches['var'] : null;

			$config = isset( $matches['config'] ) ? $matches['config'] : null;

			if ( $config ) {
				$config = json_decode( $config, true );
			} else {
				$config = [];
			}


			if ( $view ) {
				$result = Yii::$app->view->render( $view, $config );
				return $result === false ? $matches[0] : $result;
			} elseif($var){
				$path = explode('->',$var);
				$elem = null;
				foreach($path as $key => $prop){
					if($key == 0){
						$elem = $dependencies[$prop];
					} else{
						if(is_array($elem)){
							$elem = $elem[$prop];
						} elseif(is_object($elem)){
							$elem = $elem->{$prop};
						} else {
							$elem = Module::t('','Wrong Variable.');
						}
					}
				}
				return $elem;
			}

			$class = isset( $matches['class'] ) ? $matches['class'] : null;
			if ( $class ) {

				$method = isset( $matches['method'] ) ? $matches['method'] : null;

				$MethodChecker = new ReflectionMethod( $class, $method );
				if ( $MethodChecker->isStatic() ) {
					return $MethodChecker->invokeArgs( new $class, $config );
					/*return call_user_func($class . '::' . $method, $config);*/
				} else {
					$obj = new $class( $config );

					return $obj->{$method};
				}
			}

		}, $pattern );

	}
	
}