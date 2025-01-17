<?php

namespace App\Entity;

/**
 * @property string|null $imagePath
 * @property string|null $imageUrl
 */
interface ImageStockCompatibleInterface
{
    public function getImageStockId(): ?string;

    public function getImagePath(): ?string;

    public function setImagePath(?string $imagePath): self;

    public function getId(): ?int;
}