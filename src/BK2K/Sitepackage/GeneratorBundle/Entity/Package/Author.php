<?php

/*
 * This file is part of the bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace BK2K\Sitepackage\GeneratorBundle\Entity\Package;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Author
 */
class Author
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min = 3
     * )
     * @var string
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     * )
     * @var string
     */
    private $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min = 3
     * )
     * @Assert\Regex(
     *     pattern = "/^[A-Za-z0-9\x7f-\xff .:&-]+$/",
     *     message = "Only letters, numbers and spaces are allowed"
     * )
     * @var string
     */
    private $company;

    /**
     * @Assert\NotBlank()
     * @Assert\Url()
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
     * @return Author
     */
    public function setHomepage($homepage)
    {
        $this->homepage = $homepage;
        return $this;
    }
}
