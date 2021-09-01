<?php

namespace phendron\craftobfusec\controllers;

use yii\web\Response;
use Craft;
use craft\web\view;
use craft\web\Controller;
use phendron\craftobfusec\CraftobfusecBundle;
use phendron\craftobfusec\CraftObfusec;

class ControlpanelController extends Controller {

protected $allowAnonymous = true;

public function actionInit(){
//return $this->renderTemplate("test-plugin/controlpanel/index.twig", ['settings'=>Craft::$app->getPlugins()->getPlugin('test-plugin')::getInstance()->SettingsQuery->getSettings()]);

$save_request=false;
$save_error=false;
if(Craft::$app->request->getBodyParam('action_request')){
$save_request=true;
$update_row = array(
    'secure_route'=>Craft::$app->request->getBodyParam('secure_route'),
    'global_default_route'=>Craft::$app->request->getBodyParam('global_default_route'),
    'unique_urls_active'=>Craft::$app->request->getBodyParam('unique_urls_active')
);


$updated = CraftObfusec::getInstance()->settingsquery->updateSettings($update_row);
if(!$updated){
$save_error=true;
}
}


$parameters = CraftObfusec::getInstance()->settingsquery->getSettings();
$parameters["authorized_users"] = CraftObfusec::getInstance()->pathbrowserquery->getAuthenticatedUsersCount();
$parameters["save_request"]=$save_request;
$parameters["save_error"]=$save_error;
$all_users = CraftObfusec::getInstance()->pathbrowserquery->GetAllUsers();
$parameters["unauthorized_users"] = 0;
if($all_users["success"]){
$parameters["unauthorized_users"] = count($all_users["users"]);
}
return $this->renderTemplate("craft-obfusec/controlpanel/index.twig", ['settings'=>$parameters]);
}


public function actionPathbrowser(){
$parameters = null;
$parameters = CraftObfusec::getInstance()->pathbrowserquery->getAuthenticatedUsers(10);
return $this->renderTemplate("craft-obfusec/controlpanel/path-browser.twig", ["data"=>$parameters]);
}


public function actionInitializeuser(){

if(Craft::$app->request->getBodyParam("CRAFT_CSRF_TOKEN")){
$user_id = Craft::$app->request->getBodyParam("user_id");
$initialized=CraftObfusec::getInstance()->pathbrowserquery->AuthenticateUserById($user_id);

if($initialized){
$data = array("success"=>true);
echo json_encode($data);
exit(0);
} else {
$data = array("success"=>false);
echo json_encode($data);
exit(0);
}
} else {
exit(0);
}
}



public function actionReinitializeuser(){

if(Craft::$app->request->getBodyParam("CRAFT_CSRF_TOKEN")){
$user_uid = Craft::$app->request->getBodyParam("user_uid");
$initialized=CraftObfusec::getInstance()->pathbrowserquery->ReAuthenticateUserByUuid($user_uid);

if($initialized["success"]){
echo json_encode($initialized);
exit(0);
} else {
$data = array("success"=>false);
echo json_encode($data);
exit(0);
}
} else {
exit(0);
}
}


public function actionUninitializedusers(){
$users=CraftObfusec::getInstance()->pathbrowserquery->getUnauthenticatedUserIds();
echo json_encode(array("users"=>$users,"CRAFT_CSRF_TOKEN"=>Craft::$app->request->getCsrfToken()));
//echo json_encode(["success"=>"true"]);
exit(0);
}


public function actionSearchauthenticatedusers(){

if(Craft::$app->request->getBodyParam("CRAFT_CSRF_TOKEN")){

$search_params = Craft::$app->request->getBodyParam("search_params");
$search_offset=Craft::$app->request->getBodyParam("search_page");
$search_limit=10;
$search_offset=$search_limit*$search_offset;

$results=CraftObfusec::getInstance()->pathbrowserquery->SearchAuthenticatedUsers($search_params, $search_offset, $search_limit);
if($results["success"]){
$results_count=count($results["users"]);
$results["user_range_from"]=1;
if($search_offset>0){
$results["user_range_from"]=$search_offset+1;
}
$results["user_range_to"]=$search_offset+$search_limit;
if($results_count < $search_limit){
$results["user_range_to"]=$results_count+$search_offset;
}
}
echo json_encode($results);
exit(0);
}

}



public function actionGetallusers(){

if(Craft::$app->request->getBodyParam("CRAFT_CSRF_TOKEN")){

$results=CraftObfusec::getInstance()->pathbrowserquery->GetAllUsers();
$results["CRAFT_CSRF_TOKEN"] = Craft::$app->request->getCsrfToken();
echo json_encode($results);
exit(0);
}
}

public function actionSaveSettings(){

}

}



?>
