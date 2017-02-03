<?php

namespace App\Classes;

/**
 * Class for parsing robots.txt file
 * @author Dyachuk Sergey
 */

class Robots
{
    private $robotsUrl = '';
    private $content = [];
    private $color = '';
    private $arrHeaders = [];
    private $derectivehostCount = 0;
    private $serverCode = '';
    private $fileSize = 0;
    private $task = [
        'Проверка наличия файла robots.txt',
        'Проверка кода ответа сервера для файла robots.txt',
        'Проверка указания директивы Host',
        'Проверка количества директив Host, прописанных в файле',
        'Проверка размера файла robots.txt',
        'Проверка указания директивы Sitemap',
    ];
    private $trueState = array(
        'Файл robots.txt присутствует',
        'Файл robots.txt отдаёт код ответа сервера 200',
        'Директива Host указана',
        'В файле прописана 1 директива Host',
        'Размер файла robots.txt составляет __, что находится в пределах допустимой нормы',
        'Директива Sitemap указана',
    );
    private $falseState = [
        'Файл robots.txt отсутствует',
        'При обращении к файлу robots.txt сервер возвращает код ответа (указать код)',
        'В файле robots.txt не указана директива Host',
        'В файле прописано несколько директив Host',
        'Размера файла robots.txt составляет __, что превышает допустимую норму',
        'В файле robots.txt не указана директива Sitemap',
    ];
    private $trueRecomendet = 'Доработки не требуются';
    private $falseRecomendet = [
        'Программист: Создать файл robots.txt и разместить его на сайте.',
        'Программист: Файл robots.txt должны отдавать код ответа 200, иначе файл не будет обрабатываться. Необходимо настроить сайт таким образом, чтобы при обращении к файлу sitemap.xml сервер возвращает код ответа 200',
        'Программист: Для того, чтобы поисковые системы знали, какая версия сайта является основных зеркалом, необходимо прописать адрес основного зеркала в директиве Host. В данный момент это не прописано. Необходимо добавить в файл robots.txt директиву Host. Директива Host задётся в файле 1 раз, после всех правил.',
        'Программист: Директива Host должна быть указана в файле толоко 1 раз. Необходимо удалить все дополнительные директивы Host и оставить только 1, корректную и соответствующую основному зеркалу сайта',
        'Программист: Максимально допустимый размер файла robots.txt составляем 32 кб. Необходимо отредактировть файл robots.txt таким образом, чтобы его размер не превышал 32 Кб',
        'Программист: Добавить в файл robots.txt директиву Sitemap',
    ];

    public function __construct($url)
    {
        $host = parse_url($url, PHP_URL_HOST);
        if ($host) $this->robotsUrl = $url . '/robots.txt';
        ini_set('display_errors', 'Off');
        $stringFile = file_get_contents($this->robotsUrl);
        $this->arrHeaders = $http_response_header;
        $this->content = explode("\n", $stringFile);
        $this->content = array_diff($this->content, array(''));
        $this->setServerCode();
    }

    /**
     * @param string $url
     * @return int
     */
    public static function isURL($url)
    {
        $w = "a-z0-9";
        $urlPattern = "#( 
        (?:f|ht)tps?://(?:www.)? 
        (?:[$w\\-.]+/?\\.[a-z]{2,4})/? 
        (?:[$w\\-./\\#]+)? 
        (?:\\?[$w\\-&=;\\#]+)? 
        )#xi";

        return preg_match($urlPattern, $url);
    }

    /**
     * @return void
     */
    public function setContent()
    {
        $streamInfo = $this->getStreamInfo();
        $this->setFileSize($streamInfo);
        $this->setDerectiveHostCount();
    }

    /**
     * @param int $i
     * @return bool
     */
    public function getStatus($i)
    {
        switch ($i) {
            case 0:
                if ($this->getCountLineFile() != 0)
                    return $this->setColor(true);
                return $this->setColor();
            case 1:
                if ($this->getServerCode() == 200)
                    return $this->setColor(true);
                return $this->setColor();
            case 2:
                if (!$this->derectivehostCount == 0)
                    return $this->setColor(true);
                return $this->setColor();
            case 3:
                if ($this->derectivehostCount == 1)
                    return $this->setColor(true);
                return $this->setColor();
            case 4:
                if ($this->getFileSize() <= 32000)
                    return $this->setColor(true);
                return $this->setColor();
            case 5:
                if ($this->isDerectiveSitemapPresent())
                    return $this->setColor(true);
                return $this->setColor();
        }
    }

    /**
     * @param bool $color
     * @return bool
     */
    public function setColor($color = false)
    {
        $this->color = ($color) ? 'green' : 'red';
        return $color;
    }

    /**
     * @param int $i
     * @return string
     */
    public function getTask($i)
    {
        return $this->task[$i];
    }

    /**
     * @return bool
     */
    public function isDerectiveSitemapPresent()
    {
        foreach ($this->content as $c) {
            if (preg_match("/Sitemap:/i", $c)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return array
     */
    public function getStreamInfo()
    {
        $fp = fopen($this->robotsUrl, "r");
        $inf = stream_get_meta_data($fp);
        fclose($fp);
        return $inf;
    }

    /**
     * @return void
     */
    public function setServerCode()
    {
        foreach ($this->arrHeaders as $c)
            if (preg_match("/HTTP/", $c)) $tmp = $c;
        if ($tmp) {
            $this->serverCode = substr($tmp, 9);
            $this->falseState[1] = "При обращении к файлу robots.txt сервер возвращает код ответа $this->serverCode";
            $this->trueState[1] = "Файл robots.txt отдаёт код ответа сервера $this->serverCode";
        }
    }

    /**
     * @return string
     */
    public function getServerCode()
    {
        return $this->serverCode;
    }

    /**
     * @param $inf
     * @return bool
     */
    public function setFileSize($inf)
    {
        foreach ($inf["wrapper_data"] as $v) {
            if (stristr($v, "content-length")) {
                $s = explode(":", $v);
                $this->fileSize = trim($s[1]);
                $this->trueState[4] = "Размер файла robots.txt составляет $this->fileSize байт, что находится в пределах допустимой нормы";
                $this->falseState[4] = "Размера файла robots.txt составляет $this->fileSize байт, что превышает допустимую норму";
                return true;
            }
        }
    }

    /**
     * @return int
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * @return void
     */
    public function setDerectiveHostCount()
    {
        foreach ($this->content as $c) {
            if (preg_match("/Host:/i", $c)) {
                $this->derectivehostCount++;
            }
        }
    }

    /**
     * @return int
     */
    public function getDerectiveHostCount()
    {
        return $this->derectivehostCount;
    }

    /**
     * @param int $i
     * @param bool $status
     * @return string
     */
    public function getState($i, $status)
    {
        if ($status) {
            return $this->trueState[$i];
        } else {
            return $this->falseState[$i];
        }
    }

    /**
     * @param int $i
     * @param bool $status
     * @return string
     */
    public function getRecomendet($i, $status)
    {
        if ($status) {
            return $this->trueRecomendet;
        } else {
            return $this->falseRecomendet[$i];
        }
    }

    /**
     * @return int
     */
    public function getCountLineFile()
    {
        return count($this->content);
    }

    /**
     * @return string
     */
    public function getRobotsURL()
    {
        return $this->robotsUrl;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

}