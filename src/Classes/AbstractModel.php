<?php

namespace Classes;

use Classes\Connect;

/**
 * @property protected string $table
 * @property protected array $fillable;
 * @property protected array $guarded;
 * @property Connect $connect
 */
abstract class AbstractModel
{
    protected string $table;
    protected array $fillable;
    protected array $guarded;
    protected Connect $connect;

    public function __construct()
    {
        $this->connect = new Connect;
    }

    /**
     * Returns the name of the model table.
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}
