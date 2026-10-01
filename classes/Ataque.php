<?php

class Ataque
{
    private int $id;
    private int $personagemId;
    private string $nome;
    private int $dano;
    
    public function __construct(
        int $id,
        int $personagemId,
        string $nome,
        int $dano
    ) {
        $this->id = $id;
        $this->personagemId = $personagemId;
        $this->nome = $nome;
        $this->dano = $dano;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPersonagemId(): int
    {
        return $this->personagemId;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getDano(): int
    {
        return $this->dano;
    }

    public function executar(Personagem $atacante, Personagem $defensor): int
    {
        $danoFinal = max(1, $this->dano + random_int(-3, 3));

        $defensor->receberDano($danoFinal);
        
        return $danoFinal;
    }
}
