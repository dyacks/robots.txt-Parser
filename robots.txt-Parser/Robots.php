<?php

class Robots
{
    private $robotsUrl = '';
    private $content = [];
    private $color = '';
    private $arrHeaders = [];
    private $stringFile = '';
    private $derectivehostCount = '0';
    private $serverCode = '';
    private $fileSize = '';
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

        if ($host) {
            $this->robotsUrl = $url . '/robots.txt';
        }

        ini_set('display_errors', 'Off');
        $this->stringFile = file_get_contents($this->robotsUrl);
        $this->arrHeaders = $http_response_header;
        $this->content = explode("\n", $this->stringFile);
        ini_set('display_errors', 'On');
        $this->content = array_diff($this->content, array(''));

        $this->setServerCode();
    }

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

    public function getContent()
    {
        $streamInfo = $this->getStreamInfo();
        $this->setFileSize($streamInfo);
        $this->setDerectiveHostCount();
    }

    public function getStatus($i)
    {
        switch ($i) {
            case 0:
                if ($this->getCountLineFile() != 0) {
                    $this->color = 'green';
                    return true;
                } else {
                    $this->color = 'red';
                    return false;
                }
                break;
            case 1:
                if ($this->getServerCode() == 200) {
                    $this->color = 'green';
                    return true;
                } else {
                    $this->color = 'red';
                    return false;
                }
            case 2:
                if (!$this->derectivehostCount == 0) {
                    $this->color = 'green';
                    return true;
                } else {
                    $this->color = 'red';
                    return false;
                }
                break;
            case 3:
                if ($this->derectivehostCount == 1) {
                    $this->color = 'green';
                    return true;
                } else {
                    $this->color = 'red';
                    return false;
                }
                break;
            case 4:
                if ($this->getFileSize() <= 32000) {
                    $this->color = 'green';
                    return true;
                } else {
                    $this->color = 'red';
                    return false;
                }
                break;
            case 5:
                if ($this->isDerectiveSitemapPresent()) {
                    $this->color = 'green';
                    return true;
                } else {
                    $this->color = 'red';
                    return false;
                }
        }
    }

    public function getTask($i)
    {
        return $this->task[$i];
    }

    public function isDerectiveSitemapPresent()
    {
        foreach ($this->content as $c) {
            if (preg_match("/Sitemap:/i", $c)) {
                return true;
            }
        }
        return false;
    }

    public function getStreamInfo()
    {
        $fp = fopen($this->robotsUrl, "r");
        $inf = stream_get_meta_data($fp);
        fclose($fp);
        return $inf;
    }

    public function setServerCode()
    {
        $code = get_headers($this->getRobotsURL());
        foreach ($code as $c) {
            if (preg_match("/HTTP/", $c)) {
                $tmp = $c;
            }
        }
        $this->serverCode = substr($tmp, 9);
        $this->falseState[1] = "При обращении к файлу robots.txt сервер возвращает код ответа $this->serverCode";
        $this->trueState[1] = "Файл robots.txt отдаёт код ответа сервера $this->serverCode";
    }

    public function getServerCode()
    {
        return $this->serverCode;
    }

    public function setFileSize($infF)
    {
        foreach ($infF["wrapper_data"] as $v) {
            if (stristr($v, "content-length")) {
                $s = explode(":", $v);
                $this->fileSize = trim($s[1]);
                $this->trueState[4] = "Размер файла robots.txt составляет $this->fileSize байт, что находится в пределах допустимой нормы";
                $this->falseState[4] = "Размера файла robots.txt составляет $this->fileSize байт, что превышает допустимую норму";
                return true;
            }
        }
    }

    public function getFileSize()
    {
        return $this->fileSize;
    }

    public function setDerectiveHostCount()
    {
        foreach ($this->content as $c) {
            if (preg_match_all("/Host:/i", $c)) {
                $this->derectivehostCount += 1;
            }
        }
    }

    public function getDerectiveHostCount()
    {
        return $this->derectivehostCount;
    }

    public function getState($i, $status)
    {
        if ($status) {
            return $this->trueState[$i];
        } else {
            return $this->falseState[$i];
        }
    }

    public function getRecomendet($i, $status)
    {
        if ($status) {
            return $this->trueRecomendet;
        } else {
            return $this->falseRecomendet[$i];
        }
    }

    public function getCountLineFile()
    {
        return count($this->content);
    }

    public function getRobotsURL()
    {
        return $this->robotsUrl;
    }

    public function getColor()
    {
        return $this->color;
    }

}