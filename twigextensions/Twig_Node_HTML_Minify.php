<?php

namespace Craft;

class Twig_Node_HTML_Minify extends \Twig_Node
{
    public function __construct(\Twig_NodeInterface $body, $lineno, $tag = 'minify')
    {
        parent::__construct(array('body' => $body), array(), $lineno, $tag);
    }

    public function getTag()
    {
        return 'minify';
    }

    /**
     * Compiles the node to PHP.
     *
     * @param Twig_Compiler A Twig_Compiler instance
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write("ob_start();\n")
            ->subcompile($this->getNode('body'))
            ->write("\$minifier = \$this->getEnvironment()->getExtension('minify');")
            ->write("\$minifier->setHTML(ob_get_clean());")
            ->write("echo \$minifier->minifyHTML();\n")
        ;
    }
}