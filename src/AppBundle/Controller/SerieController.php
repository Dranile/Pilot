<?php
/**
 * Created by PhpStorm.
 * User: mpyz
 * Date: 23/12/17
 * Time: 12:07
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Serie;
use AppBundle\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpFoundation\Request;
use Urodoz\Truncate\TruncateService;

/**
 * @Route("/serie")
 */
class SerieController extends Controller
{
    /**
     * @Route("/newSerie", name="new_serie")
     */
    public function newSerie(Request $request)
    {

        $serie = new Serie();

        $form = $this->createFormBuilder($serie)
            ->add('name', TextType::class, array('attr' =>['placeholder' => 'Name']))
            ->add('imageUrl', UrlType::class, array(
                'required' => false,
                'attr' => ['placeholder' => 'imageUrl-placeholder']))
            ->add('save', SubmitType::class, array('label' => 'create'))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $serie = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($serie);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('default/newSerie.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{name}", name="get_serie")
     */
    public function getArticleOfSerie(Serie $serie = null, Request $request){

        if(!empty($serie)){

            $page = $request->query->get('page');
            $nb = $request->query->get('nbItems');
            if(!$nb){
                $nb = 5;
            }
            if(!$page){
                $page = 1;
            }

            $repository = $this->getDoctrine()->getRepository(Article::class);
            $results = $repository->getArticleOfSerie($serie->getId(), $page, $nb);
            dump($request);
            dump($results);
            $truncateService = new TruncateService();
            foreach ($results as $article){
                $article->setContent($truncateService->truncate($article->getContent(), 255));
            }

            $pagination = array(
                'page' => $page,
                'nbPages' => ceil(count($results) / $nb),
                'nbItems' => $nb,
                'routePagination' => 'get_serie',
                'paramsRoute' => array('name' => $serie->getName()),
            );

            return $this->render('default/index.html.twig', [
                'results' => $results,
                'pagination' => $pagination,
            ]);
        }

        return $this->render('default/index.html.twig', [
            'results' => null,
            'pagination' => null
        ]);

    }
}