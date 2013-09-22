<?php

namespace Craft;

require_once(__DIR__ . '/Twig_TokenParser_HTML_Minify.php');

class CompressorTwigExtension extends \Twig_Extension
{
    private $html;

    public function __construct()
    {
        return $this;
    }

    public function getName()
    {
        return 'minify';
    }

    public function setHTML($html)
    {
        $this->html = $html;
    }

    public function minifyHTML()
    {
        require_once(__DIR__ . '/lib/Minify/HTML.php');
        
        return \Minify_HTML::minify($this->html);
    }

    public function getTokenParsers()
    {
        return array(
            new Twig_TokenParser_HTML_Minify()
        );
    }
}