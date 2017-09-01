<?php
/**
 * Svg plugin for Craft CMS 3.x
 *
 * Convert SVG Images
 *
 * @link      http://www.fractorr.com
 * @copyright Copyright (c) 2017 Trevor Orr
 */

namespace fractorr\svg;

use fractorr\svg\services\SvgService as SvgServiceService;
use fractorr\svg\variables\SvgVariable;
use fractorr\svg\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;

use craft\base\Element;
use craft\services\Elements;
use craft\events\ElementEvent;
use craft\elements\Entry;

//use craft\base\Section;
//use craft\services\Sections;
//use craft\events\SectionEvent;


use yii\base\Event;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://craftcms.com/docs/plugins/introduction
 *
 * @author    Trevor Orr
 * @package   Svg
 * @since     1.0.0
 *
 * @property  SvgServiceService $svgService
 */
class Svg extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * Svg::$plugin
     *
     * @var Svg
     */
    public static $plugin;
	public $savedEntry = null;
	
	
    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * Svg::$plugin
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
		
        // Do something after we're installed
        Event::on(
            Plugins::className(),
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                    // We were just installed
                }
            }
        );

		Event::on(
			Elements::class, 
			Elements::EVENT_BEFORE_SAVE_ELEMENT, 
            function (ElementEvent $event) {
            	if (!$this->savedEntry) {
					$this->savedEntry = Entry::find()->slug($event->element->slug)->one();
					
					/*
	            	echo "<pre>";
	            	echo "EVENT_BEFORE_SAVE_ELEMENT\n";
	            	print_r($this->savedEntry['productSvg']);
	            	echo "</pre>";
	            	*/
               	}
            }
		);
		
		Event::on(
			Elements::class, 
			Elements::EVENT_AFTER_SAVE_ELEMENT, 
            function (ElementEvent $event) {
            	/* 
            	echo "<pre>";
            	print_r(Craft::$app->sections->getAllSections());
            	echo "</pre>";
            	die();
            	*/
            	
            	$entryTypeParts = explode("/", $event->element["uri"]);
            	$entryType = $entryTypeParts[0];
            	
                if ($entryType == $this->getSettings()->entryTypeHandle)
                {
                	$pathFiles = $this->getSettings()->pathFiles;
	                
	                /*
	            	echo "<pre>";
	            	print_r($pathFiles);
	            	echo "</pre>";
	            	die();
	            	
	            	echo "<pre>";
	            	print_r($event->element);
	            	echo "</pre>";
	            	die();
	            	*/

	            	
                	foreach ($pathFiles as $path)
					{
						/*
		            	echo "<hr>";
		            	echo "<pre>";
		            	echo "EVENT_AFTER_SAVE_ELEMENT\n";
		            	print_r($event->element[$path[4]]);
		            	echo "<hr>";
		            	echo "<hr>";
		            	print_r($this->savedEntry[$path[4]]);
		            	echo "</pre>";
		            	die();
						*/

						if ($event->element[$path[4]] != "" && (int)$path[7] === 1 && strcmp($event->element[$path[4]], $this->savedEntry[$path[4]]) !== 0 )
						{
							/*
			                define("PATH_LABEL", 	0);
			                define("PATH_PATH", 	1);
			                define("PATH_RES", 		2);
			                define("PATH_FORMAT", 	3);
			                define("PATH_FIELD", 	4);
			                define("PATH_WIDTH", 	5);
			                define("PATH_HEIGHT", 	6);
			                define("PATH_ENABLED", 	7);
							*/
							
			                $im = new \Imagick();
			                $tp = new \ImagickPixel('transparent');
			                
							$im->setBackgroundColor($tp);
			                $im->setResolution($path[2], $path[2]);
			                $im->readImageBlob($event->element[$path[4]]);
			                $im->trimImage(20000);
			                
			                if ($path[5] != 0 || $path[6] != 0) 
			                {
								$im->resizeImage($path[5], $path[6], \Imagick::FILTER_LANCZOS, 1);
			                }
			                
			                $im->setImageFormat($path[3]);

			                if ($path[3] == "pdf") 
			                {
				                $im->setPage($im->getImageWidth(), $im->getImageHeight(), 0, 0);
			                }
			                
			                $im->setImageUnits(\Imagick::RESOLUTION_PIXELSPERINCH);
			                $raw_data = $im->getImageBlob();
			                
			                if (!file_exists($path[1])) 
			                {
								mkdir($path[1], 0777, true);
							}

							if ($path[3] == "png32") {
								$path[3] = "png";
							}

			                $im->writeImage($path[1] . '/' .  $event->element->slug .  '.' . $path[3]);
							$im->clear();
							$im->destroy();
						}
                	}
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
                'svg',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    /**
     * Returns the component definition that should be registered on the
     * [[\craft\web\twig\variables\CraftVariable]] instance for this plugin’s handle.
     *
     * @return mixed|null The component definition to be registered.
     * It can be any of the formats supported by [[\yii\di\ServiceLocator::set()]].
     */
    public function defineTemplateComponent()
    {
        return SvgVariable::class;
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates and returns the model used to store the plugin’s settings.
     *
     * @return \craft\base\Model|null
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * Returns the rendered settings HTML, which will be inserted into the content
     * block on the settings page.
     *
     * @return string The rendered settings HTML
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'svg/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
