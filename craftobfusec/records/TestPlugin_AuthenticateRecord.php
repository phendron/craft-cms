<?php

namespace Craft;


class TestPlugin_AuthenticateRecord extends BaseRecord{


public function getTableName(){
    return 'testplugin_authenticate';
}


protected function defineAttributes(){

return array(
    'user_id' => AttributeType::Number,
    'uuid' => AttributeType::String,
    );
}

public defineIndexes(){
return array(array('columns' => array('name', 'uuid'), 'unique' => true),);
}


}



?>
