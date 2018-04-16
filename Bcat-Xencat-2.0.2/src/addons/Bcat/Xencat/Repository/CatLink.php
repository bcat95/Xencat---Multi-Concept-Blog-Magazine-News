<?php

namespace Bcat\Xencat\Repository;

use XF\Mvc\Entity\AbstractCollection;
use XF\Mvc\Entity\Repository;

class CatLink extends Repository
{
	public function findCatLink()
	{
		return $this->finder('Bcat\Xencat:CatLink')
			->with('Category', true)
			->order('Category.category_name');
	}

	public function updateCatlinksByThreadId($id, $rows)
	{
		foreach ($rows AS $key => &$row)
		{
			$row = [
				'category_id' => $row,
				'thread_id' => $id,
			];
		}

		$this->db()->delete('bc_xencat_catlinks', 'thread_id = ?', $id);

		if (!empty($rows))
		{
			$this->db()->insertBulk('bc_xencat_catlinks', $rows);
		}

		return;
	}
}
