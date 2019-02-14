<?php
/**
 * Created by PhpStorm.
 * User: acf
 * Date: 16/08/2018
 * Time: 14:20
 */

namespace AdBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FilterType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder


            ->add('title', TextType::class, array('translation_domain' => 'ApplicationSettingBundle',
                                                  'label' => "Intitulé de l'annonce ",
                                                  'required' => false,
                                                  'attr' => array('class' => 'form-control')
            ))

//            ->add('title', TextType::class, array('translation_domain' => 'ApplicationSettingBundle',
//                'label' => "Intitulé de l'annonce ",
//                'required' => false,
//                'attr' => array('class' => 'form-control')
//            ))






        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(

        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix() {
        return 'application_setting_adCategory_filter_type';
    }

}
