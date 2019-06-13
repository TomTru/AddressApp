<?php

if (!empty($_POST)) {
    $method = $_POST['method'];
    $reqChecker = new ReqChecker();
    echo json_encode($reqChecker->{$method}($_POST));
}


class ReqChecker {
    public function checkFirst($request) {

        if(!$request['data']) {
            return false;
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
            return false;
        }

        $resp = [];
        foreach ($request['data'] as $item) {
            $parts = explode(';', $item);
            $url = $this->checkProtocolExists($parts[2]);
            $htmlContent = file_get_contents($url);
            $resp[] = $htmlContent;
        }

        return $resp;
    }

    public function checkThird($request) {
        return $request;
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

}