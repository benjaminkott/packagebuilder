<?php

/*
 * This file is part of the bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace BK2K\Sitepackage\GeneratorBundle\Service\Generator;

use BK2K\Sitepackage\GeneratorBundle\Entity\Package;
use BK2K\Sitepackage\GeneratorBundle\Utility\FileUtility;

/**
 * SitepackageGenerator
 */
class SitepackageGenerator
{
    const SKELETON_NAME = 'skeleton';

    /**
     * @var string
     */
    protected $zipPath;

    /**
     * @var string
     */
    protected $filename;

    /**
     * @param Package $package
     */
    public function create(Package $package = null)
    {
        $extensionKey = $package->getExtensionKey();
        $this->filename = $extensionKey . '.zip';
        $source_dir = __DIR__ . '/../../Resources/skeletons/BaseExtension/';
        $this->zipPath = tempnam(sys_get_temp_dir(), $this->filename);
        $fileList = FileUtility::listDirectory($source_dir);

        $zipFile = new \ZipArchive();
        $opened = $zipFile->open($this->zipPath, \ZipArchive::CREATE);
        if ($opened === true) {
            foreach ($fileList as $file) {
                if ($file !== $this->zipPath && file_exists($file)) {
                    $baseFileName = $this->replaceSkeletonNameInPath($file, $source_dir);
                    if (is_dir($file)) {
                        $zipFile->addEmptyDir($baseFileName);
                    } elseif (!$this->isTwigFile($file)) {
                        $zipFile->addFile($file, $baseFileName);
                    } else {
                        $content = $this->getFileContent($file, $package);
                        $nameInZip = $this->removeTwigExtension($baseFileName);
                        $zipFile->addFromString($nameInZip, $content);
                    }
                }
            }
            $zipFile->close();
        }
    }

    /**
     * @return string
     */
    public function getZipPath()
    {
        return $this->zipPath;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $file
     * @param Package $package
     * @return string
     */
    private function getFileContent($file, Package $package)
    {
        $content = file_get_contents($file);
        $fileUniqueId = uniqid('file');
        $twig = new \Twig_Environment(new \Twig_Loader_Array([$fileUniqueId => $content]));
        $rendered = $twig->render(
            $fileUniqueId,
            [
                'package' => $package,
                'timestamp' => time()
            ]
        );
        return $rendered;
    }

    /**
     * @param string $file
     * @return bool
     */
    private function isTwigFile($file)
    {
        $pathinfo = pathinfo($file);
        return $pathinfo['extension'] == 'twig';
    }

    /**
     * @param string $file
     * @param string $sourceDir
     * @return mixed
     */
    protected function createRelativeFilePath($file, $sourceDir)
    {
        return substr($file, strlen($sourceDir));
    }

    /**
     * @param string $file
     * @param string $sourceDir
     * @return mixed
     */
    protected function replaceSkeletonNameInPath($file, $sourceDir)
    {
        return str_replace(
            self::SKELETON_NAME . '/',
            '',
            $this->createRelativeFilePath($file, $sourceDir)
        );
    }

    /**
     * @param string $baseFileName
     * @return mixed
     */
    protected function removeTwigExtension($baseFileName)
    {
        return substr($baseFileName, 0, -5);
    }
}
