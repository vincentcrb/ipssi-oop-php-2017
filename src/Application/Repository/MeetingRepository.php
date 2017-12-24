<?php

declare(strict_types=1);

namespace Application\Repository;

use Application\Collection\MeetingCollection;
use Application\Entity\Meeting;
use Application\Exception\MeetingNotFoundException;
use PDO;

final class MeetingRepository
{
    /**
     * @var PDO
     */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function fetchAll() : MeetingCollection
    {
        $result = $this->pdo->query('SELECT id, title FROM meetings');
        $meetings = [];
        while ($meetings = $result->fetch()) {
            $meetings[] = new Meeting($meetings['title']);
        }
        return new MeetingCollection(...$meetings);
    }

    public function get(string $title) : Meeting
    {
        $statement = $this->pdo->prepare('SELECT id, title FROM meetings WHERE title = :title');
        $statement->execute([':title' => $title]);
        $meetings = $statement->fetch();
        if (!$meetings) {
            throw new MeetingNotFoundException();
        }
        return new Meeting($meetings['title']);
    }
}
