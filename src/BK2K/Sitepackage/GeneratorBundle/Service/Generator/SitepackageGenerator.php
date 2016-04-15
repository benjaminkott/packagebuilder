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
    protected $zip;
    
    protected $filename;

    public function create(Package $sitepackage = null)
    {
        $this->filename = __DIR__ . '/Documents-'.time().".zip";
        $source_dir = __DIR__ . '/../../Resources/skeletons/BaseExtension/';
        $zip_file = $this->filename;
        $file_list = FileUtility::listDirectory($source_dir);

        $this->zip = new \ZipArchive();
        $opened = $this->zip->open($zip_file, \ZipArchive::CREATE);
        if ($opened === true) {
            foreach ($file_list as $file) {
                if ($file !== $zip_file && file_exists($file)) {
                    $nameInZip = substr($file, strlen($source_dir), -5);
                    $content = $this->getFileContent($file, $sitepackage);
                    $this->zip->addFromString($nameInZip, $content);
                }
            }
            $this->zip->close();
        }
    }
    
    public function getZip()
    {
        return $this->zip;
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
}
