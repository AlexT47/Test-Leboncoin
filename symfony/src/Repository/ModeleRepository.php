<?php

namespace App\Repository;

use App\Entity\Modele;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Modele>
 */
class ModeleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Modele::class);
    }


    public function searchAndAddModele($string)
    {
        $modeles = $this->createQueryBuilder('m')
            ->select('m.id, LOWER(m.nom) as nom, 0 as poid')
            ->getQuery()
            ->getArrayResult();

        $string = strtolower(self::retirerAccent($string));

        // Retirer les mots qui n'ont aucune correspondance avec modèles
        $str = preg_replace('/(\d+)/i', '', $string);
        foreach (explode(' ', $str) as $word) {
            if (!self::verifSiMotExiste($modeles, $word)) {
                $str = str_replace($word, '', $str);
            }
        }

        $arrayStr = explode(' ', trim($str));

        // Recherche du modèle principale
        foreach ($modeles as $key => $modele) {
            $arrayModele = explode(' ', $modele['nom']);
            $modelePrincipal = preg_replace('/(\d+)/i', '', $arrayModele[0]);
            $strEstTrouve = false;

            foreach ($arrayStr as $word) {
                if (self::compareModeleByWord($modelePrincipal, $word)) {
                    $modeles[$key]['poid'] = 1;
                    $strEstTrouve = true;
                }

                if (isset($arrayModele[1])) {
                    if (self::compareModeleByWord($arrayModele[1], $word)) {
                        $modeles[$key]['poid'] += 0.5;
                    }
                }
            }

            if (!$strEstTrouve) {
                unset($modeles[$key]);
            }
        }

        if (count($modeles) === 0) {
            return null;
        }

        // Recherche des compléments
        $str = trim(preg_replace('/[a-z]/i', '', $string));
        $arrayStr = explode(' ', $str);
        foreach ($modeles as $key => $modele) {
            $arrayModele = explode(' ', $modele['nom']);

            foreach ($arrayModele as $wordModele) {
                $str = trim(preg_replace('/[a-z]/i', '', $wordModele));
                foreach ($arrayStr as $word) {
                    if (self::compareModeleByWord($str, $word)) {
                        $modeles[$key]['poid'] += 0.5;
                        break;
                    }
                }
            }
        }

        $columnPoid = array_column($modeles, 'poid');
        $columnNom = array_column($modeles, 'nom');
        array_multisort(
            $columnPoid,
            SORT_DESC,
            $columnNom,
            SORT_ASC,
            $modeles
        );

        if (!isset($modeles[0])) {
            return null;
        }

        return $this->find($modeles[0]['id']);
    }

    private function retirerAccent($string) {
        $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
        $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
        return str_replace($search, $replace, $string);
    }

    private function VerifSiMotExiste($modeles, $str): bool
    {
        $result = false;
        foreach ($modeles as $modele) {
            if (str_contains($modele['nom'], $str)) {
                $result = true;
            }
        }

        return $result;
    }

    private function compareModeleByWord($modele, $string): bool
    {
        $result = false;
        foreach (explode(' ', $string) as $word) {
            if ($modele === $word) {
                $result = true;
            }
        }

        return $result;
    }
}
