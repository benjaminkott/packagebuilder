<?php

namespace BK2K\Sitepackage\GeneratorBundle\Controller;

/*
 *  The MIT License (MIT)
 *
 *  Copyright (c) 2016 Benjamin Kott, http://www.bk2k.info
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is
 *  furnished to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in
 *  all copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 */

use BK2K\Sitepackage\GeneratorBundle\Entity\Package;
use BK2K\Sitepackage\GeneratorBundle\Type\PackageType;
use BK2K\Sitepackage\GeneratorBundle\Utility\StringUtility;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * DefaultController.
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
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

            echo "<pre>";
            var_dump($sitePackage);
            echo "</pre>";
            die();

            // Generator
            $generator = $this->get('sitepackage_generator.generator');
            $generator->create($sitePackage);
            $filename = $generator->getFilename();
            $response = new BinaryFileResponse($generator->getZipPath());
            $response->trustXSendfileTypeHeader();
            $response->setContentDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $filename,
                iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
            );
            $response->deleteFileAfterSend(true);
            return $response;
        } else {
            return $this->render(
                'SitepackageGeneratorBundle:Default:New.html.twig',
                array(
                    'form' => $form->createView(),
                )
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
