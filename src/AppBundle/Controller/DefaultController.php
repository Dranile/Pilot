<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Article;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $results = $repository->findLastArticle(10);

        foreach ($results as $article){
            $article->setContent(substr($article->getContent(),0,255). '...');
        }

        //var_dump($result);
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'results' => $results,
            'isEmpty' => count($results) === 0,
        ]);
    }
}
