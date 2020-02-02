<?php

/*
 * This file is part of the bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Controller\Web;

use App\Entity\Package;
use App\Form\PackageType;
use App\Service\SitepackageGenerator;
use App\Utility\StringUtility;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
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
     * @Route("/", name="default_index")
     */
    public function index(): Response
    {
        return $this->render(
            'default/index.html.twig',
            [
                'author' => [
                    'name' => 'Benjamin Kott',
                    'email' => 'benjamin.kott@outlook.com',
                    'hash' => md5('benjamin.kott@outlook.com'),
                    'twitter' => 'benjaminkott',
                    'github' => 'benjaminkott',
                    'description' => 'Benjamin is Frontend-Developer at <a class="font-weight-bold" href="https://www.typo3.com" target="_blank">TYPO3</a> and worked on projects based on TYPO3 CMS from mid- to enterprise size. Since 2014 his <a class="font-weight-bold" href="https://github.com/benjaminkott/bootstrap_package" target="_blank">Bootstrap Package</a> is used as codebase for the official TYPO3 CMS Introduction Package with the goal to provide an extensive best practice example on how to create websites efficiently with TYPO3 CMS.'
                ]
            ]
        );
    }

    /**
     * @Route("/new/", name="default_new")
     */
    public function new(Request $request)
    {
        $session = $request->getSession();
        $session->set('sitepackage', null);

        $sitepackage = new Package();
        $form = $this->createNewSitePackageForm($sitepackage);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->get('extended')->isClicked()) {
                $sitepackage->setExtended(true);
            }

            if ($sitepackage->getExtended()) {
                $session->set('sitepackage', $sitepackage);
                return $this->redirectToRoute('default_edit');
            } elseif ($form->isValid()) {
                $session->set('sitepackage', $sitepackage);
                return $this->redirectToRoute('default_success');
            }
        }

        return $this->render(
            'default/new.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/edit/", name="default_edit")
     */
    public function edit(Request $request)
    {
        $session = $request->getSession();
        $sitepackage = $session->get('sitepackage');

        if ($sitepackage === null) {
            $this->addFlash(
                'danger',
                'Whoops, we could not find the package configuration. Please submit the configuration again.'
            );
            return $this->redirectToRoute('default_new');
        }

        $form = $this->createEditSitePackageForm($sitepackage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*
            $sitepackage->setVendorName(StringUtility::stringToUpperCamelCase($sitepackage->getAuthor()->getCompany()));
            $sitepackage->setVendorNameAlternative(StringUtility::camelCaseToLowerCaseDashed($sitepackage->getVendorName()));
            $sitepackage->setPackageName(StringUtility::stringToUpperCamelCase($sitepackage->getTitle()));
            $sitepackage->setPackageNameAlternative(StringUtility::camelCaseToLowerCaseDashed($sitepackage->getPackageName()));
            $sitepackage->setExtensionKey(StringUtility::camelCaseToLowerCaseUnderscored($sitepackage->getPackageName()));
            $session = $request->getSession();
            $session->set('sitepackage', $sitepackage);
            */

            return $this->redirectToRoute('default_success');
        }

        return $this->render(
            'default/edit.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/success/", name="default_success")
     */
    public function success(Request $request)
    {
        $session = $request->getSession();
        $sitepackage = $session->get('sitepackage');
        if ($sitepackage === null) {
            $this->addFlash(
                'danger',
                'Whoops, we could not find the package configuration. Please submit the configuration again.'
            );
            return $this->redirectToRoute('default_new');
        }

        return $this->render(
            'default/success.html.twig',
            [
                'sitepackage' => $sitepackage
            ]
        );
    }

    /**
     * @Route("/download/", name="default_download")
     */
    public function download(Request $request, SitepackageGenerator $sitepackageGenerator)
    {
        $session = $request->getSession();
        $sitepackage = $session->get('sitepackage');
        if ($sitepackage === null) {
            $this->addFlash(
                'danger',
                'Whoops, we could not find the package configuration. Please submit the configuration again.'
            );
            return $this->redirectToRoute('default_new');
        }
        $sitepackageGenerator->create($sitepackage);
        $filename = $sitepackageGenerator->getFilename();

        BinaryFileResponse::trustXSendfileTypeHeader();

        return $this
            ->file($sitepackageGenerator->getZipPath(), StringUtility::toASCII($filename))
            ->deleteFileAfterSend(true);
    }

    /**
     * @Route("/imprint/", name="default_imprint")
     */
    public function imprintAction()
    {
        return $this->render('default/imprint.html.twig', []);
    }

    /**
     * @Route("/privacy/", name="default_privacy")
     */
    public function privacyAction()
    {
        return $this->render('default/privacy.html.twig', []);
    }

    /**
     * @param Package $sitepackage
     * @return FormInterface
     */
    protected function createNewSitePackageForm(Package $sitepackage)
    {
        return $this->createForm(
            PackageType::class,
            $sitepackage,
            ['action' => $this->generateUrl('default_new')]
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
     * @return FormInterface
     */
    protected function createEditSitePackageForm(Package $sitepackage)
    {
        return $this->createForm(
            PackageType::class,
            $sitepackage,
            ['action' => $this->generateUrl('default_edit')]
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
