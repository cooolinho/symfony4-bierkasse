<?php

namespace App\Controller;

use App\Entity\Beer;
use App\Form\BeerType;
use App\Repository\BeerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/beer")
 */
class BeerController extends Controller
{
    /**
     * @Route("/", name="beer_index", methods="GET")
     */
    public function index(BeerRepository $beerRepository): Response
    {
        return $this->render('beer/index.html.twig', ['beers' => $beerRepository->findAll()]);
    }

    /**
     * @Route("/new", name="beer_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $beer = new Beer();
        $form = $this->createForm(BeerType::class, $beer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($beer);
            $em->flush();

            return $this->redirectToRoute('beer_index');
        }

        return $this->render('beer/new.html.twig', [
            'beer' => $beer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="beer_show", methods="GET")
     */
    public function show(Beer $beer): Response
    {
        return $this->render('beer/show.html.twig', ['beer' => $beer]);
    }

    /**
     * @Route("/{id}/edit", name="beer_edit", methods="GET|POST")
     */
    public function edit(Request $request, Beer $beer): Response
    {
        $form = $this->createForm(BeerType::class, $beer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('beer_edit', ['id' => $beer->getId()]);
        }

        return $this->render('beer/edit.html.twig', [
            'beer' => $beer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="beer_delete", methods="DELETE")
     */
    public function delete(Request $request, Beer $beer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$beer->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($beer);
            $em->flush();
        }

        return $this->redirectToRoute('beer_index');
    }
}
