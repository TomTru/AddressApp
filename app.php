<?php

if (!empty($_POST)) {
    $method = $_POST['method'];
    $reqChecker = new ReqChecker();
    echo json_encode($reqChecker->{$method}($_POST));
}


class ReqChecker {
    public function checkFirst($request) {

        if(!$request['data']) {
            return '';
        }

        $codes = [];
        $addresses = $request['data'];

        foreach ($addresses as $address) {
            $address = $this->checkProtocolExists($address);
            $check = get_headers($address);
            $code = explode(' ', $check[0])[1];
            $codes[$address]['code'] = $code;

            if ($code === '301') {
                $codes[$address]['movedTo'] = $this->getLocation($check);
            }
        }

        return $codes;
    }

    public function checkSecond($request) {
        if (!$request['data']) {
            return '';
        }

        $resp = [];
        foreach ($request['data'] as $item) {
            $parts = explode(';', $item);
            $url = $this->checkProtocolExists($parts[2]);
            $htmlContent = file_get_contents($url);

            if ($htmlContent) {
                $domContent = new DOMDocument();
                @$domContent->loadHTML($htmlContent);
                $anchors = $domContent->getElementsByTagName('a');
                $foundedItems = [];
                foreach($anchors as $a) {
                    $trimmedKeyword = trim($a->nodeValue);
                    if ($parts[1] === $trimmedKeyword && $parts[0] === $a->getAttribute("href")) {
                        $tItem = utf8_decode('Link: "' . $parts[2] . '" zawiera adres "' . $parts[0] . '" i słowo kluczowe "' . $parts[1] . '"');
                        $foundedItems[] = $tItem;
                        $resp[] = $tItem;
                    }
                }

            }

            if (count($foundedItems) === 0) {
                $resp[] = utf8_decode('Link: "' . $parts[2] . '" nie zawiera adresu "' . $parts[0] . '" i słowa kluczowego "' . $parts[1] . '"');
            }

        }

        return $resp;
    }

    public function checkThird($request) {
        if (!$request['data']) {
            return '';
        }

        $resp = [];
        foreach ($request['data'] as $item) {
            $parts = explode(';', $item);
            $url = $this->checkProtocolExists($parts[0]);
            $htmlContent = file_get_contents($url);

            if ($htmlContent) {
                $domContent = new DOMDocument();
                @$domContent->loadHTML($htmlContent);
                $anchors = $domContent->getElementsByTagName('a');
                $foundedItems = [];
                foreach($anchors as $a) {
                    $trimmedKeyword = trim($a->nodeValue);
                    if ($parts[1] === $trimmedKeyword) {
                        $tItem = utf8_decode('<a href="' . $a->getAttribute("href") . '">' . $parts[1] . '</a>');
                        $foundedItems[] = $tItem;
                        $resp[] = $tItem;
                    }
                }
            }

            if (count($foundedItems) === 0) {
                $resp[] = "'" . $parts[0] . "': nie znaleziono linka '" . $parts[1] . "'";
            }
        }

        return $resp;
    }

    protected function getLocation($respArr) {
        foreach ($respArr as $item) {
            if(strpos($item, 'Location:') !== false) {
                return $item;
            }
        }
        return 'brak';
    }

    protected function checkProtocolExists($url) {
        if (stripos($url, 'http') === false) {
            $url = 'http://' . $url;
        }
        return $url;
    }

    protected function makeEmptyResult() {

    }

}