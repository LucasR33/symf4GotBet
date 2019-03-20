<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Personnage;
use App\Entity\Question;
use App\Entity\Reponse;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Security\Core\User\UserInterface;
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
        var_dump($request->request);
        //var_dump($request->request->get("statut_1"));
        foreach( $request->request as $saisie){
          //  var_dump($saisie);
        }

        foreach($personnages as $p){
            $pid = $p->id;
            var_dump($pid);
            $pstatut = $request->request->statut_{{$pid}};
            var_dump($pstatut);
        }

        return $this->render('got_bet/questionnaire.html.twig', [
        'controller_name' => 'GotBetController',
        'personnages' => $personnages
    ]);
    }
}
