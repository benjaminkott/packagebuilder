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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * DefaultController
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     * @return array
     */
    public function indexAction()
    {
        return [
            'author' => [
                'name' => 'Benjamin Kott',
                'email' => 'benjamin.kott@outlook.com',
                'hash' => md5('benjamin.kott@outlook.com'),
                'twitter' => 'benjaminkott',
                'github' => 'benjaminkott',
                'description' => 'Benjamin is Lead-Frontend-Developer at <a class="font-weight-bold" href="https://www.teamwfp.de" target="_blank">TeamWFP</a> and working projects based on TYPO3 CMS from mid- to enterprise size. Since 2014 his <a class="font-weight-bold" href="https://github.com/benjaminkott/bootstrap_package" target="_blank">Bootstrap Package</a> is used as codebase for the official TYPO3 CMS Introduction Package with the goal to provide an extensive best practice example on how to create websites efficiently with TYPO3 CMS.'
            ]
        ];
    }

    /**
     * @Route("/new/", name="sp_new")
     * @Template()
     * @return Response|array
     */
    public function newAction(Request $request)
    {
        $session = $request->getSession();
        $session->set('sitepackage', null);
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

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/edit/", name="sp_edit")
     * @Template()
     * @return Response | array
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

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/success/", name="sp_success")
     * @Template()
     * @return Response|array
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

        return             [
            'sitepackage' => $sitepackage
        ];
    }

    /**
     * @Route("/download/", name="sp_download")
     * @return Response
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

        BinaryFileResponse::trustXSendfileTypeHeader();

        return $this
            ->file($generator->getZipPath(), StringUtility::toASCII($filename))
            ->deleteFileAfterSend(true);
    }

    /**
     * @Route("/imprint/", name="imprint")
     * @Template()
     * @return array
     */
    public function imprintAction()
    {
        return [];
    }

    /**
     * @Route("/privacy/", name="privacy")
     * @Template()
     * @return array
     */
    public function privacyAction()
    {
        return [];
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
     * @param Package $sitepackage
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
