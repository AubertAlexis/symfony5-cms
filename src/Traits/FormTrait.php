<?php

namespace App\Traits;

trait FormTrait {
    
    /**
     * Format options for inputs
     *
     * @param string $label 
     * @param array $options
     * @param bool $date
     * @return array
     */
    public function setOptions(string $label, array $options = null, bool $date = false) : array
    {
        $optionsToMerge = ($date === false) ? ['label' => $label] : ['label' => $label, 'widget' => 'single_text', 'html5' => true, 'attr' => ['class' => 'datepicker']];

        return ($options != null) ? array_merge_recursive($options, $optionsToMerge) : $optionsToMerge;
    }
}
