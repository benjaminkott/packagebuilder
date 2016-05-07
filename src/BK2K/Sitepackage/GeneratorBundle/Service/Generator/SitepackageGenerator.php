<?php

namespace BK2K\Sitepackage\GeneratorBundle\Service\Generator;

/*
 *  The MIT License (MIT)
 *
 *  Copyright (c) 2016 Benjamin Kott, http://www.bk2k.info
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is
 *  furnished to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in
 *  all copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 */

use BK2K\Sitepackage\GeneratorBundle\Entity\Package;
use BK2K\Sitepackage\GeneratorBundle\Utility\FileUtility;

/**
 * SitepackageGenerator.
 */
class SitepackageGenerator
{
    protected $zipPath;

    protected $filename;

    const SKELETON_NAME = 'skeleton';

    public function create(Package $package = null)
    {
        $extensionKey = $package->getExtensionKey();
        $this->filename = $extensionKey . '.zip';
        $source_dir = __DIR__ . '/../../Resources/skeletons/BaseExtension/';
        $this->zipPath = __DIR__ . '/' . $this->filename;
        $fileList = FileUtility::listDirectory($source_dir);

        $zipFile = new \ZipArchive();
        $opened = $zipFile->open($this->zipPath, \ZipArchive::CREATE);
        if ($opened === true) {
            foreach ($fileList as $file) {
                if ($file !== $this->zipPath && file_exists($file)) {
                    $baseFileName = $this->replaceSkeletonNameInPath($file, $source_dir);
                    if (!$this->isTwigFile($file)) {
                        $zipFile->addFile($file, $baseFileName);
                    } else {
                        $content = $this->getFileContent($file, $package);
                        $nameInZip = $this->removeTwigExtension($baseFileName);
                        $zipFile->addFromString($nameInZip, $content);
                    }
                }
            }
            $zipFile->close();
        }
    }

    public function getZipPath()
    {
        return $this->zipPath;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    private function getFileContent($file, Package $package)
    {
        $content = file_get_contents($file);
        $fileUniqueId = uniqid('file');
        $twig = new \Twig_Environment(new \Twig_Loader_Array([$fileUniqueId => $content]));
        $rendered = $twig->render(
            $fileUniqueId,
            [
                'package' => $package,
                'timestamp' => time()
            ]
        );
        return $rendered;
    }

    private function isTwigFile($file)
    {
        $pathinfo = pathinfo($file);
        return $pathinfo['extension'] == 'twig';
    }

    /**
     * @param $file
     * @param $source_dir
     * @return mixed
     */
    protected function createRelativeFilePath($file, $source_dir)
    {
        return substr($file, strlen($source_dir));
    }

    /**
     * @param $file
     * @param $source_dir
     * @return mixed
     */
    protected function replaceSkeletonNameInPath($file, $source_dir)
    {
        return str_replace(self::SKELETON_NAME . '/', '',
            $this->createRelativeFilePath($file, $source_dir));
    }

    /**
     * @param $baseFileName
     * @return mixed
     */
    protected function removeTwigExtension($baseFileName)
    {
        return substr($baseFileName, 0, -5);
    }
}
