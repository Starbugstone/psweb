<?php

namespace App\Service;

use App\Entity\CookieJar;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class CookieJarService
{
    private EntityManagerInterface $em;
    private int $cookieJarTimeout = 600; //in seconds

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getLastCookieJar(): ?CookieJar
    {
        return $this->em->getRepository(CookieJar::class)->findOneBy([], ['id' => 'DESC']);
    }

    public function getCookieJarHistory(int $limit = 5): array
    {
        return $this->em->getRepository(CookieJar::class)->findBy([], ['id' => 'DESC'], $limit);
    }

    public function isCookieJarAvailable(): bool
    {
        $cookieJar = $this->getLastCookieJar();
        if ($cookieJar === null) {
            return true;
        }

        if ($cookieJar->getLastCookieDateTime() <= new DateTime('-' . $this->cookieJarTimeout . ' seconds')) {
            return true;
        }

        return false;
    }

    public function nextCookieAvailable()
    {
        $cookieJar = $this->getLastCookieJar();
        if ($cookieJar === null) {
            return new DateTime();
        }

        return $cookieJar->getLastCookieDateTime()->modify('+' . $this->cookieJarTimeout . ' seconds');
    }
}