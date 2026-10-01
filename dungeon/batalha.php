<?php

session_start();

if (isset($_POST['reiniciar'])) {

    unset($_SESSION['batalha']);

    header('Location: batalha.php');

    exit;
}

require_once __DIR__ . '/../config/conexao.php';

require_once __DIR__ . '/../classes/Ataque.php';
require_once __DIR__ . '/../classes/AtaqueRepository.php';
require_once __DIR__ . '/../classes/Personagem.php';
require_once __DIR__ . '/../classes/PersonagemRepository.php';
require_once __DIR__ . '/../classes/Equipe.php';
require_once __DIR__ . '/../classes/Batalha.php';

$personagemRepository = new PersonagemRepository($pdo);

// ============================
// Personagens do jogador
// ============================

$convictus = $personagemRepository->buscarPorId(1);
$mercenario1 = $personagemRepository->buscarPorId(2);
$mercenario2 = $personagemRepository->buscarPorId(2);

// ============================
// Personagens inimigos
// ============================

$inimigo1 = $personagemRepository->buscarPorId(2);
$inimigo2 = $personagemRepository->buscarPorId(2);
$inimigo3 = $personagemRepository->buscarPorId(2);

// ============================
// Criar equipe do jogador
// ============================

$equipeJogador = new Equipe($convictus);

$equipeJogador->adicionarMercenario($mercenario1);
$equipeJogador->adicionarMercenario($mercenario2);

// ============================
// Criar equipe inimiga
// ============================

$equipeInimiga = new Equipe($inimigo1);

$equipeInimiga->adicionarMercenario($inimigo2);
$equipeInimiga->adicionarMercenario($inimigo3);

// ============================
// Criar batalha
// ============================

if (isset($_SESSION['batalha'])) {

    $dadosBatalha = $_SESSION['batalha'];

    $batalha = new Batalha(
        $equipeJogador,
        $equipeInimiga
    );

    $batalha->setTurno($dadosBatalha['turno']);

    // Recuperar HP dos personagens
    foreach ($equipeJogador->getPersonagens() as $indice => $personagem) {

        if (isset($dadosBatalha['jogador'][$indice])) {

            $hp = $dadosBatalha['jogador'][$indice];

            $personagem->receberDano(
                $personagem->getHpAtual() - $hp
            );
        }
    }

    foreach ($equipeInimiga->getPersonagens() as $indice => $personagem) {

        if (isset($dadosBatalha['inimigos'][$indice])) {

            $hp = $dadosBatalha['inimigos'][$indice];

            $personagem->receberDano(
                $personagem->getHpAtual() - $hp
            );
        }
    }
} else {

    $batalha = new Batalha(
        $equipeJogador,
        $equipeInimiga
    );
}

// ============================
// Processar ataque
// ============================

$mensagem = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $ataqueId = (int) ($_POST['ataque_id'] ?? 0);

    $personagemAtual = $batalha->getPersonagemAtual();

    if ($personagemAtual !== null) {

        foreach ($personagemAtual->getAtaques() as $ataque) {

            if ($ataque->getId() === $ataqueId) {

                $dano = $batalha->executarAtaque($ataque);

                $mensagem =
                    $personagemAtual->getNome()
                    . " causou "
                    . $dano
                    . " de dano!";

                $_SESSION['batalha'] = [
                    'turno' => $batalha->getTurno(),

                    'jogador' => array_map(
                        fn($personagem) => $personagem->getHpAtual(),
                        $equipeJogador->getPersonagens()
                    ),

                    'inimigos' => array_map(
                        fn($personagem) => $personagem->getHpAtual(),
                        $equipeInimiga->getPersonagens()
                    )
                ];

                break;
            }
        }
    }
}

// ============================
// Turno automático do inimigo
// ============================

$mensagem = null;

$personagemAtual = $batalha->getPersonagemAtual();

