<?php

namespace phendron\craftobfusec\services;

use Craft;
use craft\db\Table;
use craft\db\Query;
use yii\base\Component;
use yii\db\Exception;
use yii\db\Expression;

class PathBrowserQuery extends Component{


// defined:: Component table
protected $table = "{{%craft-obfusec_authenticate}}";


private function randword($len=4){
$chara = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$randstr="";
for($i=0; $i < $len; $i++){
$randstr .= $chara[rand(0, strlen($chara)-1)];
}
return $randstr;
}

// UUIDv5 validation function (ignoring inbuilt function in PHP & Craft CMS)
private function is_valid_uuid($uuid){
// Preg-match UUIDv5
return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?'.'[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
}

// UUIDv5 generator function
// arguments:
// $namespace == UUIDv5::String where is_valid_uuid($namespace) == True
// $name == any string
private function UUIDv5($namespace, $name){

if(!$this->is_valid_uuid($namespace)){return false;}

$hex = str_replace(array('-','{','}'),'',$namespace);
$str='';
for($i = 0; $i < strlen($hex); $i+=2){
$str .= chr(hexdec($hex[$i].$hex[$i+1]));
}

$hash=sha1($str.$name);

return sprintf('%08s-%04s-%04x-%04x-%12s', substr($hash,0,8),substr($hash,8,4),(hexdec(substr($hash,12,4))&0x0fff)|0x5000,(hexdec(substr($hash,16,4))&0x3fff)|0x8000,substr($hash,20,12));

}


public function getAuthenticatedUsersCount(){


$query = new Query();
$query->select(['id'])->from($this->table);
$cmd = $query->createcommand();
$records = $cmd->queryAll();
$rows = $records;
return count($rows);


}


public function getAuthenticatedUsers($limit_by=10,$offset=0){


$count_query = new Query();
$count_query->select(['id','uid','uuid'])->from($this->table)->orderby('id', SORT_ASC);
$count_cmd = $count_query->createcommand();
$count_records = $count_cmd->queryAll();
$count_rows = $count_records;

$query = new Query();
$query->select(['id','uid','uuid'])->from($this->table)->orderby('id', SORT_ASC)->limit($limit_by)->offset($offset);
$cmd = $query->createcommand();
$records = $cmd->queryAll();
$rows = $records;


for($i=0; $i<count($rows); $i++){
$row=$rows[$i];
$query = new Query();
$query->select(['username'])->from('{{%users}}')->where(['uid'=>$row["uid"]])->limit(1);
$cmd = $query->createcommand();
$record = $cmd->queryAll();
$row["username"]=$record[0]["username"];
$rows[$i]=$row;
}



return array("users"=>$rows,"users_count"=>count($count_rows));


}



public function getUnauthenticatedUserIds(){

$user_ids = [];
$query = new Query();
$query->select(['id','uid'])->from('{{%users}}')->orderby('id', SORT_ASC);
$cmd = $query->createcommand();
$records = $cmd->queryAll();

for($i=0; $i<count($records); $i++){
$record=$records[$i];

$query = new Query();
$query->select(['id'])->from($this->table)->where(['uid'=>$record["uid"]])->orderby('id', SORT_ASC)->limit(1);
$cmd = $query->createcommand();
$path_record = $cmd->queryAll();

if(count($path_record)==0){
$user_ids[] = $record["id"];
}

}

return $user_ids;

}


public function AuthenticateUserById($user_id){


$query = new Query();
$query->select(['uid'])->from('{{%users}}')->where(['id'=>$user_id])->limit(1);
$cmd = $query->createcommand();
$record = $cmd->queryAll();

if(count($record)==0){
return false;
} else {
$generated_uuid=$this->UUIDv5($record[0]["uid"], $this->randword(23));
Craft::$app->getDb()->createCommand()->insert($this->table, ['uid'=>$record[0]["uid"],'uuid'=>$generated_uuid], false)->execute();
return true;
}

}


public function ReAuthenticateUserByUuid($user_uid){


$generated_uuid=$this->UUIDv5($user_uid, $this->randword(23));
if(!$generated_uuid){
return array("success"=>false,"error"=>array("generated_uuid"=>true,"user_uuid"=>false,"user_uid"=>false));
} else if (!$this->is_valid_uuid($user_uid)){
return array("success"=>false,"error"=>array("user_uid"=>true,"generated_uuid"=>false,"user_uuid"=>false));
}
Craft::$app->getDb()->createCommand()->update($this->table, array("uuid"=>$generated_uuid), 'uid="'.$user_uid.'"', array(), false)->execute();
return array("success"=>true, "uuid"=>$generated_uuid);

}


public function SearchAuthenticatedUsers($params,$offset=0,$limit=10){



$count_users_query = new Query();
$count_users_query->select(["id","uid"])->from("{{%users}}")->where(array('like','username', $params."%",false))->orWhere(array('like','email',$params."%",false));
$count_users_cmd = $count_users_query->createcommand();
$count_users_records = $count_users_cmd->queryAll();

if(count($count_users_records)==0){

$count_auth_query = new Query();
$count_auth_query->select(["id","uid"])->from($this->table)->Where(array('like','uuid',$params."%",false));
$count_auth_cmd = $count_auth_query->createcommand();
$count_auth_records = $count_auth_cmd->queryAll();
if(count($count_auth_records)>0){

$user_count=0;
foreach($count_auth_records as $count_auth_record){

$query = new Query();
$query->select(["username"])->from("{{%users}}")->where(array('uid'=>$count_auth_record["uid"]))->limit(1);
$cmd = $query->createcommand();
$user_record = $cmd->queryAll();
if(count($user_record)==1){
$user_count+=1;
}
}


$query = new Query();
$query->select(["id","uid","uuid"])->from($this->table)->Where(array('like','uuid',$params."%",false))->offset($offset)->limit($limit);
$cmd = $query->createcommand();
$auth_records = $cmd->queryAll();
$results_records=array();

foreach($auth_records as $auth_record){

$query = new Query();
$query->select(["username"])->from("{{%users}}")->where(array('uid'=>$auth_record["uid"]))->limit(1);
$cmd = $query->createcommand();
$user_record = $cmd->queryAll();
if(count($user_record)==1){
$auth_record["username"]=$user_record[0]["username"];
$results_records[] = [$auth_record];
}
}

return array("success"=>true,"users"=>$results_records,"users_count"=>$user_count);
} else {return array("success"=>false);}
} else {


$user_auth_count=0;
foreach($count_users_records as $count_users_record){
$query = new Query();
$query->select(["id"])->from($this->table)->where(array('uid'=>$count_users_record['uid']))->limit(1);
$cmd = $query->createcommand();
$user_record = $cmd->queryAll();
if(count($user_record)==1){
$user_auth_count+=1;
}
}


$query = new Query();
$query->select(["id","uid","username"])->from("{{%users}}")->where(array('like','username', $params."%",false))->orWhere(array('like','email',$params."%",false))->offset($offset)->limit($limit);
$cmd = $query->createcommand();
$records = $cmd->queryAll();

$users = array("success"=>true,"users"=>array());

foreach($records as $record){
$query = new Query();
$query->select(["id","uid","uuid"])->from($this->table)->where(array('uid'=>$record['uid']))->limit(1);
$cmd = $query->createcommand();
$user_record = $cmd->queryAll();
if(count($user_record)==1){
$user_record[0]["username"] = $record["username"];
$users["users"][] = $user_record;
}
}

$users["users_count"]=$user_auth_count;
return $users;
}
}


public function GetAllUsers(){


$query = new Query();
$query->select(['id','uid'])->from('{{%users}}');
$cmd = $query->createcommand();
$user_records = $cmd->queryAll();
$request = array("success"=>false);
if(count($user_records)>0){
$request["success"]=true;
$request["users"]=$user_records;
}

return $request;
}


public function IfRouteExists($route){

$query = new Query();
$query->select(['id'])->from($this->table)->where(array('uuid'=>$route))->limit(1);
$cmd = $query->createcommand();
$record = $cmd->queryAll();
if(count($record)==1){
return true;
} else {
return false;
}

}

}
