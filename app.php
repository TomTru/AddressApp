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
            
            if (stripos($address, 'http') === false) {
                $address = 'http://' . $address;
            }

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
        return $request;
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

}