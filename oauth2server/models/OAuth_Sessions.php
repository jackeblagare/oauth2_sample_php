<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "api.oauth_sessions".
 *
 * @property string $owner_type
 * @property string $owner_id
 * @property string $client_id
 */
class OAuth_Sessions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api.oauth_sessions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['owner_type', 'owner_id', 'client_id'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'owner_type' => 'Owner Type',
            'owner_id' => 'Owner ID',
            'client_id' => 'Client ID',
        ];
    }
}
