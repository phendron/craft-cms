<?php

namespace phendron\craftobfusec;

use Craft;
use craft\base\ElementInterface;
use craft\config\GeneralConfig;
use craft\elements\User;
use craft\helpers\Assets;
use craft\helpers\Json;
use craft\helpers\StringHelper;
use craft\helpers\UrlHelper;
use craft\i18n\Locale;
use craft\models\Section;
use craft\services\Sites;
use craft\web\AssetBundle;
use craft\web\assets\axios\AxiosAsset;
use craft\web\assets\d3\D3Asset;
use craft\web\assets\datepickeri18n\DatepickerI18nAsset;
use craft\web\assets\elementresizedetector\ElementResizeDetectorAsset;
use craft\web\assets\fabric\FabricAsset;
use craft\web\assets\fileupload\FileUploadAsset;
use craft\web\assets\garnish\GarnishAsset;
use craft\web\assets\iframeresizer\IframeResizerAsset;
use craft\web\assets\jquerypayment\JqueryPaymentAsset;
use craft\web\assets\jquerytouchevents\JqueryTouchEventsAsset;
use craft\web\assets\jqueryui\JqueryUiAsset;
use craft\web\assets\picturefill\PicturefillAsset;
use craft\web\assets\selectize\SelectizeAsset;
use craft\web\assets\velocity\VelocityAsset;
use craft\web\assets\xregexp\XregexpAsset;
use craft\web\View;
use craft\web\assets\cp\CpAsset;
use yii\web\JqueryAsset;

class CraftobfusecBundle extends AssetBundle {


public function init(){

$this->sourcePath = '@phendron/craftobfusec/resources';

$this->depends = [ CpAsset::class,];

$this->js = ['script.js',];

$this->css = ['style.css',];

parent::init();

}

}

?>
