<?php

/*
 * This file is part of the bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Controller\Api;

use App\Entity\Package;
use App\Entity\Package\Author;
use App\Service\SitepackageGenerator;
use App\Utility\StringUtility;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validation;

/**
 * @Rest\RouteResource("sitepackage", pluralize=false)
 * @Rest\NamePrefix("api_")
 */
class SitepackageController extends FOSRestController
{
    /**
     * @var SitepackageGenerator
     */
    protected $sitepackageGenerator;

    public function __construct(
        SitepackageGenerator $sitepackageGenerator
    ) {
        $this->sitepackageGenerator = $sitepackageGenerator;
    }

    public function postAction(Request $request): Response
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

        $sitepackage = new Package();
        if (isset($data['typo3Version'])) {
            $sitepackage->setTypo3Version($data['typo3Version']);
        }
        if (isset($data['title'])) {
            $sitepackage->setTitle($data['title']);
        }
        if (isset($data['description'])) {
            $sitepackage->setDescription($data['title']);
        }
        if (isset($data['repositoryUrl'])) {
            $sitepackage->setRepositoryUrl($data['repositoryUrl']);
        }
        $author = new Author();
        if (isset($data['author']['name'])) {
            $author->setName($data['author']['name']);
        }
        if (isset($data['author']['email'])) {
            $author->setEmail($data['author']['email']);
        }
        if (isset($data['author']['company'])) {
            $author->setCompany($data['author']['company']);
        }
        if (isset($data['author']['homepage'])) {
            $author->setHomepage($data['author']['homepage']);
        }
        $sitepackage->setAuthor($author);

        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();
        $violations = $validator->validate($sitepackage);

        if ($violations->count() != 0) {
            $view = $this->view($violations, Response::HTTP_BAD_REQUEST);
            return $this->handleView($view);
        }

        $sitepackage->setVendorName(StringUtility::stringToUpperCamelCase($sitepackage->getAuthor()->getCompany()));
        $sitepackage->setVendorNameAlternative(StringUtility::camelCaseToLowerCaseDashed($sitepackage->getVendorName()));
        $sitepackage->setPackageName(StringUtility::stringToUpperCamelCase($sitepackage->getTitle()));
        $sitepackage->setPackageNameAlternative(StringUtility::camelCaseToLowerCaseDashed($sitepackage->getPackageName()));
        $sitepackage->setExtensionKey(StringUtility::camelCaseToLowerCaseUnderscored($sitepackage->getPackageName()));

        $this->sitepackageGenerator->create($sitepackage);
        $filename = $this->sitepackageGenerator->getFilename();
        BinaryFileResponse::trustXSendfileTypeHeader();

        return $this
            ->file($this->sitepackageGenerator->getZipPath(), StringUtility::toASCII($filename))
            ->deleteFileAfterSend(true);
    }
}
