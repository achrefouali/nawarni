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
          $category=$options['category'];

        $builder


            ->add('title', TextType::class, array('translation_domain' => 'ApplicationSettingBundle',
                                                  'label' => "Intitulé de l'annonce ",

                                                  'required' => false,
                                                  'attr' => array('class' => 'form-control','placeholder'=>"Veuillez choisir l'intitulé de l'annonce")
            ))


            ->add('public', ChoiceType::class, array(
                'translation_domain' => 'ApplicationTrainingBundle',
                'label' => 'Statut',
                'placeholder' => 'Tous',
                'required'=> false,
                'choices'  => array(
                    'Désactivée' => '0',
                    'Activée' => '1',
                ),
                'choice_label' => function ($item, $value){
                    return $value;
                },
                'attr' => array('class' => 'form-control ','data-placeholder' => 'Tous')
            ))

            ->add('adCategory', ChoiceType::class, array(
                'translation_domain' => 'ApplicationTrainingBundle',
                'multiple'=>true,
                'placeholder' => 'Veuillez choisir la catégorie des annonces',
                'label' => "Categorie d'annonce ",
                'required'=>false,
                'choices'  => array_flip($category),
                'attr' => array('class' => 'form-control chosen-select','data-placeholder' => 'Tous')
            ))





        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'category'=>[],

        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix() {
        return 'application_setting_adCategory_filter_type';
    }

}
