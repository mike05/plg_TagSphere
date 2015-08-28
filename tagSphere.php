<?php

/**
 * plugin blankContentPlugin
 * @version 1.2
 * @package blankContentPlugin
 * @copyright Copyright (c) Jahr Firmennamen URL
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
/**
 * Platz für Informationen
 * =======================
 *
 * Anwendung im Content:
 *   {TagSphere}
 *
 * Anwendung im Content mit Parameterübergabe:
 *   {TagSphere param1=Hello!|param2=it works fine|param3=Joomla! rocks ;-)}
 */
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgContentTagSphere extends JPlugin {
    
 

    function plgContentTagSphere(&$subject,$params) {
        parent::__construct($subject,$params);
       
    }

    /**
     * Contentstring Definition
     * String erkennen und mit neuem Inhalt füllen
     */
    public function onContentPrepare($context, &$article, &$params, $limitstart) {
        $regex = '/{TagSphere\s*(.*?)}/i';
        $article->text = preg_replace_callback($regex, array($this, "form"), $article->text);
        return true;
    }

    public function form($matches) {

        /**
         * Contentstring zerlegen
         */
        $string = $matches[1];
        $params = explode('|', $string);


        
        /**
         * individuelle Anwendung starten
         */
        
        if (isset($parameter1)) {
            $output .= "<h4>" . $parameter1 . "</h4>";
        }
       
        
        $output = $this->createJS();
         $output .= ' <div id="tagcloud" style="color: ' . $this->params->get('fontcolor') .'; background-color: ' . $this->params->get('bgcolor','transparent') . ';">
        <ul>';
        
        /**
         * Parameter raus filtern und Variablen erstellen
         */
        foreach ($params as $param) {
            
            //  <li><a href="#" style="color: ' . $this->params->get('fontcolor') . ';">dazzlingly</a></li>
            
            if (stristr($param, 'tags=')) {
                $parameter1 = str_replace('tags=', '', $param);
                
                $tags = explode(',', $parameter1);
                foreach ($tags as $tag)
                {
                    $output .= ' <li><a href="#" style="color: ' . $this->params->get('fontcolor') . ';">' . trim($tag) . '</a></li>';
                }
                
            }
            /*if (stristr($param, 'param2=')) {
                $parameter2 = str_replace('param2=', '', $param);
            }
            if (stristr($param, 'param3=')) {
                $parameter3 = str_replace('param3=', '', $param);
            }*/
            // kann beliebig erweitert werden
        }
        
        
        
          
            
        $output .=  '</ul></div>';


        return $output;
    }

    
    protected function createJS() {
        
        $output = "";
        if($this->params->get('usejQuery'))
            $output .= '<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>';
        
        $output .= '<script type="text/javascript" src="' . JUri::base() . 'plugins/content/tagSphere/js/tagcloud.jquery.min.js"></script>';
        $output .= '<script type="text/javascript" >';        
        $output .= 'var settings = {';
        $output .= 'height: ' . $this->params->get('height') . 
                ',width: ' . $this->params->get('height') . 
                ',radius: ' . $this->params->get('radius') . 
                ',speed: ' . $this->params->get('speed') . 
                ',slower: 0.9, timer: 5,fontMultiplier: '. $this->params->get('fontMultiplier') . ',';
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