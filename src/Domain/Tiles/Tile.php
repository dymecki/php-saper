<?php

declare(strict_types = 1);

namespace App\Domain\Tiles;

final class Tile implements \JsonSerializable
{
    protected Position  $position;
    protected State     $state;

    public function __construct(Position $position, State $state)
    {
        $this->position = $position;
        $this->state    = $state;
    }

    public static function obj(int $x, int $y, int $value = 0): self
    {
        return new static(new Position($x, $y), new State($value));
    }

    public static function bomb(int $x, int $y): self
    {
        return self::obj($x, $y, -1);
    }

    public static function fromJson(\stdClass $data): self
    {
        return new static(
            new Position($data->position->x, $data->position->y),
            State::fromJson($data)
        );
    }

    public function position(): Position
    {
        if ($this->isNull()) {
            throw new \DomainException('NullTile cannot have a position');
        }

        return $this->position;
    }

    public function state(): State
    {
        return $this->state;
    }

    public function isBomb(): bool
    {
        return $this->state->value() === -1;
    }

    public function isNull(): bool
    {
        return $this->position->x() === 0 || $this->position->y() === 0;
    }

    public function hasFlag(): bool
    {
        return $this->state->flag();
    }

    public function putFlag(): self
    {
        return $this->isOpen() ? $this : new Tile($this->position, $this->state->withFlag());
    }

    public function removeFlag(): self
    {
        return $this->hasFlag() ? new static($this->position, $this->state->withoutFlag()) : $this;
    }

    public function isNumber(): bool
    {
        return $this->state->value() > 0;
    }

    public function isSafe(): bool
    {
        return $this->state->value() === 0;
    }

    public function isOpen(): bool
    {
        return $this->state->open();
    }

    public function isClosed(): bool
    {
        return !$this->isOpen();
    }

    public function open(): self
    {
        return new static($this->position, $this->state->withOpen());
    }

    public function close(): self
    {
        return new static($this->position, $this->state->withClose());
    }

//    public function type(): string
//    {
//        $types = [
//            'bomb'   => (int) $this->isBomb(),
//            'number' => (int) $this->isNumber(),
//            'safe'   => (int) $this->isSafe(),
//            'closed' => (int) $this->isClosed()
//        ];
//
//        return array_flip($types)[1];
//    }

    public function withValue(int $value): self
    {
        return $this->isBomb() ? $this : new self($this->position, $this->state->withValue($value));
    }

    public function toJson(): string
    {
        return json_encode($this, JSON_THROW_ON_ERROR, 512);
    }

    public function jsonSerialize(): array
    {
        return [
            'position' => $this->position,
            'state'    => $this->state
        ];
    }
}
