<?php declare(strict_types=1);

/*
 * This file is part of the package bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Entity\Package;

use JMS\Serializer\Annotation as Serializer;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Author.
 */
class Author implements \JsonSerializable
{
    #[Assert\NotBlank(message: "Please enter the authors' name.")]
    #[Assert\Length(min: 3)]
    #[Serializer\Type('string')]
    #[OA\Property(type: 'string', example: 'Benjamin Kott')]
    private string $name;

    #[Assert\NotBlank(message: "Please enter the authors' email address.")]
    #[Assert\Email(message: "The email '{{ value }}' is not a valid email.")]
    #[Serializer\Type('string')]
    #[OA\Property(type: 'string', example: 'contact@sitepackagebuilder.com')]
    private string $email;

    #[Assert\NotBlank(message: "Please enter the authors' company.")]
    #[Assert\Length(min: 3)]
    #[Assert\Regex(pattern: '/^[A-Za-z0-9\x7f-\xff .:&-]+$/', message: 'Only letters, numbers and spaces are allowed')]
    #[Serializer\Type('string')]
    #[OA\Property(type: 'string', example: 'BK2K')]
    private string $company;

    #[Assert\NotBlank(message: "Please enter the authors' homepage URL.")]
    #[Assert\Url]
    #[Serializer\Type('string')]
    #[OA\Property(type: 'string', example: 'https://www.sitepackagebuilder.com')]
    private string $homepage;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getHomepage(): string
    {
        return $this->homepage;
    }

    public function setHomepage(string $homepage): self
    {
        $this->homepage = $homepage;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'company' => $this->getCompany(),
            'homepage' => $this->getHomepage(),
        ];
    }
}
