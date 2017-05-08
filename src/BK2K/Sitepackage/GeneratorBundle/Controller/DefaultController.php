<?php

/*
 * This file is part of the bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace BK2K\Sitepackage\GeneratorBundle\Controller;

use BK2K\Sitepackage\GeneratorBundle\Entity\Package;
use BK2K\Sitepackage\GeneratorBundle\Type\PackageType;
use BK2K\Sitepackage\GeneratorBundle\Utility\StringUtility;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * DefaultController
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render(
            'SitepackageGeneratorBundle:Default:Index.html.twig',
            [
                'author' => [
                    'name' => 'Benjamin Kott',
                    'email' => 'benjamin.kott@outlook.com',
                    'hash' => md5('benjamin.kott@outlook.com'),
                    'twitter' => 'benjaminkott',
                    'github' => 'benjaminkott',
                    'description' => 'Benjamin is Lead-Frontend-Developer at <a class="font-weight-bold" href="https://www.teamwfp.de" target="_blank">TeamWFP</a> and working projects based on TYPO3 CMS from mid- to enterprise size. Since 2014 his <a class="font-weight-bold" href="https://github.com/benjaminkott/bootstrap_package" target="_blank">Bootstrap Package</a> is used as codebase for the official TYPO3 CMS Introduction Package with the goal to provide an extensive best practice example on how to create websites efficiently with TYPO3 CMS.'
                ]
            ]
        );
    }

    /**
     * @Route("/new/", name="sp_new")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $sitePackage = new Package();
        $form = $this->createSitePackageForm($sitePackage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // VendorName
            $sitePackage->setVendorName(StringUtility::stringToUpperCamelCase($sitePackage->getAuthor()->getCompany()));
            $sitePackage->setVendorNameAlternative(StringUtility::camelCaseToLowerCaseDashed($sitePackage->getVendorName()));
            // PackageName
            $sitePackage->setPackageName(StringUtility::stringToUpperCamelCase($sitePackage->getTitle()));
            $sitePackage->setPackageNameAlternative(StringUtility::camelCaseToLowerCaseDashed($sitePackage->getPackageName()));
            // ExtensionKey
            $sitePackage->setExtensionKey(StringUtility::camelCaseToLowerCaseUnderscored($sitePackage->getPackageName()));

            // Generator
            $generator = $this->get('sitepackage_generator.generator');
            $generator->create($sitePackage);
            $filename = $generator->getFilename();
            $response = new BinaryFileResponse($generator->getZipPath());
            $response->trustXSendfileTypeHeader();
            $response->setContentDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $filename,
                StringUtility::toASCII($filename)
            );
            $response->deleteFileAfterSend(true);
            return $response;
        } else {
            return $this->render(
                'SitepackageGeneratorBundle:Default:New.html.twig',
                [
                    'form' => $form->createView(),
                ]
            );
        }
    }

    /**
     * @Route("/success/", name="sp_success")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function successAction()
    {
        return $this->render('SitepackageGeneratorBundle:Default:Success.html.twig');
    }

    /**
     * @Route("/imprint/", name="imprint")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function imprintAction()
    {
        return $this->render('SitepackageGeneratorBundle:Default:Imprint.html.twig');
    }

    /**
     * @Route("/privacy/", name="privacy")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function privacyAction()
    {
        return $this->render('SitepackageGeneratorBundle:Default:Privacy.html.twig');
    }

    /**
     * @param $sitepackage
     * @return \Symfony\Component\Form\Form
     */
    protected function createSitePackageForm(Package $sitepackage)
    {
        return $this->createForm(
            PackageType::class,
            $sitepackage,
            [
                'action' => $this->generateUrl('sp_new')
            ]
        )->add(
            'save',
            SubmitType::class,
            [
                'label' => 'Download Sitepackage',
                'attr' => [
                    'class' => 'btn-primary'
                ]
            ]
        );
    }
}
