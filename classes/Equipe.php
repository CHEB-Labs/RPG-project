<?php

class Equipe
{
    private Personagem $principal;

    private array $mercenarios = [];

    public function __construct(Personagem $principal)
    {
        $this->principal = $principal;
    }

    public function adicionarMercenario(Personagem $mercenario): void
    {
        if (count($this->mercenarios) < 2) {
            $this->mercenarios[] = $mercenario;
        }
    }

    public function getPrincipal(): Personagem
    {
        return $this->principal;
    }

    public function getMercenarios(): array
    {
        return $this->mercenarios;
    }

    public function getPersonagens(): array
    {
        return array_merge(
            [$this->principal],
            $this->mercenarios
        );
    }

    public function getPrimeiroVivo(): ?Personagem
    {
        foreach ($this->getPersonagens() as $personagem) {
            if ($personagem->estaVivo()) {
                return $personagem;
            }
        }

        return null;
    }

    public function temVivos(): bool
    {
        return $this->getPrimeiroVivo() !== null;
    }
}