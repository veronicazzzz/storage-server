<?php

namespace App\Service;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileService
{
    /**
     * @var string
     */
    private $targetDirectory;
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * FileService constructor.
     *
     * @param $targetDirectory
     * @param Filesystem $filesystem
     */
    public function __construct($targetDirectory, Filesystem $filesystem)
    {
        $this->targetDirectory = $targetDirectory;
        $this->filesystem = $filesystem;
    }

    /**
     * @param UploadedFile $file
     *
     * @return string
     */
    public function upload(UploadedFile $file): string
    {
        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName         = $originalFileName.'-'.uniqid().'.'.$file->guessExtension();

        $file->move($this->getTargetDirectory(), $fileName);

        return $fileName;
    }

    public function remove(string $fileName): void
    {
        $fileFullPath = $this->getTargetDirectory() . '/' . $fileName;

        $isFileExist = $this->filesystem->exists($fileFullPath);

        if (!$isFileExist) {
            throw new FileNotFoundException();
        }

        $this->filesystem->remove($fileFullPath);
    }

    /**
     * @return string
     */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}