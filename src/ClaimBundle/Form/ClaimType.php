<?php

namespace ClaimBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClaimType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array('translation_domain' => 'ApplicationPromotionBundle',
                'label' => "Intitulé de la réclamation",
                'required' => true, 'mapped'=>true,
                'attr' => array('class' => 'form-control','placeholder' => 'Veuillez saisir la categorie de  l annonce'),
            ))

            ->add('description',TextareaType::class,array('translation_domain' => 'ApplicationPromotionBundle',
                'label' => 'Description',
                'required' => true,
                'attr' => array('class' => 'form-control','maxlength'=>1000)))

            ->add(
                'claimCategory',
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
                    'class'=> 'SettingBundle\Entity\ClaimCategory'
                ]
            )


            ->add(
                'claimFile',
                FileType::class,
                [
                    'label' => 'Fiche réclamation',
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
            );
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ClaimBundle\Entity\Claim'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'claimbundle_claim';
    }


}
