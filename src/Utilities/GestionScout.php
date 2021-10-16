<?php

namespace App\Utilities;

use App\Entity\Sygesca3\Fonctions;
use App\Entity\Sygesca3\Scout;
use Doctrine\ORM\EntityManagerInterface;

class GestionScout
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getFonctionByAge($date)
    {
        $age = date('Y') - date('Y', strtotime($date));

        return $this->entityManager->getRepository(Fonctions::class)->findByAge($age);

    }

    /**
     * Fonction pour arrondir au supérieur
     *
     * @param $nombre
     * @param $arrondi
     * @return float|int
     */
    public function arrondiSuperieur($nombre, $arrondi)
    {
        return ceil($nombre / $arrondi) * $arrondi;
    }

    /**
     * fonction verification des valeurs
     *
     * @param $donnee
     * @return string
     */
    public function validForm($donnee)
    {
        $result = htmlspecialchars(stripslashes(trim($donnee)));

        return $result;
    }

}