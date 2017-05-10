<?php

/*
 * This file is part of the bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace BK2K\Sitepackage\GeneratorBundle\Entity;

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
     * @Assert\Regex(
     *     pattern = "/^[A-Za-z0-9\x7f-\xff .,:!?&-]+$/",
     *     message = "Only letters, numbers and spaces are allowed"
     * )
     * @var string
     */
    private $description;

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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Package
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
