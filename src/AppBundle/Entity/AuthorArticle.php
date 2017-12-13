<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AuthorArticle
 *
 * @ORM\Table(name="author_article")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AuthorArticleRepository")
 */
class AuthorArticle extends BaseArticle
{
    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    protected $author;

    /**
     * @var string
     *
     * @ORM\Column(name="site", type="string", length=255)
     */
    protected $site;

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return AuthorArticle
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get site
     *
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set site
     *
     * @param string $site
     *
     * @return AuthorArticle
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }
}

