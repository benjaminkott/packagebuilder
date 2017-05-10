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
        $session = $request->getSession();
        $sitepackage = $session->set('sitepackage', null);
        $sitepackage = new Package();
        $form = $this->createNewSitePackageForm($sitepackage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sitepackage->setVendorName(StringUtility::stringToUpperCamelCase($sitepackage->getAuthor()->getCompany()));
            $sitepackage->setVendorNameAlternative(StringUtility::camelCaseToLowerCaseDashed($sitepackage->getVendorName()));
            $sitepackage->setPackageName(StringUtility::stringToUpperCamelCase($sitepackage->getTitle()));
            $sitepackage->setPackageNameAlternative(StringUtility::camelCaseToLowerCaseDashed($sitepackage->getPackageName()));
            $sitepackage->setExtensionKey(StringUtility::camelCaseToLowerCaseUnderscored($sitepackage->getPackageName()));
            $session = $request->getSession();
            $session->set('sitepackage', $sitepackage);
            return $this->redirectToRoute('sp_success');
        }
        return $this->render(
            'SitepackageGeneratorBundle:Default:New.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/edit/", name="sp_edit")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request)
    {
        $session = $request->getSession();
        $sitepackage = $session->get('sitepackage');
        if ($sitepackage === null) {
            $this->addFlash(
                'danger',
                'Whoops, we could not find the package configuration. Please submit the configuration again.'
            );
            return $this->redirectToRoute('sp_new');
        }
        $form = $this->createEditSitePackageForm($sitepackage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sitepackage->setVendorName(StringUtility::stringToUpperCamelCase($sitepackage->getAuthor()->getCompany()));
            $sitepackage->setVendorNameAlternative(StringUtility::camelCaseToLowerCaseDashed($sitepackage->getVendorName()));
            $sitepackage->setPackageName(StringUtility::stringToUpperCamelCase($sitepackage->getTitle()));
            $sitepackage->setPackageNameAlternative(StringUtility::camelCaseToLowerCaseDashed($sitepackage->getPackageName()));
            $sitepackage->setExtensionKey(StringUtility::camelCaseToLowerCaseUnderscored($sitepackage->getPackageName()));
            $session = $request->getSession();
            $session->set('sitepackage', $sitepackage);
            return $this->redirectToRoute('sp_success');
        }
        return $this->render(
            'SitepackageGeneratorBundle:Default:Edit.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/success/", name="sp_success")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function successAction(Request $request)
    {
        $session = $request->getSession();
        $sitepackage = $session->get('sitepackage');
        if ($sitepackage === null) {
            $this->addFlash(
                'danger',
                'Whoops, we could not find the package configuration. Please submit the configuration again.'
            );
            return $this->redirectToRoute('sp_new');
        }
        return $this->render(
            'SitepackageGeneratorBundle:Default:Success.html.twig',
            [
                'sitepackage' => $sitepackage
            ]
        );
    }

    /**
     * @Route("/download/", name="sp_download")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadAction(Request $request)
    {
        $session = $request->getSession();
        $sitepackage = $session->get('sitepackage');
        if ($sitepackage === null) {
            $this->addFlash(
                'danger',
                'Whoops, we could not find the package configuration. Please submit the configuration again.'
            );
            return $this->redirectToRoute('sp_new');
        }
        $generator = $this->get('sitepackage_generator.generator');
        $generator->create($sitepackage);
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
    protected function createNewSitePackageForm(Package $sitepackage)
    {
        return $this->createForm(
            PackageType::class,
            $sitepackage,
            ['action' => $this->generateUrl('sp_new')]
        )->add(
            'save',
            SubmitType::class,
            [
                'label' => 'Create Sitepackage',
                'icon' => 'floppy-disk',
                'attr' => ['class' => 'btn-primary']
            ]
        );
    }

    /**
     * @param $sitepackage
     * @return \Symfony\Component\Form\Form
     */
    protected function createEditSitePackageForm(Package $sitepackage)
    {
        return $this->createForm(
            PackageType::class,
            $sitepackage,
            ['action' => $this->generateUrl('sp_edit')]
        )->add(
            'save',
            SubmitType::class,
            [
                'label' => 'Update Sitepackage',
                'icon' => 'floppy-disk',
                'attr' => ['class' => 'btn-primary']
            ]
        );
    }
}
