<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Personnage;
use App\Entity\Question;
use App\Entity\Reponse;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
//use Symfony\Component\Routing\Annotation\JsonResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class GotBetController extends AbstractController
{
    /**
     * @Route("/", name="got_bet")
     */
    public function index()
    {
        return $this->render('got_bet/index.html.twig', [
            'controller_name' => 'GotBetController',
        ]);
    }

    /**
     * @Route("/gotbet/questionnaire", name="questionnaire")
     */
    public function questionnaire()
    {
        $repo = $this->getDoctrine()->getRepository(Personnage::class);
        $personnages = $repo->findAll();
        $repoQ = $this->getDoctrine()->getRepository(Question::class);
        $questions = $repoQ->findAll();

        return $this->render('got_bet/questionnaire.html.twig', [
            'controller_name' => 'GotBetController',
            'personnages' => $personnages,
            'questions' => $questions
        ]);
    }

    /**
     * @Route("/gotbet/createReponse", name="createReponse", methods="POST")
     */
    public function createReponse(Request $request){
        $repo = $this->getDoctrine()->getRepository(Personnage::class);
        $personnages = $repo->findAll();
        //var_dump($request->request);
        //var_dump($request->request->get("statut_1"));

        foreach($personnages as $p){
            $entityManager = $this->getDoctrine()->getManager();
            $pid = $p->id;
            $statut = "statut_{$pid}";
            $pstatut = $request->request->get($statut);
            //var_dump($pid, $pstatut);
            $reponse = new Reponse();
            $reponse->setPersonnage($p);
            $reponse->setStatut($pstatut);
            $reponse->setUser($user = $this->getUser());
            $entityManager->persist($reponse);
            $entityManager->flush();
        }

        return $this->render('got_bet/questionnaire.html.twig', [
        'controller_name' => 'GotBetController',
        'personnages' => $personnages
    ]);
    }

    /**
     * @Route("/gotbet/scores", name="scores", methods="GET")
     */
    public function scores(){
        $repo = $this->getDoctrine()->getRepository(User::class);
        $users = $repo->findBy([], ['score' => 'DESC']);

        return $this->render('got_bet/scores.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/gotbet/about", name="about", methods="GET")
     */
    public function about(){
        $repo = $this->getDoctrine()->getRepository(User::class);
        $users = $repo->findBy([], ['score' => 'DESC']);

        return $this->render('got_bet/about.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/gotbet/compte", name="compte", methods="GET")
     */
    public function monCompte(){
        $entityManager = $this->getDoctrine()->getManager();
        $query = $entityManager->createQuery(
            'SELECT p.nom, p.prenom, r.statut
            FROM App\Entity\Personnage p
            INNER JOIN App\Entity\Reponse r
            WHERE p.id = r.personnage AND r.user = :u')
            ->setParameter('u', $this->getUser());
        return $this->render('got_bet/compte.html.twig', [
            'persorep' => $query->execute()
        ]);
    }
}
