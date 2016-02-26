<?php

/**
 * plugin plgContentTagSphere
 * @version 1.2
 * @package plgContentTagSphere
 * @copyright Copyright (c) 2015 Michael Gollner www.it-mg.net
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0 GNU/GPL Version 2.0
 */
/**
 * Informations
 * =======================
 *
 * Usage in content:
 *   {TagSphere}
 *
 * Usage in content with paramters:
 *   {TagSphere param1=Hello!|param2=it works fine|param3=Joomla! rocks ;-)}
 */
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgContentTagSphere extends JPlugin {

    function plgContentTagSphere(&$subject, $params) {
        parent::__construct($subject, $params);
    }

    /**
     * Contentstring definition
     * Get string and fill it with new data
     */
    public function onContentPrepare($context, &$article, &$params, $limitstart) {
        $regex = '/{TagSphere\s*(.*?)}/i';
        $article->text = preg_replace_callback($regex, array($this, "form"), $article->text);
        return true;
    }

    public function form($matches) {

        /**
         * Split contentstring 
         */
        $string = $matches[1];
        $params = explode('|', $string);

        //create JS output
        $output = $this->createJS();

        //extend output with the tagcloud html div tag
        $output .= ' <div id="tagcloud" style="color: ' . $this->params->get('fontcolor') . '; background-color: ' . $this->params->get('bgcolor', 'transparent') . ';">
        <ul>';

        /**
         * Filter params and create variables
         */
        foreach ($params as $param) {

            if (stristr($param, 'tags=')) {
                $parameter1 = str_replace('tags=', '', $param);

                $tags = explode(',', $parameter1);
                foreach ($tags as $tag) {
                    $output .= ' <li><a href="#" style="color: ' . $this->params->get('fontcolor') . ';">' . trim($tag) . '</a></li>';
                }
            }
            /* if (stristr($param, 'param2=')) {
              $parameter2 = str_replace('param2=', '', $param);
              }
              if (stristr($param, 'param3=')) {
              $parameter3 = str_replace('param3=', '', $param);
              } */
            // extend if you want
        }





        $output .= '</ul></div>';


        return $output;
    }

    protected function createJS() {

        $output = "";
        if ($this->params->get('usejQuery'))
            $output .= '<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>';

        $output .= '<script type="text/javascript" src="' . JUri::base() . 'plugins/content/tagSphere/js/tagcloud.jquery.min.js"></script>';
        $output .= '<script type="text/javascript" >';
        $output .= 'var settings = {';
        $output .= 'height: ' . $this->params->get('height') .
                ',width: ' . $this->params->get('width') .
                ',radius: ' . $this->params->get('radius') .
                ',speed: ' . $this->params->get('speed') .
                ',slower: 0.9, timer: 5,fontMultiplier: ' . $this->params->get('fontMultiplier') . ',';
        $output .= 'hoverStyle: {border: "none", color: "#0b2e6f" },';
        $output .= 'mouseOutStyle: { border: "", color: "" }};';

        $output .= 'jQuery(document).ready(function(){';
        $output .= "jQuery('#tagcloud').tagoSphere(settings);";
        $output .= '});';


        $output .= '</script>';
        return $output;
    }

}

?>
