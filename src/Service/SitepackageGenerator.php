<?php declare(strict_types=1);

/*
 * This file is part of the package bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Service;

use App\Entity\Package;
use App\Utility\FileUtility;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class SitepackageGenerator
{
    protected KernelInterface $kernel;
    protected string $zipPath;
    protected string $filename;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function create(Package $package): void
    {
        $extensionKey = $package->getExtensionKey();
        $this->filename = $extensionKey . '.zip';
        $sourceDir = $this->kernel->getProjectDir() . '/resources/packages/' . $package->getBasePackage() . '/' . (string) $package->getTypo3Version() . '/src/';
        $this->zipPath = tempnam(sys_get_temp_dir(), $this->filename);
        $fileList = FileUtility::listDirectory($sourceDir);

        $zipFile = new \ZipArchive();
        $opened = $zipFile->open($this->zipPath, \ZipArchive::CREATE);
        if (true === $opened) {
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

    public function getZipPath(): string
    {
        return $this->zipPath;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    private function getFileContent(string $file, Package $package): string
    {
        $content = file_get_contents($file);
        $fileUniqueId = uniqid('file');
        $twig = new Environment(new ArrayLoader([$fileUniqueId => $content]));
        $rendered = $twig->render(
            $fileUniqueId,
            [
                'package' => $package,
                'timestamp' => time(),
            ]
        );

        return $rendered;
    }

    private function isTwigFile(string $file): bool
    {
        $pathinfo = pathinfo($file);

        return 'twig' === $pathinfo['extension'];
    }

    protected function createRelativeFilePath(string $file, string $sourceDir): string
    {
        return substr($file, strlen($sourceDir));
    }

    protected function removeTwigExtension(string $baseFileName): string
    {
        return substr($baseFileName, 0, -5);
    }
}
