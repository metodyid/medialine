<?php


namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Component\Parser\Parser;

class ParserController extends AbstractController
{

    /**
     * @Route("/parser/{page}", name="parse")
     * @param NewsRepository $newsRepository
     * @return string
     */
    public function index(NewsRepository $newsRepository, string $page, Parser $parser)
    {
        try {
                $data = $parser->getResource($page)->parse();

                if (!empty($data)) {
                    $newsRepository->truncate();
                    $count = count($data);
                    foreach ($data as $value) {
                        $new = new News();
                        $new->setTitle($value['title'])
                            ->setLink($value['link'])
                            ->setPhoto($value['photo'])
                            ->setText($value['text']);
                        $newsRepository->create($new);
                    }
                } else {
                    throw $this->createNotFoundException();
                }
        }catch (Exception $e) {
            throw new \HttpRuntimeException($e->getMessage());
        }

        return $this->render('parser.html.twig', ['count' => $count]);
    }
}