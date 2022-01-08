<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class File
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $fileName;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $fileSize;

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     *
     * @return File
     */
    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @return int
     */
    public function getFileSize(): int
    {
        return $this->fileSize;
    }

    /**
     * @param int $fileSize
     *
     * @return File
     */
    public function setFileSize(int $fileSize): self
    {
        $this->fileSize = $fileSize;

        return $this;
    }
}