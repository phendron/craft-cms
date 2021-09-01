<?php

namespace developer\testplugin;

use craft\web\AssetBundle;
//use craft\web\assets\cp\CpAsset;

class CpAssetsBundle extends AssetBundle {


public function init(){

$this->sourcePath = '@craft-cms/cms/assets';

//$this->depends = [ CpAsset::class,];

//$this->js = ['Craft.min.js',];

//$this->css = ['style.css',];

parent::init();

}

}

?>
