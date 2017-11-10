<?php

declare(strict_types=1);

namespace AppBundle\Service\Grid;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Translation\TranslatorInterface;

class GridLoader implements GridLoaderInterface
{
    protected $templateName = 'grid/grid.html.twig';
    protected $paginator;
    protected $pagination;
    protected $translator;
    protected $tableFields;
    protected $sortableFields;
    protected $filterableFields;
    protected $entityName;
    protected $translationDomain;
    protected $entityManager;


    public function __construct(PaginatorInterface $pagination, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->paginator = $pagination;
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }


    private function createQuery(string $className)
    {
        return $this->entityManager->createQueryBuilder()
            ->select("entity")
            ->from($className, "entity")
            ->getQuery();
    }

    public function getTemplate(): string
    {
        return $this->templateName;
    }



    private function proccessFilterableFields(array $filterableFields, array $tableFields, string $domain): array
    {
        $proccessedFilterableFields = array();
        foreach ($filterableFields as $field) {
            $proccessedFilterableFields["entity." . $field] = $this->translator->trans($tableFields[$field],array(),$domain);
        }

        return $proccessedFilterableFields;
    }

    public function getViewData(): array
    {
        return array(
            "pagination"=>$this->pagination,
            "tableFields" => $this->tableFields,
            "sortableFields" => $this->sortableFields,
            "filterableFields" => $this->filterableFields,
            "translation_domain" => $this->translationDomain,
            "entityName"=>$this->entityName,
        );
    }

    public function loadGrid(array $data): GridLoaderInterface
    {
        $query = $this->createQuery($data['className']);

        $this->entityName = $data['entityName'];
        $this->tableFields = $data['tableFields'];
        $this->sortableFields = $data['sortableFields'];
        $this->filterableFields = $this->proccessFilterableFields($data['filterableFields'], $data['tableFields'],$data['translationDomain']);
        $this->translationDomain = $data['translationDomain'];

        $this->pagination = $this->paginator->paginate(
            $query,
            $data['request']->request->getInt('page', 1),
            $data['limit']);


        return $this;
    }
};