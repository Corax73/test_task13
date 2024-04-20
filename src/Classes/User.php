<?php

namespace Classes;

use PDO;

/**
 * @property protected string $table
 * @property protected array $fillable
 * @property protected array $guarded;
 * @property protected Connect $connect
 */
class User extends AbstractModel
{
    protected string $table = 'users';
    protected array $fillable = [
        'email',
        'password'
    ];
    protected array $guarded = [
        'password',
        'remember_token'
    ];

    /**
     * Returns an array of all model entries.
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function all(int $limit = 12, int $offset = 0): array
    {
        $query = 'SELECT id, ' . implode(', ', array_diff($this->fillable, $this->guarded)) . ',created_at FROM `'. $this->table . '` ORDER BY `id` DESC LIMIT :limit';
        if ($offset) {
            $query .= ' OFFSET :offset';
        }
        $stmt = $this->connect->connect(PATH_CONF)->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        if ($offset) {
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }
        $stmt->execute();
        $resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resp ? $resp : [];
    }
}
