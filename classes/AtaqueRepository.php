<?php

class AtaqueRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function buscarPorPersonagem(int $personagemId): array
    {
        $sql = "SELECT id, personagem_id, nome, dano
                FROM ataque
                WHERE personagem_id = :personagem_id
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'personagem_id' => $personagemId
        ]);

        $ataques = [];

        while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ataques[] = new Ataque(
                (int) $dados['id'],
                (int) $dados['personagem_id'],
                $dados['nome'],
                (int) $dados['dano']
            );
        }

        return $ataques;
    }
}
