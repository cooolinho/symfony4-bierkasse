<?php

namespace App\Controller;

use App\Entity\Beer;
use App\Entity\Cashbox;
use App\Entity\Payment;
use App\Entity\Player;
use App\Form\CashboxType;
use App\Repository\CashboxRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cashbox")
 */
class CashboxController extends Controller
{
    /**
     * @Route("/", name="cashbox_index", methods="GET")
     */
    public function index(CashboxRepository $cashboxRepository): Response
    {
        return $this->render('cashbox/index.html.twig', ['cashboxes' => $cashboxRepository->findAll()]);
    }

    /**
     * @Route("/new", name="cashbox_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $cashbox = new Cashbox();
        $form = $this->createForm(CashboxType::class, $cashbox);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cashbox);
            $em->flush();

            return $this->redirectToRoute('cashbox_index');
        }

        return $this->render('cashbox/new.html.twig', [
            'cashbox' => $cashbox,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cashbox_show", methods="GET")
     */
    public function show(Cashbox $cashbox): Response
    {
        /* @var Beer $beer */
        foreach ($cashbox->getBeers() as $beer) {
            $beer->getPlayer()->addBeer();
        }

        /* @var Payment $payment */
        foreach ($cashbox->getPayments() as $payment) {
            $payment->getPlayer()->addPayment($payment->getAmount());
        }

        return $this->render('cashbox/show.html.twig', ['cashbox' => $cashbox]);
    }

    /**
     * @Route("/{id}/history", name="cashbox_history", methods="GET")
     */
    public function history(Cashbox $cashbox): Response
    {
        return $this->render('cashbox/history.html.twig', [
            'cashbox' => $cashbox
        ]);
    }

    /**
     * @Route("/{id}/player/{player}/history", name="cashbox_player_history", methods="GET")
     */
    public function historyPlayer(Cashbox $cashbox, Player $player): Response
    {
        /* @var Payment $payment */
        foreach ($cashbox->getPayments() as $payment) {
            if ($payment->getPlayer()->getId() != $player->getId()) {
                $cashbox->removePayment($payment);
            }
        }

        /* @var Beer $beer */
        foreach ($cashbox->getBeers() as $beer) {
            if ($beer->getPlayer()->getId() != $player->getId()) {
                $cashbox->removeBeer($beer);
            }
        }

        return $this->render('cashbox/history.html.twig', [
            'cashbox' => $cashbox,
            'player' => $player
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cashbox_edit", methods="GET|POST")
     */
    public function edit(Request $request, Cashbox $cashbox): Response
    {
        $form = $this->createForm(CashboxType::class, $cashbox);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cashbox_edit', ['id' => $cashbox->getId()]);
        }

        return $this->render('cashbox/edit.html.twig', [
            'cashbox' => $cashbox,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cashbox_delete", methods="DELETE")
     */
    public function delete(Request $request, Cashbox $cashbox): Response
    {
        if ($this->isCsrfTokenValid('delete' . $cashbox->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cashbox);
            $em->flush();
        }

        return $this->redirectToRoute('cashbox_index');
    }

    /**
     * @Route("/{id}", name="cashbox_reset", methods="RESET")
     */
    public function reset(Request $request, Cashbox $cashbox): Response
    {
        if ($this->isCsrfTokenValid('reset' . $cashbox->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();

            foreach ($cashbox->getPayments() as $payment) {
                $cashbox->removePayment($payment);
                $em->remove($payment);
            }

            foreach ($cashbox->getBeers() as $beer) {
                $cashbox->removeBeer($beer);
                $em->remove($beer);
            }

            $em->flush();
        }

        return $this->redirectToRoute('cashbox_edit', ['id' => $cashbox->getId()]);
    }

    /**
     * @Route("/action", name="cashbox_action", methods="GET|POST")
     */
    public function beer(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        /* @var Cashbox $cashbox */
        $cashbox = $em->getRepository('App:Cashbox')->find($request->request->get('cashbox'));

        /* @var Player $player */
        $player = $em->getRepository('App:Player')->find($request->request->get('player'));

        $action = $request->request->get('action');

        if ($player instanceof Player) {

            if ($action === 'beer') {
                $beer = new Beer();
                $beer->setPlayer($player);

                $cashbox->addBeer($beer);
            } else if ($action === 'payment') {
                $payment = new Payment();
                $payment->setAmount($request->request->get('amount'));
                $payment->setPlayer($player);

                $cashbox->addPayment($payment);
            }

            $em->persist($cashbox);
            $em->flush();
        }

        $message = [];
        $message['beer'] = ' hat 1 Bier getrunken';
        $message['payment'] = ' hat 1 Bier bezahlt';

        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);
        $response->setContent($player->getFullname() . $message[$action]);

        return $response;
    }
}
