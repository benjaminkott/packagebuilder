<?php

declare(strict_types=1);

/*
 * This file is part of the package bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Twig\TokenParser;

use App\Twig\Node\FrameNode;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

final class FrameTokenParser extends AbstractTokenParser
{
    public function parse(Token $token)
    {
        $stream = $this->parser->getStream();

        $attributes = null;
        if ($stream->nextIf(Token::NAME_TYPE, 'with')) {
            $attributes = $this->parser->getExpressionParser()->parseExpression();
        }

        $stream->expect(Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse($this->decideFrameEnd(...), true);
        $stream->expect(Token::BLOCK_END_TYPE);

        return new FrameNode($token->getLine(), $body, $attributes, $this->getTag());
    }

    public function decideFrameEnd(Token $token)
    {
        return $token->test('endframe');
    }

    public function getTag()
    {
        return 'frame';
    }
}
