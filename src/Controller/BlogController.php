<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\NewsFormType;

class BlogController extends AbstractController
{
    const POST_LIMIT = 10;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $blogPostRepository;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $authorRepository;


    /**
     * BlogController constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->blogPostRepository = $entityManager->getRepository('App:BlogPost');
        $this->authorRepository = $entityManager->getRepository('App:Author');
    }

    /**
     * @Route("/news", name="news")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function newsAction(Request $request)
    {
        $page = 1;

        if ($request->get('page')) {
            $page = $request->get('page');
        }

        return $this->render('blog/news.html.twig', [
            'blogPosts' => $this->blogPostRepository->getAllPosts($page, self::POST_LIMIT),
            'totalBlogPosts' => $this->blogPostRepository->getPostCount(),
            'page' => $page,
            'entryLimit' => self::POST_LIMIT
        ]);
    }

    /**
     * @Route("/admin", name="admin_index")
     * @Route("/admin/news", name="admin_news")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newsAdminAction()
    {
        $author = $this->authorRepository->find(1);

        $blogPosts = [];

        if ($author) {
            $blogPosts = $this->blogPostRepository->findBy(['author' => $author]);
        }

        return $this->render('admin/news.html.twig', [
            'blogPosts' => $blogPosts
        ]);
    }

    /**
     * @Route("/create-news", name="create_news")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createNewsAction(Request $request)
    {
        $blogPost = new BlogPost();

        $author = $this->authorRepository->find(1);
        $blogPost->setAuthor($author);

        $form = $this->createForm(NewsFormType::class, $blogPost);
        $form->handleRequest($request);

        // Check is valid
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($blogPost);
            $this->entityManager->flush();

            $this->addFlash('success', 'Congratulations! Your post is created');

            return $this->redirectToRoute('admin_news');
        }

        return $this->render('Admin/news_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete-news/{newsId}", name="delete_news")
     *
     * @param $newsId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteNewsAction($newsId)
    {
        $blogPost = $this->blogPostRepository->find($newsId);
        $author = $this->authorRepository->find(1);

        if (!$blogPost || $author !== $blogPost->getAuthor()) {
            $this->addFlash('error', 'Unable to remove entry!');

            return $this->redirectToRoute('admin_news');
        }

        $this->entityManager->remove($blogPost);
        $this->entityManager->flush();

        $this->addFlash('success', 'Entry was deleted!');

        return $this->redirectToRoute('admin_news');
    }
}
