<?php declare(strict_types=1);

/*
 * This file is part of the package bk2k/packagebuilder.
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Controller\Api;

use App\Entity\Package;
use App\Service\SitepackageGenerator;
use App\Utility\StringUtility;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validation;

/**
 * @Route("/api/v1/sitepackage", defaults={"_format"="json"})
 */
class SitepackageController extends AbstractController
{
    /**
     * @var \JMS\Serializer\Serializer
     */
    protected $serializer;

    /**
     * @var SitepackageGenerator
     */
    protected $sitepackageGenerator;

    public function __construct(
        SerializerInterface $serializer,
        SitepackageGenerator $sitepackageGenerator
    ) {
        $this->serializer = $serializer;
        $this->sitepackageGenerator = $sitepackageGenerator;
    }

    /**
     * @Route("/", methods={"POST"})
     *
     * @OA\RequestBody(
     *     @Model(type=\App\Entity\Package::class)
     * )
     *
     * @OA\Response(
     *     response=200,
     *     description="Successfully generated.",
     *     @OA\Schema(type="file"),
     * )
     * @OA\Response(
     *     response=400,
     *     description="Request malformed."
     * )
     * @OA\Tag(name="sitepackage")
     */
    public function createSitepackage(Request $request): Response
    {
        $content = $request->getContent();
        $sitepackage = $this->serializer->deserialize($content, Package::class, 'json');
        $this->validateObject($sitepackage);

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

    /**
     * @param $object
     */
    protected function validateObject($object): void
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();
        $errors = $validator->validate($object);
        if (\count($errors) > 0) {
            $errorsString = (string) $errors;
            throw new BadRequestHttpException($errorsString);
        }
    }
}
