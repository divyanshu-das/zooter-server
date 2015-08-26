<?php
App::uses('Component', 'Controller');

Class ZooterResponseComponent extends Component {

    public function prepareResponse($response, $api, $action){
        $data = am(array('api' => $api, 'action' => $action), $response);
        return json_encode($data);
    }

    public function respondError($errorCode = 400, $errorMessage = 'Bad Request', $api = ''){
        $responseData = array(
            'status' => $errorCode,
            'message' => $errorMessage,
            'api' => $api
        );
        echo json_encode($responseData);
        exit;
    }

    public function getUnassignedStatusCode(){
        return '007';
    }

    public function respondBadRequestError($api = '', $message = 'Bad Request'){
        return $this->respondError(400, $message, $api);
    }

    public function respondAuthorizationRequiredError($message = 'Authorization Required'){
        return $this->respondError(401, $message);
    }

    public function respondForbiddenRequestError($message = 'Forbidden Request'){
        return $this->respondError(402, $message);
    }

    public function respondMethodNotAllowed($message = 'Method not allowed'){
        return $this->respondError(405, $message);
    }

    public function respondDoesNotExistError($message = 'Does Not Exist'){
        return $this->respondError(3000, $message);
    }

    public function respondVoucherInActiveError($message = 'Voucher is InActive'){
        return $this->respondError(3100, $message);
    }

    public function respondUserNotEligibleError($message = 'User Not eligible'){
        return $this->respondError(3200, $message);
    }

    public function respondUserDoesNotExistError($message = 'User Does Not Exist'){
        return $this->respondError(1100, $message);
    }


    public function getErrorResponse($result){
        $response = array('status' => $result['api_return_code'], 'message' => $result['message']);
        return $response;
    }
}