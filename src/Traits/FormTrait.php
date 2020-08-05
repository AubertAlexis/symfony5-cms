<?php

namespace App\Traits;

use App\Repository\ModuleRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait FormTrait
{
    /**
     * @var string
     */
    private $domain = "";

    /**
     * @var ModuleRepository $moduleRepository
     */
    private $moduleRepository;

    public function __construct(ModuleRepository $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
    }

    /**
     * Define the translate domain
     *
     * @param string $domain
     * @return void
     */
    public function defaultOptions(string $domain)
    {
        $this->domain = $domain;
    }

    /**
     * Format options for inputs
     *
     * @param string $label 
     * @param array $options
     * @param bool $date
     * @return array
     */
    public function setOptions(string $label, array $options = null, bool $date = false): array
    {
        if ($date === false) {  
            $optionsToMerge = ($label != false) ? ['label_format' => "{$this->domain}.form.{$label}"] : ['label_format' => false];
        } else {
            $optionsToMerge = ($label != false) ? ['label_format' => "{$this->domain}.form.{$label}", 'widget' => 'single_text', 'html5' => true, 'attr' => ['class' => 'datepicker']] : ['label_format' => false, 'widget' => 'single_text', 'html5' => true, 'attr' => ['class' => 'datepicker']];
        }

        return ($options != null) ? array_merge_recursive($options, $optionsToMerge) : $optionsToMerge;
    }

    public function isModuleEnabled(string $key): bool
    {
        $module = $this->moduleRepository->findOneByKeyname($key);

        if (!$module) throw new NotFoundHttpException("Not found");
        
        return $module->getEnabled();
    }
}
