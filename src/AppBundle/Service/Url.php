<?php


namespace AppBundle\Service;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class Url
{

    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getUrl($name, EntityRepository $repository, $field = 'name')
    {
        /**
         * В целом добавляемые генерируемые значения могут быть разными
         * в зависимости от частоты и количества статей
         */
        $name_to_url = $this->trans($name) . '_' . rand(1111, 9999);

        if (!$repository->findOneBy([$field => $name_to_url])) {
            return $name_to_url;
        } else {
            return $this->getUrl($name, $repository, $field);
        }
    }

    private function trans($text)
    {
        $transliterator = \Transliterator::create('Latin');
        $text = $transliterator->transliterate($text);
        return preg_replace('/[^a-z\d_-]/i', '', $text);
    }

}