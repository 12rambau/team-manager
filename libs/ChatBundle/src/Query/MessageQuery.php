<?php

namespace btba\ChatBundle\Query;

trait MessageQuery
{
    public function countAll()
    {
        return $this->createQueryBuilder('m')
            ->select('count(m.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
