<?php

namespace App\Repositories;

use App\Entities\User;
use App\Util\Traits\InteractsWithReflection;
use PDO;

class UserRepository implements UserRepositoryInterface
{
    use InteractsWithReflection;

    private string $table = 'users';

    public function __construct(private readonly PDO $pdo)
    {
    }

    public function save(User $user): void
    {
        if ($user->isNew()) {
            $statement = $this->pdo->prepare(
                "INSERT INTO $this->table (username, password, firstname, lastname) VALUES (:username, :password, :firstname, :lastname)"
            );

            $statement->execute($this->getProperties($user, ['username', 'password', 'firstname', 'lastname']));

            $id = $this->pdo->lastInsertId();

            $this->setProperties($user, compact('id'));
        } else {
            $statement = $this->pdo->prepare(
                "UPDATE $this->table SET username = :username, password = :password, firstname = :firstname, lastname = :lastname WHERE id = :id"
            );

            $statement->execute($this->getProperties($user, ['id', 'username', 'password', 'firstname', 'lastname']));
        }
    }

    public function findById(int $id): User|null
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

        $user = new User();
        $this->setProperties($user, $data);

        return $user;
    }

    public function findByUsername(string $username): User|null
    {
        $statement = $this->pdo->prepare(
            "SELECT * FROM $this->table WHERE username = :username"
        );

        $statement->bindParam('username', $username);
        $statement->execute();

        $data = $statement->fetch(PDO::FETCH_ASSOC);
        if (empty($data)) {
            return null;
        }

        $user = new User();
        $this->setProperties($user, $data);

        return $user;
    }
}