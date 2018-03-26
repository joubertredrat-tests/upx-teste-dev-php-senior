<?php

namespace Acme\Task\Repository;

use Acme\Task\Model\Tag;

/**
 * Tag Repository
 *
 * @package Acme\Task\Repository
 */
class TagRepository extends AbstractRepository
{
    /**
     * @param int $id
     * @return Tag
     * @throws \ReflectionException
     */
    public function get(int $id): Tag
    {
        $tag = new Tag();

        $pdo = $this
            ->getConnection()
            ->getPdo()
        ;

        $query = "SELECT * FROM tag WHERE id = :id";
        $statement = $pdo->prepare($query);
        $statement->bindParam(":id", $id, \PDO::PARAM_INT);

        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->execute();
        $data = $statement->fetchAll();

        if (count($data) == 1) {
            $this->bindDataModel($tag, $data[0]);
        }

        return $tag;
    }

    /**
     * @return array<Tag>
     * @throws \ReflectionException
     */
    public function getAll(): array
    {
        $return = [];

        $pdo = $this
            ->getConnection()
            ->getPdo()
        ;

        $query = "SELECT * FROM tag";
        $statement = $pdo->prepare($query);

        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->execute();
        $data = $statement->fetchAll();

        if (count($data) > 0) {
            foreach ($data as $row) {
                $tag = new Tag();
                $this->bindDataModel($tag, $row);
                $return[] = $tag;
            }
        }

        return $return;
    }

    /**
     * @param Tag $tag
     * @return Tag
     * @throws \ReflectionException
     */
    public function add(Tag $tag): Tag
    {
        if (is_null($tag->getId())) {
            $pdo = $this
                ->getConnection()
                ->getPdo()
            ;

            $tag->setCreated(
                new \DateTime('now')
            );

            $query = "INSERT INTO tag (name, text_color, background_color, created) "
                . "VALUES (:name, :textColor, :backgroundColor, :created)";

            $statement = $pdo->prepare($query);
            $statement->bindParam(
                ":name",
                $tag->getName(),
                \PDO::PARAM_STR
            );
            $statement->bindParam(
                ":textColor",
                $tag->getTextColor(),
                \PDO::PARAM_STR
            );
            $statement->bindParam(
                ":backgroundColor",
                $tag->getBackgroundColor(),
                \PDO::PARAM_STR
            );
            $statement->bindParam(
                ":created",
                $tag->getCreated()->format('Y-m-d H:i:s'),
                \PDO::PARAM_STR
            );

            $statement->execute();

            $reflection = new \ReflectionProperty(Tag::class, 'id');
            $reflection->setAccessible(true);
            $reflection->setValue($tag, $pdo->lastInsertId());
            $reflection->setAccessible(false);
        }

        return $tag;
    }

    public function update(Tag $tag): Tag
    {
        if (!is_null($tag->getId())) {
            $pdo = $this
                ->getConnection()
                ->getPdo()
            ;

            $tag->setUpdated(
                new \DateTime('now')
            );

            $query = "UPDATE tag SET name = :name, text_color = :text_color, "
                . "background_color = :background_color, updated = :updated WHERE id = :id";

            $statement = $pdo->prepare($query);
            $statement->bindParam(
                ":name",
                $tag->getName(),
                \PDO::PARAM_STR
            );
            $statement->bindParam(
                ":text_color",
                $tag->getTextColor(),
                \PDO::PARAM_STR
            );
            $statement->bindParam(
                ":background_color",
                $tag->getBackgroundColor(),
                \PDO::PARAM_STR
            );
            $statement->bindParam(
                ":updated",
                $tag->getUpdated()->format('Y-m-d H:i:s'),
                \PDO::PARAM_STR
            );
            $statement->bindParam(
                ":id",
                $tag->getId(),
                \PDO::PARAM_INT
            );

            $statement->execute();
        }

        return $tag;
    }

    /**
     * @param Tag $tag
     * @return bool
     */
    public function delete(Tag $tag): bool
    {
        if (!is_null($tag->getId())) {
            $pdo = $this
                ->getConnection()
                ->getPdo()
            ;

            $query = "DELETE FROM tag WHERE id = :id";
            $statement = $pdo->prepare($query);
            $statement->bindParam(
                ":id",
                $tag->getId(),
                \PDO::PARAM_INT
            );

            $statement->execute();
        }

        return true;
    }

    /**
     * @param Tag $tag
     * @param array $data
     * @throws \ReflectionException
     */
    private function bindDataModel(Tag $tag, array $data): void
    {
        $reflection = new \ReflectionProperty(Tag::class, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($tag, $data['id']);
        $reflection->setAccessible(false);

        $tag->setName($data['name']);
        $tag->setTextColor($data['text_color']);
        $tag->setBackgroundColor($data['background_color']);
        $tag->setCreated(
            new \DateTime($data['created'])
        );

        if ($data['updated']) {
            $tag->setUpdated(
                new \DateTime($data['updated'])
            );
        }
    }
}
