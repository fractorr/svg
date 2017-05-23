<?php
/**
 * Svg plugin for Craft CMS 3.x
 *
 * Convert SVG Images
 *
 * @link      http://www.fractorr.com
 * @copyright Copyright (c) 2017 Trevor Orr
 */

namespace fractorr\svg\variables;

use fractorr\svg\Svg;

use Craft;

/**
 * Svg Variable
 *
 * Craft allows plugins to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.svg }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    Trevor Orr
 * @package   Svg
 * @since     1.0.0
 */
class SvgVariable
{
    // Public Methods
    // =========================================================================

    /**
     * Whatever you want to output to a Twig tempate can go into a Variable method.
     * You can have as many variable functions as you want.  From any Twig template,
     * call it like this:
     *
     *     {{ craft.svg.exampleVariable }}
     *
     * Or, if your variable requires parameters from Twig:
     *
     *     {{ craft.svg.exampleVariable(twigValue) }}
     *
     * @param null $optional
     * @return string
     */
    public function exampleVariable($optional = null)
    {
        $result = "And away we go to the Twig template...";
        if ($optional) {
            $result = "I'm feeling optional today...";
        }
        return $result;
    }
}
