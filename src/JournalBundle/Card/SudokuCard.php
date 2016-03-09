<?php

namespace JournalBundle\Card;

use Sudoku\Sudoku;

/**
 * Sudoku card
 */
class SudokuCard extends BaseCard
{
    /**
     * {@inheritdoc}
     */
    public function getParameters(array $context = [])
    {
        $sudoku = new Sudoku();
        $sudoku->generateUncompleteGrid(1);

        return array_replace_recursive(
            $this->parameters,
            [
                'grid' => $sudoku->getGrid()
            ]
        );
    }
}
