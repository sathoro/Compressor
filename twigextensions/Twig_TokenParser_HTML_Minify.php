<?php

namespace Craft;

require_once(__DIR__ . '/Twig_Node_HTML_Minify.php');

class Twig_TokenParser_HTML_Minify extends \Twig_TokenParser
{
    public function parse(\Twig_Token $token)
    {
        $lineno = $token->getLine();

        $this->parser->getStream()->expect(\Twig_Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse(array($this, 'decideMinifyEnd'), true);
        $this->parser->getStream()->expect(\Twig_Token::BLOCK_END_TYPE);

        return new Twig_Node_HTML_Minify($body, $lineno, $this->getTag());
    }

    public function decideMinifyEnd(\Twig_Token $token)
    {
        return $token->test('endminify');
    }

    /**
     * Gets the tag name associated with this token parser.
     *
     * @return string The tag name
     */
    public function getTag()
    {
        return 'minify';
    }
}
