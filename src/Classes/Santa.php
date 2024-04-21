<?php

namespace Classes;

use PDO;

/**
 * @property protected string $table
 * @property protected array $fillable
 * @property protected Connect $connect
 */
class Santa extends AbstractModel
{
    protected string $table = 'santas';
    protected array $fillable = [
        'santas',
        'gifted'
    ];

    /**
     * Returns an array of all model entries.
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function all(int $limit = 12, int $offset = 0): array
    {
        $query = 'SELECT id, ' . implode(', ', $this->fillable) . ',created_at FROM `' . $this->table . '` ORDER BY `id` DESC LIMIT :limit';
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

    /**
     * Creates key-value pairs from an array of identifiers.
     * @param array $userIds
     * @return array
     */
    public function createSantas(array $userIds): array
    {
        $resp = [];
        shuffle($userIds);
        $sizeOf = count($userIds);
        if (($sizeOf % 2) === 0) {
            if ($sizeOf > 2) {
                $chunks = array_chunk($userIds, $sizeOf / 2);
                for ($i = 0; $i < count($chunks[0]); $i++) {
                    $resp[$chunks[0][$i]] = $chunks[1][$i];
                }
                shuffle($chunks[0]);
                shuffle($chunks[1]);
                for ($i = 0; $i < count($chunks[1]); $i++) {
                    $resp[$chunks[1][$i]] = $chunks[0][$i];
                }
            } else {
                $resp = [
                    $userIds[0] => $userIds[1],
                    $userIds[1] => $userIds[0]
                ];
            }
        }
        return $resp;
    }

    /**
     * Save santas data.
     * @param string $santas
     * @param string $gifted
     * @return bool
     */
    public function save(string $santas, string $gifted): bool
    {
        $resp = false;
        $strFields = implode(', ', $this->fillable);
        if ($strFields) {
            try {
                $query = 'INSERT INTO `' . $this->table . '` (' . $strFields . ', created_at) VALUES (:santas, :gifted, :now)';

                $params = [
                    ':santas' => $santas,
                    ':gifted' => $gifted,
                    ':now' => date('Y-m-d h:i:s', time())
                ];
                $stmt = $this->connect->connect(PATH_CONF)->prepare($query);
                $this->connect->connect(PATH_CONF)->beginTransaction();
                $resp = $stmt->execute($params);
                $this->connect->connect(PATH_CONF)->commit();
            } catch (\Exception $e) {
                if ($this->connect->connect(PATH_CONF)->inTransaction()) {
                    $this->connect->connect(PATH_CONF)->rollback();
                }
                throw $e;
            }
        }
        return $resp;
    }

    /**
     * Returns, if available, the gifted's identifier by santa's identifier.
     * @param string $userId
     * @param array $santas
     * @return string
     */
    public function checkWhoseSantaYouAre(string $userId, array $santas): string
    {
        $resp = '';
        if(isset($santas[$userId])) {
            $resp = $santas[$userId];
        }
        return $resp;
    }
}
