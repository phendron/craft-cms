<?php

namespace developer\testplugin;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class TestPluginAsset extends AssetBundle {


public function init(){

$this->sourcePath = __dir__. '/'; //'@developer\testplugin\resources';

$this->depends = [ CpAsset::class,];

$this->js = ['sciprt.js',];

$this->css = ['style.css',];

parent::init();

}

}

?>
