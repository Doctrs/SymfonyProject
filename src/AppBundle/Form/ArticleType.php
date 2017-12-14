<?php

namespace AppBundle\Form;

use AppBundle\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class ArticleType extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
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
                'constraints' => [
                    new Count(['max' => 3]),
                ],
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

        $em = $this->em;
        $builder
            ->get('tags')
            ->addModelTransformer(new CallbackTransformer(
                function (Collection $tagsAsArray) {
                    $tagsAsArray = $tagsAsArray->map(function (Tag $tag) {
                        return $tag->getName();
                    });
                    return join(', ', $tagsAsArray->toArray());
                },
                function ($tagsAsString) use ($em) {
                    $tagsAsArray = explode(',', $tagsAsString);
                    $tags = new ArrayCollection(array_map(function ($item) {
                        return Tag::create(trim($item));
                    }, $tagsAsArray));


                    $tags_temp = $em->getRepository(Tag::class)->findBy([
                        'name' => $tagsAsArray
                    ]);
                    $tags_em = [];
                    foreach ($tags_temp as $tag_tmp) {
                        $tags_em[mb_strtolower($tag_tmp->getName())] = $tag_tmp;
                    }

                    foreach ($tags as $key => $tag_form) {
                        $name = mb_strtolower($tag_form->getName());
                        if (isset($tags_em[$name])) {
                            $tags->remove($key);
                            $tags->add($tags_em[$name]);
                        } else {
                            $em->persist($tag_form);
                        }
                    }

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
