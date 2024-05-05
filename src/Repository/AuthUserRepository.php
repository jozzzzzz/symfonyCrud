<?php

namespace App\Repository;

use App\Entity\AuthUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * @extends ServiceEntityRepository<AuthUser>
* @implements PasswordUpgraderInterface<AuthUser>
 *
 * @method AuthUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuthUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuthUser[]    findAll()
 * @method AuthUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthUserRepository extends ServiceEntityRepository 
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuthUser::class);
    }

    public function login($username, $password)
    {
        $user = $this->findOneBy(['username' => $username]);

        if (!$user) {
            throw new BadCredentialsException('No user found for username ' . $username);
        }

        $isValid = $this->passwordHasher->isPasswordValid($user, $password);

        if (!$isValid) {
            throw new BadCredentialsException('Invalid password');
        }

        return $user;
    }

//    /**
//     * @return AuthUser[] Returns an array of AuthUser objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AuthUser
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
