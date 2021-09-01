<?php

namespace phendron\craftobfusec\migrations;

use Craft;
use craft\config\DbConfig;
use craft\db\Migration;

/**
 * m210829_191013_migrate migration.
 */
class Install extends Migration
{



    // plugin handle
    protected $pluginhandle = "craft-obfusec";

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // Place migration code here...
        $created=$this->createTables();
        if($created){
        $this->createIndexes();
        $this->addForeignKeys();

        Craft::$app->db->schema->refresh();
        $this->insertDefaultData();
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->removeTables();
        echo "install (safedown).\n";
        return true;
    }



    protected function createTables(){

    $tablesCreated = false;

    $tableSchema = Craft::$app->db->schema->getTableSchema('{{%'.$this->pluginhandle.'_settings}}');
    if($tableSchema === null){
    $tablesCreated = true;
    $this->createTable(
        '{{%'.$this->pluginhandle.'_settings}}',
        [
            'id' => $this->primaryKey(),
            'secure_route' => $this->string()->null()->defaultValue('secure_login'),
            'global_default_route' => $this->string()->null()->defaultValue('default'),
            'unique_urls_active' => $this->boolean()->null()->defaultValue(false)
        ]
        );

    }



    $tableSchema = Craft::$app->db->schema->getTableSchema('{{%'.$this->pluginhandle.'_authenticate}}');
    if($tableSchema === null){
    $tablesCreated = true;
    $this->createTable(
        '{{%'.$this->pluginhandle.'_authenticate}}',
        [
            'id' => $this->primaryKey(),
            'uid' => $this->char(36)->null()->defaultValue(null),
            'uuid' => $this->char(36)->null()->defaultValue(null)
        ]
        );

    }

    return $tablesCreated;
    }



    protected function createIndexes(){


    $this->createIndex(
        $this->db->getIndexName(
            '{{%'.$this->pluginhandle.'_authenticate}}',
            'uid',
             false
            ),
            '{{%'.$this->pluginhandle.'_authenticate}}',
            'uid',
             false
            );

    }

    protected function addForeignKeys(){


    $this->addForeignKey(
    $this->db->getForeignKeyName('{{%'.$this->pluginhandle.'_authenticate}}', 'uid'),
    '{{%'.$this->pluginhandle.'_authenticate}}',
    'uid',
    '{{%users}}',
    'uid',
    'SET NULL',
    'SET NULL'
    );

    }

    protected function insertDefaultData(){

    $cmd = $this->db->createCommand()->insert('{{%'.$this->pluginhandle.'_settings}}',array('secure_route'=>'secure_route','global_default_route'=>'default','unique_urls_active'=>'false'), false);
    $cmd->execute();
    }

    protected function removeTables(){
        $this->dropTableIfExists('{{%'.$this->pluginhandle.'_authenticate}}');
        $this->dropTableIfExists('{{%'.$this->pluginhandle.'_settings}}');
    }
}
