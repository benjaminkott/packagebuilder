<?php declare(strict_types=1);

/*
 * This file is part of the package bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Entity;

use App\Entity\Package\Author;
use JMS\Serializer\Annotation as Serializer;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Package.
 */
class Package implements \JsonSerializable
{
    #[Assert\NotBlank]
    #[Assert\Choice([12004000, 11005000, 10004000, 9005000, 8007000])]
    #[Serializer\Type('int')]
    #[OA\Property(type: 'int', example: '12004000')]
    private int $typo3Version = 12004000;

    #[Assert\NotBlank]
    #[Assert\Choice(['bootstrap_package', 'fluid_styled_content'])]
    #[Serializer\Type('string')]
    #[OA\Property(type: 'string', example: 'bootstrap_package')]
    private string $basePackage = 'bootstrap_package';

    private string $vendorName;

    private string $vendorNameAlternative;

    #[Assert\NotBlank(message: 'Please enter a title for your site package')]
    #[Assert\Length(min: 3)]
    #[Assert\Regex(pattern: '/^[A-Za-z0-9\x7f-\xff .:&-]+$/', message: 'Only letters, numbers and spaces are allowed')]
    #[Serializer\Type('string')]
    #[OA\Property(type: 'string', example: 'My Sitepackage')]
    private string $title;

    #[Assert\Regex(pattern: '/^[A-Za-z0-9\x7f-\xff .,:!?&-]+$/', message: 'Only letters, numbers and spaces are allowed')]
    #[Serializer\Type('string')]
    #[OA\Property(type: 'string', example: 'Project Configuration for Client')]
    private string $description;

    private string $packageName;
    private string $packageNameAlternative;
    private string $extensionKey;

    #[Assert\Url]
    #[Serializer\Type('string')]
    #[OA\Property(type: 'string', example: 'https://github.com/benjaminkott/packagebuilder')]
    private string $repositoryUrl = '';

    /**
     *
     * @var Author
     */
    #[Assert\Valid]
    #[Serializer\Type(Author::class)]
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
     * @return Package
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

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
