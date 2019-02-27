<?php

/*
 * This file is part of the bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Controller\Web;

use App\Service\DocumentationGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * DocumentationController
 */
class DocumentationController extends Controller
{
    /**
     * @Route("/docs")
     * @Route("/docs/{pathname}", name="documentation_index", requirements={"pathname"=".+"})
     */
    public function indexAction(
        Request $request,
        DocumentationGenerator $documentationGenerator,
        $pathname = null
    ): Response {
        $tree = $documentationGenerator->getTree();
        $file = $documentationGenerator->findPathInTree($pathname, $tree);
        $html = $documentationGenerator->renderFile($file);

        return $this->render(
            'documentation/index.html.twig',
            [
                'tree' => $tree,
                'file' => $file,
                'pathname' => $pathname,
                'html' => $html,
            ]
        );
    }
}
