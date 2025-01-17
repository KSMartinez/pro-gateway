<?php

namespace App\Entity;

use DateTimeInterface;
use Symfony\Component\HttpFoundation\File\File;

interface UploadPictureCompatibleInterface
{
    public function setFile(?File $imageFile): void;

    public function getFile(): ?File;

    public function setUpdatedAt(?DateTimeInterface $updatedAt): self;

    public function getUpdatedAt(): ?DateTimeInterface;

    public function getId(): ?int;

}