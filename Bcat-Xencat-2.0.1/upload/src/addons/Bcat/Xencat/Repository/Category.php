<?php

namespace Bcat\Xencat\Repository;

use XF\Mvc\Entity\AbstractCollection;
use XF\Mvc\Entity\Repository;

class Category extends Repository
{
	public function findCategory()
	{
		return $this->finder('Bcat\Xencat:Category')
			->order('category_name');
	}
}
