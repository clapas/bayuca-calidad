<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
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

    public function behaviors()
    {
        return [[
            'class' => TimestampBehavior::className(),
            'createdAtAttribute' => 'valid_until',
            'updatedAtAttribute' => false,
            'value' => date('Y-m-d H:i:s', mktime() + 3600),
        ]];   
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            [['code'], 'unique'],
            [['valid_until'], 'safe'],
            [['code'], 'string', 'max' => 4],
        ];
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
