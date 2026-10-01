<?php

class PersonagemRepository
{
    private PDO $pdo;
    private AtaqueRepository $ataqueRepository;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->ataqueRepository = new AtaqueRepository($pdo);
    }

    public function buscarPorId(int $id): ?Personagem
    {
        $sql = "SELECT id, nome, sprite, hp_max
                FROM personagem
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dados) {
            return null;
        }

        $personagem = new Personagem(
            (int) $dados['id'],
            $dados['nome'],
            $dados['sprite'],
            (int) $dados['hp_max']
        );

        $ataques = $this->ataqueRepository->buscarPorPersonagem(
            $personagem->getId()
        );

        foreach ($ataques as $ataque) {
            $personagem->adicionarAtaque($ataque);
        }

        return $personagem;
    }
}