<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Urodoz\Truncate\TruncateService;
use AppBundle\Entity\Article;

/**
 * @Route("/comment")
 */
class CommentController extends Controller
{
    /**
     * @Route("/newComment", name="new_comment")
     */
    public function newComment(Request $request)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        $comment = $form->getData();
        $articleManager = $this->getDoctrine()->getRepository(Article::class);
        $article = $articleManager->find($comment->getArticle());

        if($form->isValid()){

            $comment->setDate(new \DateTime());
            $comment->setUser($this->getUser());
            $comment->setArticle($article);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            // todo : afficher un message de succès
            return $this->redirectToRoute('article_show', array('urlAlias' => $article->getUrlAlias()));

        }
        // todo : afficher message d'erreur
        return $this->redirectToRoute('article_show', array('urlAlias' => $article->getUrlAlias()));
    }

    /**
     * @Route("/delete/{id}", name="delete_comment")
     */
    public function deleteComment(Comment $comment){
        if( !$this->authorizedUserDeleteComment($this->getUser(), $comment->getUser())){
            //TODO Erreur à gérer
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();
            // todo afficher un message de succès
        }
        return $this->redirectToRoute('article_show', array('urlAlias' => $comment->getArticle()->getUrlAlias()));
    }

    private function authorizedUserDeleteComment($user, $userArticle){
        return $user && (($user->getId() === $userArticle->getId()) || $this->isGranted('ROLE_WRITER', $user));
    }
}
