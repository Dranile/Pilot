<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($options["data"]->getArticle()){
            $idArticle = $options["data"]->getArticle()->getId();
        }
        else{
            $idArticle = '';
        }
        $builder
            ->add('content', TextareaType::class, array(
                'label' => false,
                'attr' => [
                    'placeholder' => 'your-comment',
                    'class' => 'form-input'
                ]
            ))
            ->add('article', HiddenType::class, array(
                'data' => $idArticle,
            ))
            ->add('create', SubmitType::class, array(
                'label' => 'create',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ))
        ;
    }
}