<?php

namespace App\Service;

use App\Entity\CookieJar;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class CookieJarService
{
    private EntityManagerInterface $em;
    private int $cookieJarTimeout = 10;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getCookieJar(): ?CookieJar
    {
        return $this->em->getRepository(CookieJar::class)->findOneBy([], ['id' => 'DESC']);
    }

    public function isCookieJarAvailable(): bool
    {
        $cookieJar = $this->getCookieJar();
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
        $cookieJar = $this->getCookieJar();
        if ($cookieJar === null) {
            return new DateTime();
        }

        return $cookieJar->getLastCookieDateTime()->modify('+' . $this->cookieJarTimeout . ' seconds');
    }
}