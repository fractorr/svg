# Svg plugin for Craft CMS 3.x

Convert SVG Images

![Screenshot](resources/img/plugin-logo.png)

## Installation

To install Svg, follow these steps:

1. Download & unzip the file and place the `svg` directory into your `craft/plugins` directory
2.  -OR- do a `git clone https://github.com/fractorr/svg.git` directly into your `craft/plugins` folder.  You can then update it with `git pull`
3.  -OR- install with Composer via `composer require fractorr/svg`
4. Install plugin in the Craft Control Panel under Settings > Plugins
5. The plugin folder should be named `svg` for Craft to see it.  GitHub recently started appending `-master` (the branch name) to the name of the folder for zip file downloads.

Svg works on Craft 3.x.

## Svg Overview

Converts SVG Data stored in a section field into various file formats.

## Configuring Svg

Configure the paths, format and sizes and then when an entry is saved and has data in the specified field it will automatically create the images you specified in the plugin settings.

Paths can be both web accessible and non web accessible, you decide.

## Using Svg

You don't need to do anything.

## Svg Roadmap

Nothing yet, I am sure I will come up with stuff once I get more into using it.

## Requirements

This requires [Imagick](http://php.net/manual/en/class.imagick.php) to work

* Release it

Brought to you by [Trevor Orr](http://www.fractorr.com)
