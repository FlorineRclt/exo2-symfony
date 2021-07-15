<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('createdAt', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => "Date de création",    //Pas obligatoire, défini le label par défaut mais mdifiable en twig
                'data' => new \DateTime('NOW')
            ])
            ->add('isPublished', CheckboxType::class, [
                'data' => true
            ])
            ->add('category', EntityType::class, [
                // Category fait référence à l'entité Category
                'class' => Category::class,
                'choice_label' => 'title'
            ])
            ->add('tag', EntityType::class, [
                // Tag fait référence à l'entité Tag
                'class' => Tag::class,
                'choice_label' => 'title'
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
