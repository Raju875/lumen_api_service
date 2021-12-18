<?php


namespace App\Libraries;

use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class CommonFunction
{
    /**
     * @param string $message
     * @param string $response_type
     * @param int $status_code
     * @param $data
     *
     * @return array
     */
    public static function dataResponse($message = '', $response_type, $status_code = HttpResponse::HTTP_BAD_REQUEST, $data = '')
    {
        $response['jatriResponse'] = [
            'responseTime' => time(),
            'responseType' => $response_type,
            'responseCode' => $status_code,
            'responseData' => $data,
            'message' => $message
        ];

        return $response;
    }

    /**
     * @param array $input_data
     * @param array $rules
     * @param string $type
     *
     * @return array
     */
    public static function inputValidationCheck(array $input_data, array $rules, $type = '')
    {
        $validator = Validator::make($input_data, $rules);
        if ($validator->fails()) {
            $messages = [];
            foreach ($validator->messages()->getMessages() as $key => $value) {
                $messages[] = ['field' => $key, 'error' => $value[0]];
            }
            return [
                'validation' => false,
                'error' => response()->json(CommonFunction::dataResponse($messages, $type, HTTPResponse::HTTP_UNAUTHORIZED))
            ];
        }
        return ['validation' => true, 'error' => ''];
    }
}
