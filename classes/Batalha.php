<?php

require_once __DIR__ . '/Personagem.php';
require_once __DIR__ . '/Ataque.php';
require_once __DIR__ . '/Equipe.php';

class Batalha
{
    private Equipe $equipeJogador;
    private Equipe $equipeInimiga;
    private int $turno;

    public function __construct(
        Equipe $equipeJogador,
        Equipe $equipeInimiga
    ) {
        $this->equipeJogador = $equipeJogador;
        $this->equipeInimiga = $equipeInimiga;
        $this->turno = 0;
    }

    public function getEquipeJogador(): Equipe
    {
        return $this->equipeJogador;
    }

    public function getEquipeInimiga(): Equipe
    {
        return $this->equipeInimiga;
    }

    public function getTurno(): int
    {
        return $this->turno;
    }

    public function setTurno(int $turno): void
    {
        $this->turno = $turno;
    }

    public function getPersonagemAtual(): ?Personagem
    {
        $personagensJogador = $this->equipeJogador->getPersonagens();
        $personagensInimigos = $this->equipeInimiga->getPersonagens();

        if ($this->turno <= 2) {
            return $personagensJogador[$this->turno] ?? null;
        }

        $indiceInimigo = $this->turno - 3;

        return $personagensInimigos[$indiceInimigo] ?? null;
    }

    public function executarAtaque(Ataque $ataque): ?int
    {
        $atacante = $this->getPersonagemAtual();

        if ($atacante === null || !$atacante->estaVivo()) {
            $this->proximoTurno();
            return null;
        }

        if ($this->turno <= 2) {
            $alvo = $this->equipeInimiga->getPrimeiroVivo();
        } else {
            $alvo = $this->equipeJogador->getPrimeiroVivo();
        }

        if ($alvo === null) {
            return null;
        }

        $dano = $ataque->executar($atacante, $alvo);

        $this->proximoTurno();

        return $dano;
    }

    private function proximoTurno(): void
    {
        $tentativas = 0;

        do {
            $this->turno++;

            if ($this->turno > 5) {
                $this->turno = 0;
            }

            $tentativas++;

            $personagem = $this->getPersonagemAtual();

            if ($personagem !== null && $personagem->estaVivo()) {
                return;
            }
        } while ($tentativas < 6);
    }

    public function terminou(): bool
    {
        return !$this->equipeJogador->temVivos()
            || !$this->equipeInimiga->temVivos();
    }

    public function jogadorVenceu(): bool
    {
        return !$this->equipeInimiga->temVivos();
    }

    public function inimigoVenceu(): bool
    {
        return !$this->equipeJogador->temVivos();
    }

    public function executarTurnoInimigo(): ?int
{
    $inimigo = $this->getPersonagemAtual();

    if ($inimigo === null || !$inimigo->estaVivo()) {
        return null;
    }

    // Garante que estamos realmente no turno de um inimigo
    if ($this->turno < 3) {
        return null;
    }

    $alvo = $this->equipeJogador->getPrimeiroVivo();

    if ($alvo === null) {
        return null;
    }

    $ataques = $inimigo->getAtaques();

    if (empty($ataques)) {
        return null;
    }

    // Escolhe um ataque aleatório
    $ataque = $ataques[array_rand($ataques)];

    $dano = $ataque->executar(
        $inimigo,
        $alvo
    );

    $this->proximoTurno();

    return $dano;
}
}
