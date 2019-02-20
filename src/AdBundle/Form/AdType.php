<?php

namespace AdBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class AdType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('title', TextType::class, array('translation_domain' => 'ApplicationPromotionBundle',
                'label' => "Intitulé de l'annonce",
                'required' => true, 'mapped'=>true,
                'attr' => array('class' => 'form-control','placeholder' => 'Veuillez saisir la categorie de  l annonce'),
            ))

            ->add('description',TextareaType::class,array('translation_domain' => 'ApplicationPromotionBundle',
                'label' => 'Description',
                'required' => true,
                'attr' => array('class' => 'form-control','maxlength'=>1000)))

            ->add(
                'adCategory',
                EntityType::class,
                [
                    'required' => true,
                    'choice_label' => 'title',
                    'attr' => [
                        'class' => ' form-control',

                    ],
                    'label' => 'Catégorie',
                    'translation_domain' => 'ApplicationTrainingBundle',
                    'query_builder' => function (EntityRepository $er) use (&$options) {
                            return $er->createQueryBuilder('u')
                                ->andWhere('u.enable =1')
                                ->orderBy('u.title', 'DESC');
                    },
                    'class'=>'SettingBundle\Entity\AdCategory'
                ]
            )


            ->add(
                'annonceFile',
                FileType::class,
                [
                    'label' => 'Fiche Annonce',
                   // 'translation_domain' => 'ApplicationCandidatureBundle',
                    'mapped' => false,
                    'required' => true,
                    'attr' => ['class' => 'form-control'],

                ]
            )
            ->add(
                'date',
                DateType::class,
                [
                    'required' => true,
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'label' => 'Date',
                ]
            )
            ->add('address', TextType::class, array('translation_domain' => 'ApplicationPromotionBundle',
                'label' => "Addresse ",
                'required' => true, 'mapped'=>true,
                'attr' => array('class' => 'form-control','placeholder' => 'Veuillez saisir l addresse '),
            ))
            ->add('public', CheckboxType::class, array(
                    'label' => 'Public',
                    //'translation_domain' => 'ApplicationCandidatureBundle',
                    'required' => false,
                    'attr' => array('class' => ' order i-checks'),
                )
            )


//            ->add(
//                'public',
//                ChoiceType::class,
//                [
//                    'choices' => array_flip(array(0 => 'oui',
//                        1 => 'non')),
//
//                    'required' => true,
//                    'choices_as_values' => true,
//
//                    'attr' => [
//                        'class' => 'i-checks',
//                    ],
//                    'choice_label' => function ($item, $value) {
//                        return  $value;
//                    },
//                    'label' => 'Public',
//                ]
//            )
;

    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AdBundle\Entity\Ad'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'adbundle_ad';
    }


}
