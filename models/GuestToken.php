<?php

namespace app\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "guest_token".
 *
 * @property string $code
 * @property string $valid_until
 */
class GuestToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'guest_token';
    }

    public function getPrimaryKey ($asArray = false)
    {
        if ($asArray) return ['code'];
        else return 'code';
    }

    public function init() {
        parent::init();
        static::deleteAll(['<', 'valid_until', date('Y-m-d H:i:s')]);
        do {
            $this->code = Yii::$app->getSecurity()->generateRandomString(4);
            $this->valid_until = date('Y-m-d H:i:s', mktime() + 3600);
        } while (!$this->validate(['code']));
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Code',
            'valid_until' => 'Valid Until',
        ];
    }
}
