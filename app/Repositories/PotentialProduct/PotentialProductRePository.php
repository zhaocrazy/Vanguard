<?php

namespace Vanguard\Repositories\PotentialProduct;

use Carbon\Carbon;
use Vanguard\PotentialProducts;

interface PotentialProductRePository
{
    /**
    `* Paginate PotentialProduct.
     *
     * @param $perPage
     * @param null $search
     * @param null $status
     * @return mixed
     */
    public function paginate($perPage, $search = null);

}
