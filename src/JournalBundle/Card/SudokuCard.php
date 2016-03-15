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
    public function getData(array $context = [])
    {
        $sudoku = new Sudoku();
        $sudoku->generateUncompleteGrid(0.7);

        return array_replace_recursive(
            $this->data,
            [
                'grid' => $sudoku->getGrid()
            ]
        );
    }
}
