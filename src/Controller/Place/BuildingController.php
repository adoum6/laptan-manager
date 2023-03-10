<?php

namespace App\Controller\Place;

use App\Entity\Place\Building;
use App\Form\Place\BuildingType;
use App\Repository\Place\BuildingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/place/building")
 */
class BuildingController extends AbstractController
{
    /**
     * @Route("/", name="place_building_index", methods={"GET"})
     */
    public function index(BuildingRepository $buildingRepository): Response
    {
        return $this->render('place/building/index.html.twig', [
            'buildings' => $buildingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="place_building_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $building = new Building();
        $form = $this->createForm(BuildingType::class, $building);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($building);
            $entityManager->flush();

            return $this->redirectToRoute('place_building_index');
        }

        return $this->render('place/building/new.html.twig', [
            'building' => $building,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="place_building_show", methods={"GET"})
     */
    public function show(Building $building): Response
    {
        return $this->render('place/building/show.html.twig', [
            'building' => $building,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="place_building_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Building $building): Response
    {
        $form = $this->createForm(BuildingType::class, $building);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('place_building_index');
        }

        return $this->render('place/building/edit.html.twig', [
            'building' => $building,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="place_building_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Building $building): Response
    {
        if ($this->isCsrfTokenValid('delete'.$building->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($building);
            $entityManager->flush();
        }

        return $this->redirectToRoute('place_building_index');
    }
}
