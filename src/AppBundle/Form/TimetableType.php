<?php 

namespace AppBundle\Form;

use AppBundle\Entity\Timetable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class TimetableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $Timetable = $options['data'];
        $user = $Timetable->getUser();        
        $builder
            ->add('title', TextType::class, array('label' => false ) )
            ->add('time', TimeType::class, array(
                'input'  => 'datetime',
                'widget' => 'choice', 
            ))
            ->add('save', SubmitType::class, array(
                'label' => "Save Timetable"
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Timetable::class,
        ));
    }
}
