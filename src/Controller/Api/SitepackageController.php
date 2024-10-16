<?php declare(strict_types=1);

/*
 * This file is part of the package bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Controller\Api;

use App\Entity\Package;
use App\Service\SitepackageGenerator;
use App\Utility\StringUtility;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validation;

#[Route(path: '/api/v1/sitepackage', defaults: ['_format' => 'json'])]
class SitepackageController extends AbstractController
{
    public function __construct(
        protected SerializerInterface $serializer,
        protected SitepackageGenerator $sitepackageGenerator
    ) {
    }

    #[Route(path: '/', methods: ['POST'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: new Model(type: Package::class)))]
    #[OA\Response(response: 200, description: 'Successfully generated.', content: new OA\MediaType(mediaType: 'application/zip'))]
    #[OA\Response(response: 400, description: 'Request malformed.')]
    #[OA\Tag(name: 'sitepackage')]
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
            ->enableAttributeMapping()
            ->getValidator();
        $errors = $validator->validate($object);
        if (\count($errors) > 0) {
            $errorsString = (string) $errors;
            throw new BadRequestHttpException($errorsString);
        }
    }
}
