<?php

namespace Melodia\FileBundle\Entity;

use Doctrine\ORM\EntityRepository;

class FileRepository extends  EntityRepository
{
    /**
     * @param integer $page
     * @param integer $limit
     * @param array $order Sorting options
     *
     * Format of the $order argument:
     *      $order = array(
     *          array(
     *              'property' = > 'createdAt',
     *              'direction' => 'ASC',
     *          ),
     *      )
     *
     * @return array
     */
    public function findSubset($page, $limit, $order = array())
    {
        $qb = $this->createQueryBuilder('entity');

        foreach ($order as $set) {
            if (isset($set['property']) && isset($set['direction'])) {
                $qb->orderBy('entity.' . $set['property'], $set['direction']);
            }
        }

        $qb->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }
}
