<?php

class Player {
    private int $level;

    public function __construct(int $level)
    {
        $this->level = $level;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }
}

class Encounter {
    private const RESULT_WINNER = 1;
    private const RESULT_LOSER = -1;
    private const RESULT_DRAW = 0;
    private const RESULT_POSSIBILITIES = [self::RESULT_WINNER, self::RESULT_LOSER, self::RESULT_DRAW];
    private const K_FACTOR = 32;

    public static function probabilityAgainst(int $levelPlayerOne, int $againstLevelPlayerTwo): float
    {
        return 1 / (1 + (10 ** (($againstLevelPlayerTwo - $levelPlayerOne) / 400)));
    }

    public static function setNewLevel(int &$levelPlayerOne, int $againstLevelPlayerTwo, int $playerOneResult)
    {
        if (!in_array($playerOneResult, self::RESULT_POSSIBILITIES)) {
            trigger_error(sprintf('Invalid result. Expected %s', implode(' or ', self::RESULT_POSSIBILITIES)));
        }

        $levelPlayerOne += (int)(self::K_FACTOR * ($playerOneResult - self::probabilityAgainst($levelPlayerOne, $againstLevelPlayerTwo)));
    }
}

$greg = new Player(400);
$jade = new Player(800);

echo sprintf(
    'Greg a %.2f%% de chance de gagner face à Jade',
    Encounter::probabilityAgainst($greg->getLevel(), $jade->getLevel()) * 100
) . PHP_EOL;

// Imaginons que Greg l'emporte tout de même.
Encounter::setNewLevel($greg->getLevel(), $jade->getLevel(), Encounter::RESULT_WINNER);
Encounter::setNewLevel($jade->getLevel(), $greg->getLevel(), Encounter::RESULT_LOSER);

echo sprintf(
    'Les niveaux des joueurs ont évolué vers %s pour Greg et %s pour Jade',
    $greg->getLevel(),
    $jade->getLevel()
);

exit(0);
