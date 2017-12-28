<?php

namespace AppBundle\Controller;

use AppBundle\Form\CommentType;
use AppBundle\Entity\Article;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Serie;
use Doctrine\Common\Persistence\ObjectRepository;
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

/**
 * @Route("/article")
 */
class ArticleController extends Controller
{
    /**
     * @Route("/newArticle", name="new_article")
     */
    public function newArticle(Request $request)
    {
        $article = new Article();
        $em = $this->getDoctrine()->getRepository(Serie::class );

        $form = $this->createArticleForm($article, $em, 'create');

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            //Check on content
            $article = $form->getData();

            if($article->getContent() != null && $article->getContent() != ""){
                $urlAlias = base64_encode(random_bytes(5)) . '-' . $article->getTitle();
                $urlAlias = str_replace('/','',$urlAlias);
                $article->setUrlAlias($urlAlias);
                $article->setPublishedDate(new \DateTime());
                $article->setUser($this->getUser());

                $idSerie = $form->get('serie')->getViewData();
                $article->setSerie($em->find($idSerie));
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

                $this->get('session')->getFlashBag()->set('success', 'Article created');
                // todo : afficher un message de succès
                return $this->redirectToRoute('homepage');
            }
            else {
                $this->get('session')->getFlashBag()->set('error', 'Content cannot be empty');
                return $this->render('default/newArticle.html.twig', array(
                    'form' => $form->createView()
                ));
            }
        }

        return $this->render('default/newArticle.html.twig', array(
            'form' => $form->createView(),
            'error' => null
        ));
    }

    /**
     * @Route("/{urlAlias}", name="article_show")
     */
    public function viewArticle(Article $article){
        $comment = new Comment();
        $comment->setArticle($article);
        $form = $this->createForm(CommentType::class, $comment, array(
            'action' =>  $this->generateUrl('new_comment'),
        ));
        return $this->render('default/viewArticle.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
            'isAuthorized' => $this->authorizedUser($this->getUser(), $article->getUser()),
        ]);
    }

    /**
     * @Route("/deleteArticle/{urlAlias}", name="article_delete")
     */
    public function deleteArticle(Article $article){
        if( !$this->authorizedUser($this->getUser(), $article->getUser())){
            //TODO Erreur à gérer
            return $this->redirectToRoute('homepage');
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();
            // todo afficher un message de succès
            return $this->redirectToRoute('homepage');
        }

    }

    /**
     * @Route("/updateArticle/{urlAlias}", name="article_update")
     */
    public function updateArticle(Request $request, Article $article){

        if(!$this->authorizedUser($this->getUser(), $article->getUser())){
            //Todo erreur à gérer
          return $this->redirectToRoute('homepage');
        }

        $em = $this->getDoctrine()->getRepository(Serie::class);
        $form = $this->createArticleForm($article,$em, 'update');

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $article = $form->getData();

            if($article->getContent() != null && $article->getContent() != "") {

                $idSerie = $form->get('serie')->getViewData();
                $article->setSerie($em->find($idSerie));

                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

                $this->get('session')->getFlashBag()->set('success', 'Article Modified');

                // todo : afficher un message de succès
                return $this->redirectToRoute('homepage');

            }
            else {
                $this->get('session')->getFlashBag()->set('error', 'Content cannot be empty');
                return $this->render('default/newArticle.html.twig', array(
                    'form' => $form->createView()
                ));
            }
        }
        return $this->render('default/newArticle.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    private function createArticleForm(Article $article, ObjectRepository $em, $type){
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
                'attr' => [
                    'placeholder' => 'Content',
                    'class' => 'tinymce'
                ],
            ))
            ->add('isPinned', CheckboxType::class, array(
                'required' => false,
            ))
            ->add($type, SubmitType::class, array(
                'label' => $type,
                'attr' => ['class' => 'btn btn-primary'],
            ))
            ->add('reset', ResetType::class, array(
                'attr' => ['class' => 'btn'],
            ))
            ->getForm();

        return $form;
    }

    private function authorizedUser($user, $userArticle){
        return $user && (($user->getId() === $userArticle->getId()) || $this->isGranted('ROLE_ADMIN', $user));
    }
}
