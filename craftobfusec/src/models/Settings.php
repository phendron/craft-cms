<?php


namespace phendron\craftobfusec\models;

use craft\base\model;


class Settings extends Model {

public $default_admin_url_route = 'secure_login';
public $global_default_path = "default";
public $unique_user_urls = "false";

public function rules(){
    return [[['default_admin_url_route', 'global_default_path', 'unique_user_urls'], 'required'],];
}


public function getSettingsResponse(){
return Craft::$app->controller->renderTemplate("craft-obfusec/settingsx.twig");
}

}
