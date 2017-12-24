<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Serie;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Created by PhpStorm.
 * User: mpyz
 * Date: 23/12/17
 * Time: 18:03
 */

class ArticleController extends Controller
{
    /**
     * @Route("/Article/newArticle", name="new_article")
     */
    public function newArticle(Request $request)
    {
        $article = new Article();
        $em = $this->getDoctrine()->getRepository(Serie::class );
        dump($em->findAll());
        $form = $this->createFormBuilder($article)
            ->add('serie', ChoiceType::class, array(
                'choices' => $em->findAll(),
                'choice_label' => function(Serie $serie, $key, $index) {
                    return $serie->getName();
                },
                'choice_value' => function (Serie $serie = null) {
                    return $serie ? $serie->getId():'';
                },
                'choice_translation_domain' => false,
                'placeholder' => 'choose-option',
                'required' => false,
            ))
            ->add('seasonNumber', IntegerType::class, array(
                'required' => false,
            ))
            ->add('title', TextType::class, array(
                'attr' => ['placeholder' => 'Title'],
            ))
            ->add('content', TextareaType::class, array(
                'attr' => ['placeholder' => 'Content',],
            ))
            ->add('isPinned', CheckboxType::class, array(
                'required' => false,
            ))
            ->add('create', SubmitType::class, array(
                'label' => 'create',
                'attr' => ['class' => 'btn btn-primary'],
            ))
            ->add('reset', ResetType::class, array(
                'attr' => ['class' => 'btn'],
            ))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $article = $form->getData();

            $article->setUrlAlias(base64_encode(random_bytes(5)) . '-' . $article->getTitle());
            $article->setPublishedDate(new \DateTime());
            $article->setUser($this->getUser());

            $idSerie = $form->get('serie')->getViewData();
            $article->setSerie($em->find($idSerie));
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('homepage');

        }

        return $this->render('default/newArticle.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
