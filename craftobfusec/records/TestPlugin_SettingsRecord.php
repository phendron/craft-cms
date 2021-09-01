<?php

namespace Craft;


class TestPlugin_SettingsRecord extends BaseRecord{


public function getTableName(){
    return 'testplugin_settings';
}


protected function defineAttributes(){

return array(
    'secure_route' => AttributeType::String,
    'global_default_path' => AttributeType::String,
    'unique_user_urls' => AttributeType::Bool,
    );
}



}



?>
