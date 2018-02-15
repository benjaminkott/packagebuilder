<?php

/*
 * This file is part of the bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Service;

use App\Entity\Package;
use App\Utility\FileUtility;

/**
 * SitepackageGenerator
 */
class SitepackageGenerator
{
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
    public function create(Package $package)
    {
        $extensionKey = $package->getExtensionKey();
        $this->filename = $extensionKey . '.zip';
        $sourceDir = __DIR__ . '/../Resources/skeletons/BaseExtension/' . $package->getBasePackage() . '/';
        $this->zipPath = tempnam(sys_get_temp_dir(), $this->filename);
        $fileList = FileUtility::listDirectory($sourceDir);

        $zipFile = new \ZipArchive();
        $opened = $zipFile->open($this->zipPath, \ZipArchive::CREATE);
        if ($opened === true) {
            foreach ($fileList as $file) {
                if ($file !== $this->zipPath && file_exists($file)) {
                    $baseFileName = $this->createRelativeFilePath($file, $sourceDir);
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
        return $pathinfo['extension'] === 'twig';
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
     * @param string $baseFileName
     * @return mixed
     */
    protected function removeTwigExtension($baseFileName)
    {
        return substr($baseFileName, 0, -5);
    }
}
