<?php

declare(strict_types = 1);

namespace App\Domain\Board;

use App\Domain\Tiles\Tiles;

final class Board implements \JsonSerializable
{
    private Width    $width;
    private Height   $height;
    private Tiles    $tiles;

    public function __construct(Width $width, Height $height, Tiles $tiles)
    {
        if ($tiles->count() > $width->multiply($height)) {
            throw new \DomainException("There cannot be more tiles than board's size");
        }

        $this->width  = $width;
        $this->height = $height;
        $this->tiles  = $tiles;
    }

    public static function empty(int $width, int $height): self
    {
        return new self(new Width($width), new Height($height), Tiles::init($width, $height));
    }

    public function width(): Width
    {
        return $this->width;
    }

    public function height(): Height
    {
        return $this->height;
    }

    public function tiles(): Tiles
    {
        return $this->tiles;
    }

    public static function fromJson(\stdClass $data): self
    {
        return new self(
            new Width($data->width),
            new Height($data->height),
            Tiles::fromJson((array) $data->tiles)
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'width'  => $this->width->value(),
            'height' => $this->height->value(),
            'tiles'  => $this->tiles
        ];
    }
}