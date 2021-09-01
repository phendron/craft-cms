<?php

namespace phendron\craftobfusec\services;

use Craft;
use craft\db\Table;
use craft\db\Query;
use yii\base\Component;
use yii\db\Exception;
use yii\db\Expression;

class SettingsQuery extends Component{


protected $table = "{{%craft-obfusec_settings}}";



public function getSettings(){


$query = new Query();
$query->select(['id','secure_route','global_default_route','unique_urls_active'])->from($this->table)->where(['id'=>'1']);
$cmd = $query->createcommand();
$record = $cmd->queryAll();
$row = $record[0];
return $row;


}


public function updateSettings($params){

Craft::$app->getDb()->createCommand()->update($this->table, $params, 'id=1', array(), false)->execute();
}
}
