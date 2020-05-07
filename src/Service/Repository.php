<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Service;

use InvalidArgumentException;
use OxidEsales\Eshop\Core\Model\BaseModel;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\GraphQL\Base\DataType\FilterInterface;
use OxidEsales\GraphQL\Base\DataType\PaginationFilter;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Catalogue\DataType\FilterList;
use OxidEsales\GraphQL\Catalogue\DataType\DataType;
use PDO;

class Repository
{
    /** @var QueryBuilderFactoryInterface $queryBuilderFactory */
    private $queryBuilderFactory;

    public function __construct(
        QueryBuilderFactoryInterface $queryBuilderFactory
    ) {
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    /**
     * @template T
     * @param class-string<T> $type
     * @return T
     * @throws InvalidArgumentException if $type is not instance of DataType
     * @throws NotFound if BaseModel can not be loaded
     */
    public function getById(
        string $id,
        string $type
    ) {
        $model = oxNew($type::getModelClass());
        if (!($model instanceof BaseModel)) {
            throw new InvalidArgumentException();
        }
        if (!$model->load($id) || (method_exists($model, 'canView') && !$model->canView())) {
            throw new NotFound($id);
        }
        $type = new $type($model);
        if (!($type instanceof DataType)) {
            throw new InvalidArgumentException();
        }
        return $type;
    }

    /**
     * @template T
     * @param class-string<T> $type
     * @return T[]
     * @throws InvalidArgumentException if model in $type is not instance of BaseModel
     */
    public function getByFilter(
        FilterList $filter,
        string $type,
        ?PaginationFilter $pagination = null
    ): array {
        $types = [];
        $model = oxNew($type::getModelClass());
        if (!($model instanceof BaseModel)) {
            throw new InvalidArgumentException();
        }

        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->select('*')
                     ->from($model->getViewName())
                     ->orderBy($model->getViewName() . '.oxid');

        if (
            $filter->getActive() !== null &&
            $filter->getActive()->equals() === true
        ) {
            $activeSnippet = $model->getSqlActiveSnippet();
            if (strlen($activeSnippet)) {
                $queryBuilder->andWhere($activeSnippet);
            }
        }

        /** @var FilterInterface[] $filters */
        $filters = array_filter($filter->getFilters());
        foreach ($filters as $field => $fieldFilter) {
            $fieldFilter->addToQuery($queryBuilder, $field);
        }

        if ($pagination !== null) {
            $pagination->addPaginationToQuery($queryBuilder);
        }

        $queryBuilder->getConnection()->setFetchMode(PDO::FETCH_ASSOC);
        /** @var \Doctrine\DBAL\Statement $result */
        $result = $queryBuilder->execute();
        foreach ($result as $row) {
            $newModel = clone $model;
            $newModel->assign($row);
            $types[] = new $type($newModel);
        }

        return $types;
    }
}
