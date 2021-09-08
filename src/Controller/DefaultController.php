<?php


namespace App\Controller;


use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="index")
     * @return string
     */
    public function index(NewsRepository $newsRepository)
    {
        $news = $newsRepository->findFirst15();
        return $this->render('index.html.twig', ['news' => $news]);
    }

    /**
     * @Route("/news/{id}", name="news")
     * @param NewsRepository $newsRepository
     * @param int $id
     * @return string
     */
    public function news(NewsRepository $newsRepository, int $id)
    {
        $new = $newsRepository->findOneBy(['id' => $id]);
        return $this->render('news.html.twig', ['new' => $new]);
    }
}