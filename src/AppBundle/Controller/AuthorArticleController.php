<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\AuthorArticle;
use AppBundle\Form\AuthorArticleType;
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
 * @Route("/article_author")
 */
class AuthorArticleController extends Controller
{
    /**
     * @Route("/{page}", name="author_article_index", requirements={"page"="\d+"}, defaults={"page"="1"})
     * @Template("@App/AuthorArticle/index.html.twig")
     */
    public function indexAction(Request $request, $page)
    {
        $data = $this->get('doctrine')->getManager()
            ->getRepository(AuthorArticle::class)
            ->pagerfanta([], 'publishDate:desc');
        $pagerfanta = new Pagerfanta(new DoctrineORMAdapter($data));
        $pagerfanta->setMaxPerPage(5);
        $pagerfanta->setCurrentPage($page);

        return [
            'authorArticles' => $pagerfanta
        ];
    }

    /**
     * @Route("/new", name="author_article_new")
     * @Template("@App/AuthorArticle/new.html.twig")
     */
    public function newAction(Request $request)
    {
        $article = new AuthorArticle();
        /** @var Form $form */
        $form = $this->createForm(AuthorArticleType::class, $article, [
            'method' => 'POST'
        ]);

        $form->handleRequest($request);
        if ($form->isValid()) {
            /** @var EntityManager $em */
            $em = $this->get('doctrine')->getManager();
            $url = $this->get('app.get.url')->getUrl(
                $article->getName(),
                $em->getRepository(AuthorArticle::class)
            );
            $article->setUrl($url);
            $em->persist($article);
            $em->flush();
            return $this->redirect($this->generateUrl('author_article_edit', [
                'id' => $article->getId()
            ]));
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/view/{url}", name="author_article_view")
     * @Template("@App/AuthorArticle/view.html.twig")
     * @ParamConverter("article", class="AppBundle:AuthorArticle")
     */
    public function viewAction(Request $request, AuthorArticle $article)
    {
        return [
            'authorArticles' => $article
        ];
    }

    /**
     * @Route("/{id}/edit", name="author_article_edit")
     * @Template("@App/AuthorArticle/edit.html.twig")
     */
    public function editAction(Request $request, AuthorArticle $article)
    {
        $form = $this->createForm(AuthorArticleType::class, $article, [
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
