<?php

if (!empty($_POST)) {
    $method = $_POST['method'];
    $reqChecker = new ReqChecker();
    echo json_encode($reqChecker->{$method}($_POST));
}


class ReqChecker {
    public function checkFirst($request) {
        $codes = [];
        $addresses = $request['data'];

        foreach ($addresses as $address) {
            $url = 'http://' . $address;
            $check = get_headers($url);
            $code = explode(' ', $check[0])[1];
            $codes[$address]['code'] = $code;

            if ($code === '301') {
                $codes[$address]['movedTo'] = 'test';
            }

        }

        return $codes;
    }

    public function checkSecond($request) {
        return $request;
    }

    public function checkThird($request) {
        return $request;
    }

}