<?php


namespace App\Component\Parser;

use QL\QueryList;

/**
 * Class ParserRBK
 * @package App\Component\Parser
 * @todo Внимание новости грузяться аяксом и могут быть меньше 15
 */

class ParserRbk implements ParserInterface
{
    const NEWS_LIMIT = 15;
    const URL = 'https://www.rbc.ru/';

    /**
     * @var QueryList
     */
    private $ql;

    /**
     * ParserRBK constructor.
     */
    public function __construct()
    {
        $this->ql = QueryList::get(self::URL);
    }

    /**
     * @return array
     */
    public function parse(): array
    {
        $pages = $this->getPages();
        $data = [];
        $count = 0;

        foreach ($pages as $page) {
            if ($this->isADV($page)) continue;
            if ($this->isTraffic($page)) continue;

            $data[] = $this->getPageContent($page);
            $count++;
            if ($count>self::NEWS_LIMIT) break;
        }
        return $data;
    }

    /**
     * @return array
     */
    private function getPages(): array
    {
        return $this->ql->find('.js-news-feed-list a')->attrs('href')/*->only(range(0, self::NEWS_LIMIT+self::NEWS_LIMIT))*/->all();
    }

    /**
     * @param $page
     * @return array
     */
    private function getPageContent($page): array
    {
        $ql = QueryList::get($page);

        switch ($this->getDomain($page)) {
            case 'style.rbc.ru':
                return [
                    'title' => $ql->find('.article__header')->text() ?? null,
                    'photo' => $ql->find('.article__main-image img')->src ?? null,
                    'text' => $this->getText($ql->find('.article__text')->text()) ?? null,
                    'link' => $page ?? null
                ];
            default :
                return [
                    'title' => $ql->find('.article__header__title h1')->text() ?? null,
                    'photo' => $ql->find('.article__main-image__wrap img')->src ?? null,
                    'text' => $this->getText($ql->find('.article__text')->text()) ?? null,
                    'link' => $page ?? null
                ];
        }
    }

    private function getText($text) {
        return mb_substr(urldecode($text), 0);
    }

    /**
     * @param $page
     * @return string
     */
    private function getDomain($page): string
    {
        $page = explode('/', $page);
        return $page[2] ?? '';
    }

    /**
     * @param $page
     * @return bool
     * Проверяем на рекламу
     */
    private function isADV($page): bool
    {
        return $this->getDomain($page) == 'www.adv.rbc.ru';
    }

    /**
     * @param $page
     * @return bool
     * Проверяем домен на traffic - он делает редирект
     */
    private function isTraffic($page): bool
    {
        return $this->getDomain($page) == 'traffic.rbc.ru';
    }

}