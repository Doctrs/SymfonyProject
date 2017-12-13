<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class AuthorArticleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Название',
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                    new Length(['max' => 250])
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('text', null, [
                'label' => 'Текст статьи',
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('author', null, [
                'label' => 'Автор',
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                    new Length(['max' => 250])
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('site', null, [
                'label' => 'Сайт',
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                    new Length(['max' => 250])
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('publishDate', null, [
                'label' => 'Дата публикации',
                'constraints' => [
                    new NotNull(),
                    new DateTime()
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\AuthorArticle'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'author_article';
    }


}
