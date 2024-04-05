<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Post $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Post $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
    * @return Post Returns an array of Post objects
    */
    public function findByTitleField($value)
    {
        /*
        //Using Doctrine Query Language looks like SQL
        $query = $this->_em->createQuery(
            'SELECT p
            FROM App\Entity\Post p
            WHERE p.title = :value
            '    
        )->setParameter('value', $value);

        return $query->getResult();
        */

        
        //Using Querybuilder
        return $this->createQueryBuilder('p')
            ->andWhere('p.title = :title')
            ->setParameter('title', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;

        /*
        //Qury using SQL query
        $conn = $this->_em->getConnection();

        $sql = 'SELECT *
        FROM post p
        WHERE p.title = :value
        ';

        $stmt = $conn->prepare($sql);

        $result = $stmt->executeQuery(['value' => $value]);

        return $result->fetchOne();
        */
    }
    
    public function findByUserId($id){
        $query = $this->_em->createQuery(
            'SELECT u,p
            FROM App\Entity\Post p
            INNER JOIN p.users u 
            WHERE u.id = :id'
            )->setParameter('id', $id);

            return $query->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
