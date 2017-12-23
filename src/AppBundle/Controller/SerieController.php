<?php
/**
 * Created by PhpStorm.
 * User: mpyz
 * Date: 23/12/17
 * Time: 12:07
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Serie;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpFoundation\Request;

class SerieController extends Controller
{
    /**
     * @Route("/serie/newSerie", name="new_serie")
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
}