<?php

namespace App\Repository;

use App\Entity\Secret;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Secret>
 *
 * @method Secret|null find($id, $lockMode = null, $lockVersion = null)
 * @method Secret|null findOneBy(array $criteria, array $orderBy = null)
 * @method Secret[]    findAll()
 * @method Secret[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SecretRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Secret::class);
    }

    public function add(Secret $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Secret $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneActiveSecretByHash(string $hash) {
        $qb = $this->createQueryBuilder("s")
            ->where("s.expiresAt > CURRENT_TIMESTAMP()")
            ->andWhere("s.remainingViews > 0")
            ->andWhere("s.hash = '$hash'");

        $result = $qb->getQuery()->execute();

        $this->updateReminingViews($result);

        return $result;
    }

	public function findAllActiveSecrets() {
		$qb = $this->createQueryBuilder("s")
			->where("s.expiresAt > CURRENT_TIMESTAMP()")
            ->andWhere("s.remainingViews > 0");

        $result = $qb->getQuery()->execute();

        $this->updateReminingViews($result);

		return $result;
	}

    private function updateReminingViews(array $secretArray) {
        foreach ($secretArray as $secret) {
            $secret->setRemainingViews($secret->getRemainingViews() - 1);
            $this->getEntityManager()->persist($secret);
        }

        $this->getEntityManager()->flush();
    }
}
