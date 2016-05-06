<?php

namespace BK2K\Sitepackage\GeneratorBundle\Entity;

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

use BK2K\Sitepackage\GeneratorBundle\Entity\Package\Author;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Package
 */
class Package
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3
     * )
     * @Assert\Regex(
     *     pattern = "/^[A-Za-z0-9]+$/",
     *     message = "Only letters, numbers are allowed"
     * )
     * @var string
     */
    private $vendorName;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3
     * )
     * @Assert\Regex(
     *     pattern = "/^[A-Za-z0-9_]+$/",
     *     message = "Only letters, numbers and '_' are allowed"
     * )
     * @var string
     */
    private $applicationName;

    /**
     * @Assert\Url()
     * @var string
     */
    private $repositoryUrl = '';

    /**
     * @Assert\Valid
     * @var Author
     */
    private $author;

    /**
     * @return string
     */
    public function getVendorName()
    {
        return $this->vendorName;
    }

    /**
     * @param string $vendorName
     * @return Package
     */
    public function setVendorName($vendorName)
    {
        $this->vendorName = $vendorName;
        return $this;
    }

    /**
     * @return string
     */
    public function getApplicationName()
    {
        return $this->applicationName;
    }

    /**
     * @param string $applicationName
     * @return Package
     */
    public function setApplicationName($applicationName)
    {
        $this->applicationName = $applicationName;
        return $this;
    }


    /**
     * @return string
     */
    public function getRepositoryUrl()
    {
        return $this->repositoryUrl;
    }

    /**
     * @param string $repositoryUrl
     * @return Package
     */
    public function setRepositoryUrl($repositoryUrl)
    {
        $this->repositoryUrl = $repositoryUrl;
        return $this;
    }

    /**
     * @return Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param Author $author
     * @return Package
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }
}

