<?php

declare(strict_types=1);

/*
 * This file is part of the package bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Twig\Node;

use Twig\Compiler;
use Twig\Node\Node;
use Twig\Node\NodeOutputInterface;

class FrameNode extends Node implements NodeOutputInterface
{
    protected $tagName = 'frame';

    public function __construct(int $lineno, Node $body = null, Node $attributes = null, string $tag = null)
    {
        $nodes = ['body' => $body];
        if (null !== $attributes) {
            $nodes['attributes'] = $attributes;
        }

        parent::__construct($nodes, [], $lineno, $tag);
    }

    public function compile(Compiler $compiler): void
    {
        $compiler->addDebugInfo($this);

        if ($this->hasNode('attributes')) {
            $compiler
                ->write('$attributes = ')
                ->subcompile($this->getNode('attributes'))
                ->raw(';' . PHP_EOL)
                ->write('if (!is_array($attributes)) {' . PHP_EOL)
                ->indent()
                ->write("throw new UnexpectedValueException('{% {$this->tagName} with x %}: x is not an array');" . PHP_EOL)
                ->outdent()
                ->write('}' . PHP_EOL);
        } else {
            $compiler->write('$attributes = [];' . PHP_EOL);
        }

        $compiler
            ->write('ob_start();' . PHP_EOL)
            ->subcompile($this->getNode('body'))
            ->write('$content = ob_get_clean();' . PHP_EOL)
            ->write('echo $this->env->getExtension(\'App\Twig\Extension\BlockExtension\')->frameFunction($this->env, $content, $attributes);' . PHP_EOL)
        ;
    }
}
