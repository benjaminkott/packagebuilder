<?php

/*
 * This file is part of the bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Service;

use App\Kernel;
use cebe\markdown\GithubMarkdown;
use Symfony\Component\Finder\Finder;

/**
 * DocumentationGenerator
 */
class DocumentationGenerator
{
    /**
     * @var string
     */
    protected $rootDir;

    /**
     * Constructor
     */
    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    /**
     * @param string $directory
     * @return array
     */
    public function getTree($directory = null)
    {
        $documentationRoot =  $this->rootDir . DIRECTORY_SEPARATOR . 'docs';
        $finder = new Finder();
        $finder->sortByName();
        $finder->sortByType();
        $finder->depth('== 0');
        $finder->in($documentationRoot . $directory);
        $tree = [];
        foreach ($finder as $fileInfo) {
            $segment = $this->getPathIdentifier($fileInfo->getFileName());
            $tree[$segment] = [
                'type' => $fileInfo->getType(),
                'children' => ($fileInfo->isDir() ? $this->getTree(str_replace($documentationRoot, '', $fileInfo->getRealPath())) : null),
                'segment' => $segment,
                'menu' => ($segment === 'index' ? false : true),
                'name' => $this->getName($fileInfo),
                'info' => $fileInfo
            ];
        }

        return $tree;
    }

    /**
     * @param string $path
     * @param array $tree
     * @return SplFileInfo|null
     */
    public function findPathInTree($path = null, $tree)
    {
        // If path not defined, check for index
        if (!$path) {
            $path = 'index';
        }

        // Split path into segments
        $paths = explode('/', $path);
        $segment = array_shift($paths);

        if (isset($tree[$segment])
            && count($paths) >= 1
            && $tree[$segment]['info']->isDir()
            && $tree[$segment]['children']) {
            return $this->findPathInTree(implode('/', $paths), $tree[$segment]['children']);
        }

        if (isset($tree[$segment])
            && count($paths) === 0
            && $tree[$segment]['info']->isFile()) {
            return $tree[$segment]['info'];
        }

        if (isset($tree[$segment])
            && count($paths) === 0
            && $tree[$segment]['info']->isDir()
            && isset($tree[$segment]['children']['index'])
            && $tree[$segment]['children']['index']['info']->isFile()) {
            return $tree[$segment]['children']['index']['info'];
        }

        return null;
    }

    /**
     * @param SplFileInfo $file
     * @return string
     */
    public function renderFile($file)
    {
        $content = ($file ? $file->getContents() : 'FILE COULD NOT BE RESOLVED');
        $parser = new GithubMarkdown();

        return $parser->parse($content);
    }

    /**
     * @param SplFileInfo $fileInfo
     * @return string
     */
    public function getName($fileInfo)
    {
        $value = $fileInfo->getBasename();
        preg_match('/^-?[0-9]*_?(.*)/', $value, $matches);
        $value = empty($matches[1]) ? $matches[0] : $matches[1];
        $value = str_replace('_', ' ', $value);
        $value = basename($value, '.md');
        return $value;
    }

    /**
     * @param string $path
     * @return string
     */
    public function getPathIdentifier($filename)
    {
        $pathArray = [];
        foreach (explode(DIRECTORY_SEPARATOR, $filename) as $key => $value) {
            preg_match('/^-?[0-9]*_?(.*)/', $value, $matches);
            $value = empty($matches[1]) ? $matches[0] : $matches[1];
            $pathArray[$key] = basename($value, '.md');
        }
        return strtolower(implode('/', $pathArray));
    }
}
