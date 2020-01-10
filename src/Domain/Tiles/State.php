<?php

declare(strict_types = 1);

namespace App\Domain\Tiles;

final class State implements \JsonSerializable
{
    private int  $value;
    private bool $open;
    private bool $flag;

    public function __construct(int $value, bool $open = false, bool $flag = false)
    {
        if ($open && $flag) {
            throw new \DomainException('Flag cannot be put on an opened tile');
        }

        $this->value = $value;
        $this->open  = $open;
        $this->flag  = $flag;
    }

    public function withValue(int $value): State
    {
        return new self($value, $this->open, $this->flag);
    }

    public function withOpen(): self
    {
        return new self($this->value, true, $this->flag);
    }

    public function withClose(): self
    {
        return new self($this->value, false, $this->flag);
    }

    public function withoutFlag(): self
    {
        return $this->open ? $this : new self($this->value, $this->open, false);
    }

    public function withFlag(): self
    {
        return $this->open ? $this : new self($this->value, $this->open, true);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function open(): bool
    {
        return $this->open;
    }

    public function flag(): bool
    {
        return $this->flag;
    }

    public static function fromJson(\stdClass $data): self
    {
        return new self(
            $data->state->value,
            $data->state->open,
            $data->state->flag
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'value' => $this->value,
            'open'  => $this->open,
            'flag'  => $this->flag
        ];
    }
}