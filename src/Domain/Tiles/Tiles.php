<?php

declare(strict_types = 1);

namespace App\Domain\Tiles;

final class Tiles implements \IteratorAggregate, \Countable, \JsonSerializable
{
    private array $tiles = [];

    private function __construct(array $tiles = [])
    {
        foreach ($tiles as $tile) {
            $this->put($tile);
        }
    }

    public static function init(int $width, int $height): self
    {
        $tiles = self::fromArray();

        foreach (range(1, $height) as $y) {
            foreach (range(1, $width) as $x) {
                $tiles->put(Tile::obj($x, $y));
            }
        }

        return $tiles;
    }

    public static function fromArray(array $tiles = []): self
    {
        return new self($tiles);
    }

    public function put(Tile $tile): self
    {
        if (!$tile->isNull()) {
            $this->tiles[$tile->position()->label()] = $tile;
        }

        return $this;
    }

    public function position(Tile $tile): Tile
    {
        return $this->tile($tile->position()->x(), $tile->position()->y());
    }

    public function tile(int $x, int $y): Tile
    {
        return $this->tiles["$x.$y"] ?? Tile::obj(0, 0);
    }

    public function bombs(): self
    {
        return $this->filter(fn(Tile $tile) => $tile->isBomb());
    }

    public function numbers(): self
    {
        return $this->filter(fn(Tile $tile) => $tile->isNumber());
    }

    public function safe(): self
    {
        return $this->filter(fn(Tile $tile) => $tile->isSafe());
    }

    public function flags(): self
    {
        return $this->filter(fn(Tile $tile) => $tile->hasFlag());
    }

    public function open(): self
    {
        return $this->filter(fn(Tile $tile) => $tile->isOpen());
    }

    public function merge(self $tiles): self
    {
        foreach ($tiles as $tile) {
            $this->put($tile);
        }

        return $this;
    }

    public function has(Tile $tile): bool
    {
        return isset($this->tiles[$tile->position()->label()]);
    }

    public function safeArea(Tile $tile): self
    {
        $result = self::fromArray();
        $tmp    = [$this->around($tile)];

        while (count($tmp) > 0) {
            /** @var Tile $item */
            foreach (array_pop($tmp) as $item) {
                if ($result->has($item)) {
                    continue;
                }

                if ($item->isNumber()) {
                    $result->put($item);
                }

                if ($item->isSafe()) {
                    $result->put($item);

                    $tmp[] = $this->around($item);
                }
            }
        }

        return $result;
    }

    public function withAllOpen(): self
    {
        return $this->each(fn(Tile $tile) => $tile->open());
    }

    public function withAllClosed(): self
    {
        return $this->each(fn(Tile $tile) => $tile->close());
    }

    public function each(callable $callback): self
    {
        return new self(array_map(fn(Tile $tile) => $callback($tile), $this->tiles));
    }

    public function filter(callable $callback): self
    {
        return new self(array_filter(array_values($this->tiles), fn($tile) => $callback($tile)));
    }

    public function samePositions(self $tiles): bool
    {
        if ($this->count() !== $tiles->count()) {
            return false;
        }

        /** @var Tile $tile */
        foreach ($this->tiles as $tile) {
            if ($tiles->position($tile)->isNull()) {
                return false;
            }
        }

        return true;
    }

    public function withNumbers(): self
    {
        return $this->each(fn(Tile $tile) => $tile->withValue(
            $this->around($tile)->bombs()->count()
        ));
    }

    public function around(Tile $tile): Tiles
    {
        $x = $tile->position()->x();
        $y = $tile->position()->y();

        return self::fromArray([
            $this->tile($x - 1, $y - 1),
            $this->tile($x, $y - 1),
            $this->tile($x + 1, $y - 1),
            $this->tile($x - 1, $y),
            $this->tile($x + 1, $y),
            $this->tile($x - 1, $y + 1),
            $this->tile($x, $y + 1),
            $this->tile($x + 1, $y + 1)
        ]);
    }

    public static function fromJson(array $data): self
    {
        return self::fromArray(array_map(
            fn($tile) => Tile::fromJson($tile),
            array_map('json_decode', $data)
        ));
    }

    public function jsonSerialize(): array
    {
        return array_map(fn(Tile $tile) => $tile->toJson(), $this->tiles);
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->tiles);
    }

    public function count(): int
    {
        return count($this->tiles);
    }
}