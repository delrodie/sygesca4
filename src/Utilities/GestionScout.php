<?php

namespace App\Utilities;

use App\Entity\Sygesca3\Scout;
use Doctrine\ORM\EntityManagerInterface;

class GestionScout
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


}