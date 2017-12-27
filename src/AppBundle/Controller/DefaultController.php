<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Urodoz\Truncate\TruncateService;
use AppBundle\Entity\Article;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        $page = $request->query->get('page');
        $nb = $request->query->get('nbArticle');
        if(!$nb){
            $nb = 5;
        }
        if(!$page){
            $page = 1;
        }
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $results = $repository->findLastArticle($page, $nb);

        $truncateService = new TruncateService();
        foreach ($results as $article){
            $article->setContent($truncateService->truncate($article->getContent(), 255));
        }

        $pagination = array(
            'page' => $page,
            'nbPages' => ceil(count($results) / $nb),
            'nbArticles' => $nb,
        );

        return $this->render('default/index.html.twig', [
            'results' => $results,
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/search/{text}", name="search")
     */
    public function search(Request $request, $text){

        $repository = $this->getDoctrine()->getRepository(Serie::class);
        $results = $repository->getSameAs($text);

        return new Response(json_encode($results));
    }

}
