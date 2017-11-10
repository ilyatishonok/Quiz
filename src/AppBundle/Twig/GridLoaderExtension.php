<?php


namespace AppBundle\Twig;


use AppBundle\Service\Grid\GridLoaderInterface;

class GridLoaderExtension extends \Twig_Extension
{
    private $environment;

    public function __construct(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return array(
          new \Twig_SimpleFunction("grid_loader_render", array($this,"render"), array("is_safe"=>array("html"),"needs_environment"))
        );
    }

    public function render(GridLoaderInterface $gridLoader)
    {
        return $this->environment->render($gridLoader->getTemplate(),$gridLoader->getViewData());
    }

    public function getName()
    {
        return "grid_loader";
    }
}