if (
    $personagemAtual !== null &&
    $batalha->getTurno() >= 3 &&
    $_SERVER['REQUEST_METHOD'] !== 'POST'
) {

    $dano = $batalha->executarTurnoInimigo();

    if ($dano !== null) {

        $mensagem =
            $personagemAtual->getNome()
            . " atacou e causou "
            . $dano
            . " de dano!";
    }

    $_SESSION['batalha'] = [
        'turno' => $batalha->getTurno(),

        'jogador' => array_map(
            fn($personagem) => $personagem->getHpAtual(),
            $equipeJogador->getPersonagens()
        ),

        'inimigos' => array_map(
            fn($personagem) => $personagem->getHpAtual(),
            $equipeInimiga->getPersonagens()
        )
    ];
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Convictus - Dungeon</title>

    <link rel="stylesheet" href="../css/dungeon.css">

</head>

<body>

    <main class="batalha">

        <!-- ========================= -->
        <!-- ÁREA PRINCIPAL DA BATALHA -->
        <!-- ========================= -->

        <section class="arena">

            <!-- ========================= -->
            <!-- INFORMAÇÕES SUPERIORES -->
            <!-- ========================= -->

            <div class="topo-arena">

                <!-- Vida do jogador -->

                <div class="vida-equipe vida-jogador">

                    <h3>Equipe</h3>

                    <div class="barras-equipe">

                        <?php foreach ($equipeJogador->getPersonagens() as $personagem): ?>

                            <div class="barra-personagem">

                                <span>
                                    <?= htmlspecialchars($personagem->getNome()) ?>
                                </span>

                                <div class="barra-hp">

                                    <div
                                        class="hp-atual"
                                        style="width: <?= ($personagem->getHpAtual() / $personagem->getHpMax()) * 100 ?>%;">
                                    </div>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                </div>


                <!-- Turno -->

                <div class="turno">

                    <span>Turno de:</span>

                    <strong>

                        <?php

                        if ($personagemAtual !== null) {
                            echo htmlspecialchars($personagemAtual->getNome());
                        } else {
                            echo "Nenhum";
                        }

                        ?>

                    </strong>

                </div>


                <!-- Vida dos inimigos -->

                <div class="vida-equipe vida-inimiga">

                    <h3>Inimigos</h3>

                    <div class="barras-equipe">

                        <?php foreach ($equipeInimiga->getPersonagens() as $personagem): ?>

                            <div class="barra-personagem">

                                <span>
                                    <?= htmlspecialchars($personagem->getNome()) ?>
                                </span>

                                <div class="barra-hp">

                                    <div
                                        class="hp-atual"
                                        style="width: <?= ($personagem->getHpAtual() / $personagem->getHpMax()) * 100 ?>%;">
                                    </div>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                </div>

            </div>


            <!-- ========================= -->
            <!-- PERSONAGENS -->
            <!-- ========================= -->

            <div class="personagens-arena">

                <!-- Jogador -->

                <div class="personagens-jogador">

                    <?php foreach ($equipeJogador->getPersonagens() as $personagem): ?>

                        <div class="personagem">

                            <img
                                src="../img/personagens/<?= htmlspecialchars($personagem->getSprite()) ?>"
                                alt="<?= htmlspecialchars($personagem->getNome()) ?>">

                        </div>

                    <?php endforeach; ?>

                </div>


                <!-- Inimigos -->

                <div class="personagens-inimigos">

                    <?php foreach ($equipeInimiga->getPersonagens() as $personagem): ?>

                        <div class="personagem">

                            <img
                                src="../img/inimigos/EnemyMercenary.png"
                                alt="<?= htmlspecialchars($personagem->getNome()) ?>">

                        </div>

                    <?php endforeach; ?>

                </div>

            </div>

        </section>



        <!-- ========================= -->
        <!-- PAINEL INFERIOR -->
        <!-- ========================= -->

        <section class="painel-batalha">

            <div class="informacoes">

                <h2>
                    Turno <?= $batalha->getTurno() ?>
                </h2>

                <p>
                    Personagem atual:

                    <strong>
                        <?php

                        $personagemAtual = $batalha->getPersonagemAtual();

                        if ($personagemAtual !== null) {
                            echo htmlspecialchars($personagemAtual->getNome());
                        } else {
                            echo "Nenhum";
                        }

                        ?>
                    </strong>
                </p>

            </div>


            <!-- ========================= -->
            <!-- ATAQUES -->
            <!-- ========================= -->

            <div class="ataques">

                <?php if ($personagemAtual !== null && $personagemAtual->estaVivo()): ?>

                    <?php foreach ($personagemAtual->getAtaques() as $ataque): ?>

                        <form method="POST">

                            <input
                                type="hidden"
                                name="ataque_id"
                                value="<?= $ataque->getId() ?>">

                            <button type="submit">

                                <?= htmlspecialchars($ataque->getNome()) ?>

                            </button>

                        </form>

                    <?php endforeach; ?>

                <?php else: ?>

                    <p>Este personagem não pode atacar.</p>

                <?php endif; ?>

            </div>


            <!-- ========================= -->
            <!-- MENSAGEM -->
            <!-- ========================= -->

            <div class="mensagem">

                <?php if ($mensagem !== null): ?>

                    <?= htmlspecialchars($mensagem) ?>

                <?php else: ?>

                    Aguardando ação...

                <?php endif; ?>

            </div>


            <!-- ========================= -->
            <!-- REINICIAR -->
            <!-- ========================= -->

            <form method="POST" class="reiniciar">

                <button type="submit" name="reiniciar">
                    Reiniciar Batalha
                </button>

            </form>

        </section>

    </main>

</body>

</html>