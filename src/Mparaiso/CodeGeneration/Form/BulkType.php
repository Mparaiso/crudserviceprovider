<?php

namespace Mparaiso\CodeGeneration\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class BulkType extends AbstractType
{

    protected $ids;
    protected $actions;

    /**
     * @param array $ids
     * @param array $actions
     */
    function __construct(array $ids, array $actions)
    {
        $this->ids = $ids;
        $this->actions = $actions;
    }

    /**
     * inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add("action", "choice", array("empty_value"=>"",'choices' => $this->actions))
            ->add('ids', 'choice', array(
            "multiple" => TRUE, 'expanded' => TRUE, "choices" => $this->ids));
    }


    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return "bulk";
    }
}
