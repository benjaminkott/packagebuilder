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
     * @var string
     */
    private $vendorName;

    /**
     * @var string
     */
    private $vendorNameAlternative;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3
     * )
     * @Assert\Regex(
     *     pattern = "/^[A-Za-z0-9\x7f-\xff .:&-]+$/",
     *     message = "Only letters, numbers and spaces are allowed"
     * )
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $packageName;

    /**
     * @var string
     */
    private $packageNameAlternative;

    /**
     * @var string
     */
    private $extensionKey;

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
    public function getVendorNameAlternative()
    {
        return $this->vendorNameAlternative;
    }

    /**
     * @param string $vendorNameAlternative
     * @return Package
     */
    public function setVendorNameAlternative($vendorNameAlternative)
    {
        $this->vendorNameAlternative = $vendorNameAlternative;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Package
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getPackageName()
    {
        return $this->packageName;
    }

    /**
     * @param string $packageName
     * @return Package
     */
    public function setPackageName($packageName)
    {
        $this->packageName = $packageName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPackageNameAlternative()
    {
        return $this->packageNameAlternative;
    }

    /**
     * @param string $packageNameAlternative
     * @return Package
     */
    public function setPackageNameAlternative($packageNameAlternative)
    {
        $this->packageNameAlternative = $packageNameAlternative;
        return $this;
    }

    /**
     * @return string
     */
    public function getExtensionKey()
    {
        return $this->extensionKey;
    }

    /**
     * @param string $extensionKey
     * @return Package
     */
    public function setExtensionKey($extensionKey)
    {
        $this->extensionKey = $extensionKey;
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

