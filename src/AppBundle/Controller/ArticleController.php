<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Tag;
use AppBundle\Form\ArticleType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/article")
 */
class ArticleController extends Controller
{
    /**
     * @Route("/{page}", name="article_index", requirements={"page"="\d+"}, defaults={"page"="1"})
     * @Template("@App/Article/index.html.twig")
     */
    public function indexAction(Request $request, $page)
    {
        $data = $this->get('doctrine')->getManager()
            ->getRepository(Article::class)
            ->pagerfanta([], 'publishDate:desc');
        $pagerfanta = new Pagerfanta(new DoctrineORMAdapter($data));
        $pagerfanta->setMaxPerPage(5);
        $pagerfanta->setCurrentPage($page);

        return [
            'articles' => $pagerfanta
        ];
    }

    /**
     * @Route("/tag/{id}/{page}", name="article_tag", requirements={"id"="\d+", "page"="\d+"}, defaults={"page"="1"})
     * @Template("@App/Article/index.html.twig")
     */
    public function tagAction(Request $request, $id, $page)
    {
        $data = $this->get('doctrine')->getManager()
            ->getRepository(Article::class)
            ->findByTag($id);

        $pagerfanta = new Pagerfanta(new DoctrineORMAdapter($data));
        $pagerfanta->setMaxPerPage(5);
        $pagerfanta->setCurrentPage($page);

        return [
            'articles' => $pagerfanta
        ];
    }

    /**
     * @Route("/new", name="article_new")
     * @Template("@App/Article/new.html.twig")
     */
    public function newAction(Request $request)
    {
        $article = new Article();
        /** @var Form $form */
        $form = $this->createForm(ArticleType::class, $article, [
            'method' => 'POST'
        ]);

        $form->handleRequest($request);
        if ($form->isValid()) {
            /** @var EntityManager $em */
            $em = $this->get('doctrine')->getManager();
            $em->persist($article);
            $em->flush();
            return $this->redirect($this->generateUrl('article_edit', [
                'id' => $article->getId()
            ]));
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/view/{url}", name="article_view")
     * @Template("@App/Article/view.html.twig")
     * @ParamConverter("article", class="AppBundle:Article")
     */
    public function viewAction(Request $request, Article $article)
    {
        return [
            'article' => $article
        ];
    }

    /**
     * @Route("/{id}/edit", name="article_edit")
     * @Template("@App/Article/edit.html.twig")
     */
    public function editAction(Request $request, Article $article)
    {
        $form = $this->createForm(ArticleType::class, $article, [
            'method' => 'POST'
        ]);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->get('doctrine')->getManager()->flush();
        }

        return [
            'form' => $form->createView()
        ];
    }
}
