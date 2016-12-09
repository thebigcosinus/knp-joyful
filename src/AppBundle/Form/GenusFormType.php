<?php

namespace AppBundle\Form;

use AppBundle\Entity\SubFamily;
use AppBundle\Repository\SubFamilyRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenusFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class)
            ->add('speciesCount', NumberType::class)
            ->add('subFamily',EntityType::class, array(
                'class' => SubFamily::class,
                'placeholder' => 'Choose a SubFamily',
                'query_builder' => function(SubFamilyRepository $repo) {
                    return $repo->createAlphabteticalQueryBuilder();
                }
            ))
            ->add('funFact', null, [
                'help' => 'toto'
            ]})
            ->add('isPublished', ChoiceType::class, array(
                'choices' => array(
                    'Yes' => true,
                    'No' => false
                )
            ))
            ->add('firstDiscoveredAt', DateType::class, array(
                'widget' => 'single_text',
                'html5' => false,
                'attr' => array('class' => 'js-datepicker')
            ))
            ;
    }

    /*public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view['funFact']->vars['help'] =  'For exemple';
    }*/


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Genus'
        ));
        
    }

    public function getName()
    {
        return 'app_bundle_genus_form_type';
    }
}
