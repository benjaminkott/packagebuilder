<?php

/*
 * This file is part of the bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Entity;

use App\Entity\Package\Author;
use JMS\Serializer\Annotation as Serializer;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Package
 */
class Package implements \JsonSerializable
{
    /**
     * @Assert\NotBlank()
     * @Assert\Choice({
     *     10004000,
     *     9005000,
     *     8007000
     * })
     *
     * @SWG\Property(type="int", example="9005000")
     * @Serializer\Type("int")
     * @var int
     */
    private $typo3Version = 10004000;

    /**
     * @Assert\NotBlank()
     * @Assert\Choice({
     *     "bootstrap_package",
     *     "fluid_styled_content"
     * })
     *
     * @SWG\Property(type="string", example="bootstrap_package")
     * @Serializer\Type("string")
     * @var string
     */
    private $basePackage = 'bootstrap_package';

    /**
     * @var string
     */
    private $vendorName;

    /**
     * @var string
     */
    private $vendorNameAlternative;

    /**
     * @Assert\NotBlank(
     *     message="Please enter a title for your site package"
     * )
     * @Assert\Length(
     *     min = 3
     * )
     * @Assert\Regex(
     *     pattern = "/^[A-Za-z0-9\x7f-\xff .:&-]+$/",
     *     message = "Only letters, numbers and spaces are allowed"
     * )
     *
     * @SWG\Property(type="string", example="My Sitepackage")
     * @Serializer\Type("string")
     * @var string
     */
    private $title;

    /**
     * @Assert\Regex(
     *     pattern = "/^[A-Za-z0-9\x7f-\xff .,:!?&-]+$/",
     *     message = "Only letters, numbers and spaces are allowed"
     * )
     *
     * @SWG\Property(type="string", example="Project Configuration for Client")
     * @Serializer\Type("string")
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
     * @SWG\Property(type="string", example="https://github.com/benjaminkott/packagebuilder")
     *
     * @Serializer\Type("string")
     * @var string
     */
    private $repositoryUrl = '';

    /**
     * @Assert\Valid
     *
     * @Serializer\Type(Author::class)
     * @var Author
     */
    private $author;

    /**
     * @return int
     */
    public function getTypo3Version()
    {
        return $this->typo3Version;
    }

    /**
     * @param int $typo3Version
     * @return Package
     */
    public function setTypo3Version($typo3Version)
    {
        $this->typo3Version = $typo3Version;
        return $this;
    }

    /**
     * @return string
     */
    public function getBasePackage()
    {
        return $this->basePackage;
    }

    /**
     * @param string $basePackage
     * @return Package
     */
    public function setBasePackage($basePackage)
    {
        $this->basePackage = $basePackage;
        return $this;
    }

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

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'typo3Version' => $this->getTypo3Version(),
            'basePackage' => $this->getBasePackage(),
            'vendorName' => $this->getVendorName(),
            'vendorNameAlternative' => $this->getVendorNameAlternative(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'packageName' => $this->getPackageName(),
            'packageNameAlternative' => $this->getPackageNameAlternative(),
            'extensionKey' => $this->getExtensionKey(),
            'repositoryUrl' => $this->getRepositoryUrl(),
            'author' => $this->getAuthor(),
        ];
    }
}
