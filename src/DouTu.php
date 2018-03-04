<?php
/**
 * Created by PhpStorm.
 * User: colorrabbit
 * Date: 2018/2/14
 * Time: 11:03
 */
namespace ColorGallery;

class DouTu
{
    const DOUTU_URL = 'https://www.doutula.com/api/search';

    const MIME_ALL = 0;
    const MIME_GIF = 1;
    const MIME_JPG = 2;

    private $keyWord;

    private $mime = self::MIME_ALL;

    private $page = 1;

    public function __construct()
    {
    }

    /**
     * @param string $keyWord
     */
    public function setKeyWord($keyWord): void
    {
        $this->keyWord = $keyWord;
    }

    /**
     * @return string
     */
    public function getKeyWord(): string
    {
        return $this->keyWord;
    }

    /**
     * @param int $mime
     */
    public function setMime(int $mime): void
    {
        if ($mime === self::MIME_ALL) {
            $this->mime = self::MIME_ALL;
        }

        if ($mime === self::MIME_GIF) {
            $this->mime = self::MIME_GIF;
        }

        if ($mime === self::MIME_JPG) {
            $this->mime = self::MIME_JPG;
        }
    }

    /**
     * @return int
     */
    public function getMime(): int
    {
        return $this->mime;
    }

    public function search($page = 1)
    {
        if ($page > 50) {
            $page = 50;
        }

        $parameters = [
            'keyword' => $this->getKeyWord(),
            'mime' => $this->getMime(),
            'page' => $page,
        ];


        $curl = new Curl();

        $curl->setUrl(self::DOUTU_URL);
        try {
            $curl->setParameter($parameters);
            $res = $curl->curl();
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
        }

        return $res;
    }

    public function douTu($page)
    {
        $res = json_decode($this->search($page));
        $data = $res->data->list;

        $images = '';
        foreach ($data as $item) {
            $images .= '<img src="' . $item->image_url . '">';
        }

        return $images;
    }
}
