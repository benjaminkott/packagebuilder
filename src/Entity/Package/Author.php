<?php declare(strict_types=1);

/*
 * This file is part of the package bk2k/packagebuilder.
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Entity\Package;

use JMS\Serializer\Annotation as Serializer;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Author.
 */
class Author implements \JsonSerializable
{
    /**
     * @Assert\NotBlank(message="Please enter the authors' name.")
     * @Assert\Length(
     *     min = 3
     * )
     * @OA\Property(type="string", example="Benjamin Kott")
     * @Serializer\Type("string")
     *
     * @var string
     */
    private $name;

    /**
     * @Assert\NotBlank(message="Please enter the authors' email address.")
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     * )
     * @OA\Property(type="string", example="contact@sitepackagebuilder.com")
     * @Serializer\Type("string")
     *
     * @var string
     */
    private $email;

    /**
     * @Assert\NotBlank(message="Please enter the authors' company.")
     * @Assert\Length(
     *     min = 3
     * )
     * @Assert\Regex(
     *     pattern = "/^[A-Za-z0-9\x7f-\xff .:&-]+$/",
     *     message = "Only letters, numbers and spaces are allowed"
     * )
     * @OA\Property(type="string", example="BK2K")
     * @Serializer\Type("string")
     *
     * @var string
     */
    private $company;

    /**
     * @Assert\NotBlank(message="Please enter the authors' homepage URL.")
     * @Assert\Url()
     * @OA\Property(type="string", example="https://www.sitepackagebuilder.com")
     * @Serializer\Type("string")
     *
     * @var string
     */
    private $homepage;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Author
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return Author
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $company
     *
     * @return Author
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return string
     */
    public function getHomepage()
    {
        return $this->homepage;
    }

    /**
     * @param string $homepage
     *
     * @return Author
     */
    public function setHomepage($homepage)
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
