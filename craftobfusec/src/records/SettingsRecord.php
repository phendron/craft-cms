<?php

namespace phendron\craftobfusec\records;

use craft\records\User;
use craft\records\Element;
use Craft;
use craft\db\ActiveRecord;
use yii\db\ActiveQueryInterface;

class SettingsRecord extends ActiveRecord{


public static function tableName()
{
    return '{{%craft-obfusec_settings}}';
}



}



?>
