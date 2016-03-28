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

use BK2K\Sitepackage\GeneratorBundle\Entity\Sitepackage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
        return $this->render('SitepackageGeneratorBundle:Default:Index.html.twig');
    }

    /**
     * @Route("/new/", name="new")
     */
    public function newAction(Request $request)
    {
        $sitepackage = new Sitepackage();
        $form = $this->createFormBuilder($sitepackage)
            ->add('vendorName', TextType::class)
            ->add('extensionKey', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Download Sitepackage'))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $generator = $this->get('sitepackage_generator.generator');
            $generator->create($sitepackage);
            $filename = $generator->getFilename();

            $response = new BinaryFileResponse($filename);
            $response->prepare(Request::createFromGlobals());
            $response->deleteFileAfterSend(true);
            $response->send();
        }

        return $this->render(
            'SitepackageGeneratorBundle:Default:New.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }
}
