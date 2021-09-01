<?php

namespace phendron\craftobfusec\controllers;

use yii\web\Response;
use Craft;
use craft\web\view;
use craft\web\Controller;
use craft\helpers\UrlHelper;
use phendron\craftobfusec\CraftobfusecBundle;
use phendron\craftobfusec\CraftObfusec;

class AdministratorController extends Controller {

//protected $allowAnonymous = ['missing','login'];
protected $allowAnonymous = true;

private function getUserPath(){
$url = Craft::$app->request->absoluteUrl;
$parsed_url = parse_url($url, PHP_URL_PATH);
$parsed_url_arr = explode("/", $parsed_url);
$secure_route = $parsed_url_arr[2];
$user_route = $parsed_url_arr[3];

return $user_route;
}

private function verifyUserPath(){

}


public function actionLogin(){
$user_route = $this->getUserPath();

if(!CraftObfusec::getInstance()->pathbrowserquery->IfRouteExists($user_route)){
//return $this->renderTemplate("craft-obfusec/missing.twig", []);
Craft::$app->getResponse()->redirect(UrlHelper::cpUrl('/'))->send();
} else {
return $this->renderTemplate("craft-obfusec/login.twig", []);
}

}

public function actionMissing(){
//return $this->renderTemplate('craft-obfusec/missing.twig', []);
Craft::$app->getResponse()->redirect(UrlHelper::cpUrl('/'))->send();
}


public function actionAuthenticate(){
$request = array("success"=>false);
$csrf_token = Craft::$app->request->getBodyParam("CRAFT_CSRF_TOKEN");
if($csrf_token){
$username = Craft::$app->request->getBodyParam("username");
$password = Craft::$app->request->getBodyParam("password");
$rememberMe=1;
$userSession = Craft::$app->getUser();
$user = Craft::$app->getUsers()->getUserByUsernameOrEmail($username);

if($user){
if($user->authenticate($password)){

$generalConfig = Craft::$app->getConfig()->getGeneral();
if($rememberMe && $generalConfig->rememberedUserSessionDuration !== 0){
$duration = $generalConfig->rememberedUserSessionDuration;
} else {
$duration = $generalConfig->userSessionDuration;
}

if($userSession->login($user, $duration)){
$request["success"]=true;
$request["redirect"]=UrlHelper::cpUrl();
}
}
}
}

echo json_encode($request);
exit(0);
}



}

?>
