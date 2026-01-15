<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TransactionFilter
{
    protected Builder $query;
    protected Request $request;

    protected $allowedFilters = [
        'type',
        'category_id'
    ];

    protected array $allowedRanges = [
        'transaction_date' => ['from', 'to'],
    ];


    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $query): Builder
    {
        $this->query = $query;

        $this->applyFilters();
        $this->applyRanges();

        return $this->query;
    }

    public function applyFilters(): void
    {
        foreach ($this->allowedFilters as $field) {
            if ($this->request->filled($field)) {
                $this->query->where($field, request($field));
            }
        }
    }

    protected function applyRanges(): void
    {
        foreach ($this->allowedRanges as $column => $range) {
            [$fromKey, $toKey] = $range;

            if ($this->request->filled($fromKey)) {
                $this->query->whereDate($column, ">=", $this->request->input($fromKey));
            }

            if ($this->request->filled($toKey)) {
                $this->query->whereDate($column, "<=", $this->request->input($toKey));
            }
        }
    }


}
