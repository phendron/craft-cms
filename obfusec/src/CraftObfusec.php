<?php
/**
 * Test Plugin plugin for Craft CMS 3.x
 *
 * This is a Test Plugin
 *
 * @link      Me@me.me
 * @copyright Copyright (c) 2021 Me
 */

namespace phendron\craftobfusec;


use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;

use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
use craft\web\User;
use yii\base\Event;

use phendron\craftobfusec\controllers\AdministratorController;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://docs.craftcms.com/v3/extend/
 *
 * @author    Me
 * @package   TestPlugin
 * @since     1.0.0
 *
 */
class CraftObfusec extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * TestPlugin::$plugin
     *
     * @var TestPlugin
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public $schemaVersion = '1.0.0';

    /**
     * Set to `true` if the plugin should have a settings view in the control panel.
     *
     * @var bool
     */
    public $hasCpSettings = false;

    /**
     * Set to `true` if the plugin should have its own section (main nav item) in the control panel.
     *
     * @var bool
     */
    public $hasCpSection = true;

    public $plugin_handle = "craft-obfusec";


    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * TestPlugin::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;


       $this->setComponents(['settingsquery' => services\SettingsQuery::class,]);
       $this->setComponents(['pathbrowserquery' => services\PathBrowserQuery::class,]);


        // Do something after we're installed
        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                    // We were just installed
                }
            }
        );

/**
 * Logging in Craft involves using one of the following methods:
 *
 * Craft::trace(): record a message to trace how a piece of code runs. This is mainly for development use.
 * Craft::info(): record a message that conveys some useful information.
 * Craft::warning(): record a warning message that indicates something unexpected has happened.
 * Craft::error(): record a fatal error that should be investigated as soon as possible.
 *
 * Unless `devMode` is on, only Craft::warning() & Craft::error() will log to `craft/storage/logs/web.log`
 *
 * It's recommended that you pass in the magic constant `__METHOD__` as the second parameter, which sets
 * the category to the method (prefixed with the fully qualified class name) where the constant appears.
 *
 * To enable the Yii debug toolbar, go to your user account in the AdminCP and check the
 * [] Show the debug toolbar on the front end & [] Show the debug toolbar on the Control Panel
 *
 * http://www.yiiframework.com/doc-2.0/guide-runtime-logging.html
 */
        Craft::info(
            Craft::t(
                'craft-obfusec',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );



    // Event:on /admin/login
    // Description: redirect route
    $default_admin_url_route = "secure_login";
    Event::on(
        UrlManager::class,
        UrlManager::EVENT_REGISTER_CP_URL_RULES,
        static function(RegisterUrlRulesEvent $event) use (&$default_admin_url_route){
            $plugin_handle="craft-obfusec";
            $event->rules[] = [
                'pattern' => 'login/',
                'route' => $plugin_handle.'/administrator/missing'
            ];

            $event->rules[] = [
                'pattern' => $default_admin_url_route."/<id:[\w-]+>/",
                'route' => $plugin_handle.'/administrator/login'
            ];
            $event->rules[] = [
                'pattern' => $default_admin_url_route."/<id:[\w-]+>/authenticate/",
                'route' => $plugin_handle.'/administrator/authenticate'
            ];
            $event->rules[] = [
                'pattern' => $plugin_handle,
                'route' => $plugin_handle.'/controlpanel/init'
            ];
            $event->rules[] = [
                'pattern' => $plugin_handle.'/path-browser',
                'route' => $plugin_handle.'/controlpanel/pathbrowser'
            ];

            $event->rules[] = [
               'pattern' => $plugin_handle.'/controlpanel/initializeuser',
                'route' => $plugin_handle.'/controlpanel/initializeuser'
            ];
            $event->rules[] = [
                'pattern' => $plugin_handle.'/controlpanel/uninitializedusers',
                'route' => $plugin_handle.'/controlpanel/uninitializedusers'
            ];
            $event->rules[] = [
                'pattern' => $plugin_handle.'/controlpanel/reinitializeuser',
                'route' => $plugin_handle.'/controlpanel/reinitializeuser'
            ];
            $event->rules[] = [
                'pattern' => $plugin_handle.'/controlpanel/searchauthenticatedusers',
                'route' => $plugin_handle.'/controlpanel/searchauthenticatedusers'
            ];

            $event->rules[] = [
                'pattern' => $plugin_handle.'/controlpanel/getallusers',
                'route' => $plugin_handle.'/controlpanel/getallusers'
            ];

            //$event->rules['login'] = 'testplugin/login/404';
        });



    // Register User Before Login event to disable default login action
    Event::on(
        User::class,
        User::EVENT_BEFORE_LOGIN,
        static function($event){
            // Get request login action Url
            $action_url = Craft::$app->getRequest()->absoluteUrl;
            // parse Url by PATH
            $parsed_url = parse_url($action_url, PHP_URL_PATH);
            // Split Url PATH by "/"
            $parsed_url = explode("/", $parsed_url);
            // Check a PATH exists
            if(count($parsed_url) > 0){
            // if default login action
            if($parsed_url[1]=="index.php"){
                exit(0); // exit correctly
            }
            }
        });


    }



    //public function getSettingsResponse(){
    //return \Craft::$app->controller->renderTemplate('test-plugin/settings/template', ['settings', $this->getSettings()]);
    //}

    // Protected Methods
    // =========================================================================


    
    //protected function createSettingsModel(){
    //    return new \developer\testplugin\models\Settings();
    //}

    //protected function settingsHtml(){
    //    return \Craft::$app->getView()->renderTemplate('test-plugin/settings', ['settings' => $this->getSettings()]);
    //}

}
