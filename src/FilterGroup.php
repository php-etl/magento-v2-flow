<?php

namespace Kiboko\Component\Flow\Magento2;

use phpDocumentor\Reflection\Types\This;

class FilterGroup
{
    private array $filters = [];

    public function asArray(): array
    {
        return $this->filters;
    }

    public function withFilter(string $field, string $operator, mixed $value): self
    {
        $this->filters[] = [
            'field' => $field,
            'value' => $value,
            'condition_type' => $operator,
        ];

        return $this;
    }

    public function compileFilters(int $groupIndex = 0): array
    {
        return array_merge(...array_map(fn (array $item, int $key) => [
            sprintf('searchCriteria[filterGroups][%s][filters][%s][field]', $groupIndex, $key) => $item['field'],
            sprintf('searchCriteria[filterGroups][%s][filters][%s][value]', $groupIndex, $key) => $item['value'],
            sprintf('searchCriteria[filterGroups][%s][filters][%s][conditionType]', $groupIndex, $key) => $item['condition_type'],
        ], $this->filters, array_keys($this->filters)));
    }

    public function greaterThan(string $field, mixed $value): self
    {
        return $this->withFilter($field, 'gt', $value);
    }

    public function greaterThanEqual(string $field, mixed $value): self
    {
        return $this->withFilter($field, 'gteq', $value);
    }
}
