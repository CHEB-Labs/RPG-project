<?php

class Personagem
{
    private int $id;
    private string $nome;
    private string $sprite;
    private int $hpMax;
    private int $hpAtual;

    private array $ataques = [];

    public function __construct(
        int $id,
        string $nome,
        string $sprite,
        int $hpMax
    ) {
        $this->id = $id;
        $this->nome = $nome;
        $this->sprite = $sprite;
        $this->hpMax = $hpMax;
        $this->hpAtual = $hpMax;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getSprite(): string
    {
        return $this->sprite;
    }

    public function getHpMax(): int
    {
        return $this->hpMax;
    }

    public function getHpAtual(): int
    {
        return $this->hpAtual;
    }

    public function receberDano(int $dano): void
    {
        $this->hpAtual -= $dano;

        if ($this->hpAtual < 0) {
            $this->hpAtual = 0;
        }
    }

    public function recuperarVida(int $quantidade): void
    {
        $this->hpAtual += $quantidade;

        if ($this->hpAtual > $this->hpMax) {
            $this->hpAtual = $this->hpMax;
        }
    }

    public function estaVivo(): bool
    {
        return $this->hpAtual > 0;
    }

    public function adicionarAtaque(Ataque $ataque): void
    {
        $this->ataques[] = $ataque;
    }

    public function getAtaques(): array
    {
        return $this->ataques;
    }
}