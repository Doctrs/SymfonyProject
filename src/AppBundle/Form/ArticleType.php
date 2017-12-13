<?php

namespace AppBundle\Form;

use AppBundle\Entity\Tag;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class ArticleType extends AbstractType
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
            ->add('tags', TextType::class, [
                'label' => 'Тэги',
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

        $builder
            ->get('tags')
            ->addModelTransformer(new CallbackTransformer(
                function (Collection $tagsAsArray) {
                    $tagsAsArray = $tagsAsArray->map(function (Tag $tag) {
                        return $tag->getName();
                    });
                    return join(', ', $tagsAsArray->toArray());
                },
                function ($tagsAsString) {
                    $tags = explode(',', $tagsAsString);
                    $tags = array_slice($tags, 0, 3);
                    $tags = array_map(function ($item) {
                        return Tag::create(trim($item));
                    }, $tags);
                    return $tags;
                }
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Article'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'article';
    }


}
