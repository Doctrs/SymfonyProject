<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Article;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ServiceUrlTest extends WebTestCase
{
    /** @var ContainerInterface */
    private $container;

    public function setUp()
    {
        self::bootKernel();
        $this->container = self::$kernel->getContainer();
    }

    public function testUrl()
    {
        $metadata = $this->createMock(Mapping\ClassMetadata::class);
        $em = $this->createMock(EntityManager::class);
        $test_class = new class($em, $metadata) extends EntityRepository {
            public $count = 0;
            public function __construct(EntityManager $em, Mapping\ClassMetadata $class)
            {
                parent::__construct($em, $class);
            }

            /**
             * Имитируем находение одинаковых url
             */
            public function findOneBy(array $criteria, array $orderBy = null){
                $this->count ++;
                return $this->count >= 5 ? false : true;
            }
        };
        $data = $this->container->get('app.get.url')->getUrl('ЙЦУЕКНарвоад57593№№*?"!__--', $test_class);


        /**
         * Правильно ли перевелось
         */
        $this->assertEquals(
            'JCUEKNarvoad57593__--_' /** тут 4 случайных цифры **/,
            preg_replace('/(.*)\d{4}/', '$1', $data)
        );
        /**
         * Отработало ли оно 5 раз
         */
        $this->assertEquals(5, $test_class->count);
    }
}
