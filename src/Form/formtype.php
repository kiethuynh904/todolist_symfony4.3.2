<?php


namespace App\Form;

use App\Entity\Todo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class formtype extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,array(
                'attr'=>array('class'=> 'form-control')
            ))
            ->add('category',TextType::class,array(
                'attr'=>array('class'=> 'form-control')
            ))
            ->add('description',TextareaType::class,array(
                'attr'=>array('class'=> 'form-control')
            ))
            ->add('priority',ChoiceType::class,array(
                'attr'=>array('class'=> 'form-control'),
                'choices'=>array('Low' => 'Low','Normal'=>'Normal','High'=>'High')
            ))
            ->add('due_date',DateTimeType::class,array(
                'attr'=>array('class'=> 'form-control')
            ))
            ->add('save',SubmitType::class,array(
                'label'=>'Create Todo',
                'attr'=>array('class'=> 'btn btn-success')
            ));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Todo::class,
        ]);
    }
}