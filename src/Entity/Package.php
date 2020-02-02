<?php

/*
 * This file is part of the bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Entity;

use App\Entity\Package\Author;
use App\Utility\StringUtility;
use JMS\Serializer\Annotation as Serializer;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Package
 */
class Package implements \JsonSerializable
{
    /**
     * @var bool
     */
    private $extended = false;

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
     * @Assert\Length(
     *     allowEmptyString = true,
     *     min = 3
     * )
     * @Assert\Regex(
     *     pattern = "/^[A-Z][A-Za-z0-9]+$/",
     *     message = "Only letters, numbers and spaces are allowed"
     * )
     * @SWG\Property(type="string", example="BK2K", default="generated from author->company if empty")
     * @Serializer\Type("string")
     * @var string
     */
    private $vendorName = '';

    /**
     * @Assert\Length(
     *     allowEmptyString = true,
     *     min = 3
     * )
     * @Assert\Regex(
     *     pattern = "/^[a-z][a-z0-9-]+$/",
     *     message = "Only letters, numbers and hyphens are allowed"
     * )
     * @SWG\Property(type="string", example="bk2k", default="generated from vendor name if empty")
     * @Serializer\Type("string")
     * @var string
     */
    private $vendorNameAlternative = '';

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
     * @Assert\Length(
     *     allowEmptyString = true,
     *     min = 3
     * )
     * @Assert\Regex(
     *     pattern = "/^[A-Z][A-Za-z0-9]+$/",
     *     message = "Only letters and numbers are allowed"
     * )
     * @SWG\Property(type="string", example="MySitepackage", default="generated from title if empty")
     * @Serializer\Type("string")
     * @var string
     */
    private $packageName = '';

    /**
     * @Assert\Length(
     *     allowEmptyString = true,
     *     min = 3
     * )
     * @Assert\Regex(
     *     pattern = "/^[a-z][a-z0-9-]+$/",
     *     message = "Only lower case letters, numbers and hyphens are allowed"
     * )
     * @SWG\Property(type="string", example="my-sitepackage", default="generated from package name if empty")
     * @Serializer\Type("string")
     * @var string
     */
    private $packageNameAlternative = '';

    /**
     * @Assert\Length(
     *     allowEmptyString = true,
     *     min = 3
     * )
     * @Assert\Regex(
     *     pattern = "/^[a-z][a-z0-9_]+$/",
     *     message = "Only lower case letters, numbers and undscores are allowed"
     * )
     * @SWG\Property(type="string", example="my_sitepackage", default="generated from package name if empty")
     * @Serializer\Type("string")
     * @var string
     */
    private $extensionKey = '';

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
     * @Serializer\Type(Author::class)
     * @var Author
     */
    private $author;

    /**
     * @return bool
     */
    public function getExtended()
    {
        return $this->extended;
    }

    /**
     * @param bool $extended
     * @return Package
     */
    public function setExtended($extended)
    {
        $this->extended = $extended;
        return $this;
    }

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
        if (empty($this->vendorName)) {
            return StringUtility::stringToUpperCamelCase($this->getAuthor()->getCompany());
        }

        return $this->vendorName;
    }

    /**
     * @param string $vendorName
     * @return Package
     */
    public function setVendorName($vendorName)
    {
        if ($this->getVendorName() !== $vendorName) {
            $this->vendorName = StringUtility::stringToUpperCamelCase($vendorName);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getVendorNameAlternative()
    {
        if (empty($this->vendorNameAlternative)) {
            return StringUtility::camelCaseToLowerCaseDashed($this->getVendorName());
        }

        return $this->vendorNameAlternative;
    }

    /**
     * @param string $vendorNameAlternative
     * @return Package
     */
    public function setVendorNameAlternative($vendorNameAlternative)
    {
        if ($this->getVendorNameAlternative() !== $vendorNameAlternative) {
            $this->vendorNameAlternative = StringUtility::camelCaseToLowerCaseDashed($vendorNameAlternative);
        }
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
        if (empty($this->packageName)) {
            return StringUtility::stringToUpperCamelCase($this->getTitle());
        }

        return $this->packageName;
    }

    /**
     * @param string $packageName
     * @return Package
     */
    public function setPackageName($packageName)
    {
        if ($this->getPackageName() !== $packageName) {
            $this->packageName = StringUtility::stringToUpperCamelCase($packageName);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getPackageNameAlternative()
    {
        if (empty($this->packageNameAlternative)) {
            return StringUtility::camelCaseToLowerCaseDashed($this->getPackageName());
        }

        return $this->packageNameAlternative;
    }

    /**
     * @param string $packageNameAlternative
     * @return Package
     */
    public function setPackageNameAlternative($packageNameAlternative)
    {
        if ($this->getPackageNameAlternative() !== $packageNameAlternative) {
            $this->packageNameAlternative = StringUtility::camelCaseToLowerCaseDashed($packageNameAlternative);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getExtensionKey()
    {
        if (empty($this->extensionKey)) {
            return StringUtility::camelCaseToLowerCaseUnderscored($this->getPackageName());
        }

        return $this->extensionKey;
    }

    /**
     * @param string $extensionKey
     * @return Package
     */
    public function setExtensionKey($extensionKey)
    {
        if ($this->getExtensionKey() !== $extensionKey) {
            $this->extensionKey = StringUtility::camelCaseToLowerCaseUnderscored($extensionKey);
        }

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
