<?php

namespace App\Interfaces;

/**
 * Реализует удаление данных из таблицы
 */
interface DeleteInterface
{
    /**
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool;
}
