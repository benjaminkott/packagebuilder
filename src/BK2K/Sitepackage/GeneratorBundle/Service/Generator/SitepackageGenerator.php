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

use BK2K\Sitepackage\GeneratorBundle\Entity\Sitepackage;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * SitepackageGenerator.
 */
class SitepackageGenerator
{
    protected $zip;
    
    protected $filename;

    public function create(Sitepackage $sitepackage = null)
    {
        // @TODO GENERATE RANDOM FILENAME IN TEMP
        $this->filename = 'Documents-'.time().".zip";
        $this->zip = new \ZipArchive();
        $this->zip->open($this->filename,  \ZipArchive::CREATE);
        // @TODO DO STUFF
        $this->zip->addFromString('file_name_within_archive.ext', 'TEST');
        // @TODO DO STUFF
        $this->zip->close();
    }
    
    public function getZip()
    {
        return $this->zip;
    }
    
    public function getFilename()
    {
        return $this->filename;
    }
}
