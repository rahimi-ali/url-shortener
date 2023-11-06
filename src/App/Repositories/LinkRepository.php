<?php

namespace App\Repositories;

use App\Entities\Link;
use App\Util\Traits\InteractsWithReflection;
use PDO;

class LinkRepository implements LinkRepositoryInterface
{
    use InteractsWithReflection;

    private string $table = 'links';

    public function __construct(private readonly PDO $pdo)
    {
    }

    public function save(Link $link): void
    {
        if ($link->isNew()) {
            $statement = $this->pdo->prepare(
                "INSERT INTO $this->table (userId, uri, shortLink) VALUES (:userId, :uri, :shortLink)"
            );

            $statement->execute($this->getProperties($link, ['userId', 'uri', 'shortLink']));

            $id = $this->pdo->lastInsertId();

            $this->setProperties($link, compact('id'));
        } else {
            $statement = $this->pdo->prepare(
                "UPDATE $this->table SET userId = :userId, uri = :uri, shortLink = :shortLink WHERE id = :id"
            );

            $statement->execute($this->getProperties($link, ['id', 'userId', 'uri', 'shortLink']));
        }
    }

    public function getByUserId(int $userId): array
    {
        $statement = $this->pdo->prepare(
            "SELECT * FROM $this->table WHERE userId = :userId"
        );

        $statement->bindParam('userId', $userId, PDO::PARAM_INT);
        $statement->execute();

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        $links = [];
        foreach ($rows as $row) {
            $link = new Link();
            $this->setProperties($link, $row);
            $links[] = $link;
        }

        return $links;
    }

    public function findByShortLink(string $shortLink): Link|null
    {
        $statement = $this->pdo->prepare(
            "SELECT * FROM $this->table WHERE shortLink = :shortLink"
        );

        $statement->bindParam('shortLink', $shortLink);
        $statement->execute();

        $data = $statement->fetch(PDO::FETCH_ASSOC);
        if (empty($data)) {
            return null;
        }

        $link = new Link();
        $this->setProperties($link, $data);

        return $link;
    }

    public function findById(int $id): Link|null
    {
        $statement = $this->pdo->prepare(
            "SELECT * FROM $this->table WHERE id = :id"
        );

        $statement->bindParam('id', $id, PDO::PARAM_INT);
        $statement->execute();

        $data = $statement->fetch(PDO::FETCH_ASSOC);
        if (empty($data)) {
            return null;
        }

        $link = new Link();
        $this->setProperties($link, $data);

        return $link;
    }

    public function deleteById(int $id): void
    {
        $statement = $this->pdo->prepare(
            "DELETE FROM $this->table WHERE id = :id"
        );

        $statement->execute(['id' => $id]);
    }
}