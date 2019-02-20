<?php

namespace SettingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class claimCategoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array('translation_domain' => 'ApplicationPromotionBundle',
                'label' => 'Intitulé de la réclamation',
                'required' => true, 'mapped'=>true,
                'attr' => array('class' => 'form-control','placeholder' => 'Veuillez saisir la categorie de  la réclamation '),
            ))

            ->add('description',TextareaType::class,array('translation_domain' => 'ApplicationPromotionBundle',
                'label' => 'Description',
                'required' => true,
                'attr' => array('class' => 'form-control','maxlength'=>1000)));
        ;        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SettingBundle\Entity\ClaimCategory'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'settingbundle_claimcategory';
    }


}
