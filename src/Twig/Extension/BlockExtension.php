<?php

declare(strict_types=1);

/*
 * This file is part of the package bk2k/packagebuilder.
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Twig\Extension;

use App\Twig\TokenParser\FrameTokenParser;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BlockExtension extends AbstractExtension
{
    public function getTokenParsers()
    {
        return [
            new FrameTokenParser(),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('frame', $this->frameFunction(...), ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    public function frameFunction(Environment $environment, string $content, array $attributes): string
    {
        $attributes['id'] ??= null;
        $attributes['type'] ??= 'default';
        $attributes['frameClass'] = $attributes['type'] ?? 'default';
        $attributes['size'] ??= 'default';
        $attributes['height'] ??= 'default';
        $attributes['layout'] ??= 'default';
        $attributes['backgroundColor'] ??= 'none';
        $attributes['spaceBefore'] ??= 'none';
        $attributes['spaceAfter'] ??= 'none';
        $attributes['options'] ??= [];
        $attributes['backgroundImage'] ??= null;

        $identifier = $attributes['id'];

        $classes = [];
        $classes[] = 'frame';
        $classes[] = 'frame-' . $attributes['frameClass'];
        $classes[] = 'frame-type-' . $attributes['type'];
        $classes[] = 'frame-layout-' . $attributes['layout'];
        $classes[] = 'frame-size-' . $attributes['size'];
        $classes[] = 'frame-height-' . $attributes['height'];
        $classes[] = 'frame-background-' . $attributes['backgroundColor'];
        $classes[] = 'frame-space-before-' . $attributes['spaceBefore'];
        $classes[] = 'frame-space-after-' . $attributes['spaceAfter'];

        if (is_array($attributes['options'])) {
            foreach ($attributes['options'] as $option) {
                if (trim((string) $option) !== '') {
                    $classes[] = 'frame-option-' . trim((string) $option);
                }
            }
        }

        $classes[] = 'frame-no-backgroundimage';

        return $environment->render('extension/block/frame.html.twig', [
            'id' => $attributes['id'],
            'configuration' => $attributes,
            'classes' => $classes,
            'content' => $content
        ]);
    }
}
