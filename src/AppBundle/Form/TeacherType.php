<?php 

namespace AppBundle\Form;

use AppBundle\Entity\Teacher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TeacherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $Teacher = $options['data'];
        $user = $Teacher->getUser();        
        $builder
            ->add('f_name', TextType::class, array('label' => false ) )
            ->add('l_name', TextType::class, array('label' => false ) )
            ->add('color', TextType::class, array('label' => false ) )
            ->add('code', TextType::class, array('label' => false ) )
            ->add('saveAndAdd', SubmitType::class, array(
                'label' => "Save and Add More"
            ))
            ->add('save', SubmitType::class, array(
                'label' => "Save Teacher"
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Teacher::class,
        ));
    }
}
