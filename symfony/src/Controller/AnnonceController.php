<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Automobile;
use App\Entity\Emploi;
use App\Entity\Immobilier;
use App\Entity\Modele;
use App\Repository\AnnonceRepository;
use App\Repository\ModeleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;

class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonce", "annonce_create", methods={"POST"})
     */
    public function createAction(
        Request $request,
        AnnonceRepository
        $annonceRepository,
        ModeleRepository $modeleRepository
    ): JsonResponse
    {
        $titre = $request->get('titre');
        $contenu = $request->get('contenu');
        $categorieId= $request->get('categorie_id');

        if (null === $titre || null === $contenu || null === $categorieId) {
            return new JsonResponse(['message' => 'Un ou plusieurs champs sont manquants'], 404);
        }

        $annonce = new Annonce();
        $annonce = self::annonce(
            $modeleRepository,
            $annonce,
            $titre,
            $contenu,
            $categorieId
        );

        if (!$annonce instanceof Annonce) {
            return new JsonResponse(['message' => $annonce], 404);
        }

        $annonceRepository->add($annonce);

        return new JsonResponse(self::serializeEntity($annonce), 200, [], true);
    }


    /**
     * @Route("/annonce/{id}", "annonce_update", requirements={"id"="\d+"}, methods={"PUT"})
     */
    public function updateAction(
        Annonce $annonce,
        Request $request,
        ModeleRepository $modeleRepository,
        AnnonceRepository $annonceRepository
    ):JsonResponse
    {
        //var_dump($request);
        $titre = $request->get('titre');
        $contenu = $request->get('contenu');

        $annonce = self::annonce(
            $modeleRepository,
            $annonce,
            $titre ?? $annonce->getTitre(),
            $contenu ?? $annonce->getContenu(),
            $annonce->getCategorieId()
        );

        if (!$annonce instanceof Annonce) {
            return new JsonResponse(['message' => $annonce], 404);
        }

        $annonceRepository->add($annonce);

        return new JsonResponse(self::serializeEntity($annonce), 200, [], true);
    }

    /**
     * @Route("/annonce/{id}", "annonce_show", requirements = {"id"="\d+"}, methods={"GET"})
     */
    public function showAction(Annonce $annonce):JsonResponse
    {
        return new JsonResponse(self::serializeEntity($annonce), 200, [], true);
    }

    /**
     * @Route("/annonce/{id}", "annonce_delete", requirements = {"id"="\d+"}, methods={"DELETE"})
     */
    public function deleteAction(Annonce $annonce, AnnonceRepository $annonceRepository) :JsonResponse
    {
        $annonceRepository->remove($annonce);

        return new JsonResponse('Suppression confirmée');
    }

    private function annonce(
        ModeleRepository $modeleRepository,
        Annonce $annonce,
        string $titre,
        string $contenu,
        int $categorieId
    )
    {
        $annonce->setTitre($titre)
            ->setContenu($contenu);

        switch ($categorieId) {
            case Annonce::EMPLOI:
                $emploi = new Emploi();
                $annonce->setEmploi($emploi);
                break;
            case Annonce::IMMOBILIER:
                $immobilier = new Immobilier();
                $annonce->setImmobilier($immobilier);
                break;
            case Annonce::AUTOMOBILE:
                $automobile = new Automobile();
                $modele = $modeleRepository->searchAndAddModele(
                    $annonce->getTitre()
                );

                if ($modele === null) {
                    return 'modèle non trouvé';
                }

                $automobile->setModele($modele);
                $annonce->setAutomobile($automobile);
                break;
            default:
                return 'catégorie non trouvé';
        }

        return $annonce;
    }


    private function serializeEntity($entity): string
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer], [$encoder]);

        return $serializer->serialize(
            $entity,
            'json',
            [
                AbstractNormalizer::IGNORED_ATTRIBUTES =>
                    [
                        '__initializer__',
                        '__cloner__',
                        '__isInitialized__'
                    ]
            ]
        );
    }
}