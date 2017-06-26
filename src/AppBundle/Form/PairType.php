<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Command\Currencies;

class PairType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $reflection = new \ReflectionClass(new Currencies());
        $pairs = $reflection->getConstants();
        $builder
            ->add('pair', ChoiceType::class, [
              'choices' => [
                'available cryptos' => $this->formatPairs($pairs),
              ],
              'choice_label' => function ($value, $key) {
                  return strtolower(str_replace('_', ' ', $key));
              },
              'placeholder' => 'Choose an option',
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    private function formatPairs(array $pairs)
    {
      unset($pairs['EURO']);
      unset($pairs['RANDOM']);

      return $pairs;
    }
}
