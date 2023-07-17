<?php
namespace Vanguard\Repositories\PotentialProduct;

use Vanguard\Repositories\PotentialProduct\PotentialProductRePository;
use Vanguard\PotentialProducts;

class EloquentPotentialProduct implements PotentialProductRePository
{
    /**
     * {@inheritdoc}
     */
    public function paginate($perPage = null, $search = null)
    {
        $query = PotentialProducts::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('original_title', "like", "%{$search}%");
                $q->orWhere('original_description', 'like', "%{$search}%");
                $q->orWhere('chinese_title', 'like', "%{$search}%");
                $q->orWhere('chinese_description', 'like', "%{$search}%");
            });
        }
        if(!empty($perPage)){
            $result = $query->orderBy('rank', 'desc')
                ->paginate($perPage);
        }else{
            $result = $query->orderBy('rank', 'desc');
        }

        if ($search) {
            $result->appends(['search' => $search]);
        }

        return $result;
    }

}

