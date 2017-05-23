<?php

namespace App;

use JeroenNoten\LaravelAdminLte\Menu\Builder;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use Session;

class MyFilter implements FilterInterface
{
    public function transform($item, Builder $builder)
    {
    	if (isset($item['permission']) && Session::get('hak')!='ADMIN_ZUPER') {
            return false;
        }
        return $item;
    }
}
