<?php

declare(strict_types = 1);

include_once '../vendor/autoload.php';

use App\Domain\Game\Builders\EasyGame;
use App\Domain\Game\Builders\HardGame;
use App\Domain\Game\Builders\VerySmallGame;
use App\Domain\Game\GameActions;
use App\Persistence\Files\GameRepository;
use App\Views\Html\BoardView;
use App\Views\Html\Dev\BoardView as DevBoardView;

$gameRepository = new GameRepository('./');
$game           = EasyGame::init();
//$game           = HardGame::init();
//$game           = VerySmallGame::init();
//$game           = $gameRepository->get();
//$game->board()->closeAll();

$actions = new GameActions($game);

$x      = (int) ($_GET['x'] ?? 1);
$y      = (int) ($_GET['y'] ?? 1);
$action = ($_GET['action'] ?? 'click');

$actions->dispatch($action, $x, $y);
//$gameRepository->add($game);

$boardView    = new BoardView($game->board());
$devBoardView = new DevBoardView($game->board());

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Saper</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/app.js"></script>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th scope="col">Bombs</th>
                <th scope="col">Width</th>
                <th scope="col">Height</th>
                <th scope="col">Clicks</th>
                <th scope="col">Time</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $game->board()->tiles()->bombs()->count() ?></td>
                <td><?= $game->board()->width() ?></td>
                <td><?= $game->board()->height() ?></td>
                <td><?= $game->stats()->clicks() ?></td>
                <td><?= $game->stats()->time() ?></td>
            </tr>
        </tbody>
    </table>
    <?= $boardView->render() ?>

    <div class="dev">
        <?= $devBoardView->render() ?>

        <div class="">
            <?php var_dump($game->board()->tiles()->tile($x, $y)); ?>
        </div>
    </div>

    <div class="json">
        <pre><?= json_encode($game, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT, 512) ?></pre>
    </div>
</body>
</html>