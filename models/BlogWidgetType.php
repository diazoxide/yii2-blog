<?php

namespace diazoxide\blog\models;

use diazoxide\blog\Module;
use diazoxide\blog\traits\ModuleTrait;
use diazoxide\blog\widgets\Feed;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

/**
 *
 * @property integer $id
 * @property string $config
 * @property string $title
 * @property string $widget
 *
 */
class BlogWidgetType extends ActiveRecord {
	use ModuleTrait;

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return '{{%blog_widget_type}}';
	}


	public function getBreadcrumbs() {
		$result   = $this->getModule()->breadcrumbs;
		$result[] = [ 'label' => Module::t( '', 'Widget Types' ), 'url' => [ 'index' ] ];

		return $result;
	}

	/**
	 * Before Save change $config variable
	 * From array to json string
	 *
	 * @param bool $insert
	 *
	 * @return bool
	 */
	public function beforeSave( $insert ) {
		$this->config = Json::encode( $this->config );

		return parent::beforeSave( $insert );
	}


	/**
	 * After object initialize change
	 * $config variable to decoded array
	 */
	public function afterFind() {
		$this->config = Json::decode( $this->config );
		parent::afterFind();
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[ [ 'title' ], 'required' ],
			[ [ 'title' ], 'string', 'max' => 191 ],
			[ [ 'config' ], 'safe' ],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id'     => Module::t( '', 'ID' ),
			'config' => Module::t( '', 'Config' ),
			'title'  => Module::t( '', 'Title' ),
		];
	}

	/**
	 * @param $type_id
	 * @param array $config
	 *
	 * @return string
	 * @throws NotFoundHttpException
	 */
	public static function widget( $type_id, $config = [] ) {
		$type = self::findOne( $type_id );

		if ( $type == null ) {
			throw new NotFoundHttpException( 'Widget Type not found' );
		}

		$config = array_merge(  $type->config, $config);

		return Feed::widget(  $config  );

	}

}
