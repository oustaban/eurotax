<?php

namespace Application\Sonata\ClientOperationsBundle\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

/**
 * REPLACE(str, from_str, to_str)
 * "REPLACE" "(" StringPrimary "," StringPrimary "," StringPrimary ")"
 */
class ReplaceFunction extends FunctionNode
{
    protected $stringStr, $stringFromStr, $stringToStr;
 
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)  {   
        return sprintf(
            'REPLACE(%s, %s, %s)',
            $sqlWalker->walkStringPrimary($this->stringStr),
            $sqlWalker->walkStringPrimary($this->stringFromStr),
            $sqlWalker->walkStringPrimary($this->stringToStr)
        );  
    }   
 
	public function parse(\Doctrine\ORM\Query\Parser $parser) {   
        $parser->match(Lexer::T_IDENTIFIER);                // REPLACE
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->stringStr = $parser->StringPrimary();        // str
        $parser->match(Lexer::T_COMMA);
        $this->stringFromStr = $parser->StringPrimary();    // from_str
        $parser->match(Lexer::T_COMMA);
        $this->stringToStr = $parser->StringPrimary();      // to_str
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }   
}