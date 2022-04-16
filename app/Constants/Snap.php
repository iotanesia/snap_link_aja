<?php

namespace App\Constants;


class Snap
{
    const CLIENT_ID = 'b0c75d7e09c54a8c9398395bb8ccb8ff';
    const PRIVATE_KEY = 'RqCZ4teYVi9eI+I81oN18BlnVl7wlXHW7uF8z2tiSFM=';
    const RSA_TYPE = 'RSA-SHA256';

    const MSG_SUCCESS = [
        'category' => 'Success',
        'httpCode'=> 200,
        'serviceCode' => 'any',
        'caseCode' => 00,
        'message' => 'Successful',
        'description' => 'Successful',
    ];
    const MSG_REQUEST_IN_PROGRESS = [
        'category' => 'Success',
        'httpCode'=> 202,
        'serviceCode' => 'any',
        'caseCode' => 00,
        'message' => 'Request In Progress',
        'description' => 'Transaction still on process',
    ];
    const MSG_BAD_REQUEST = [
        'category' => 'System',
        'httpCode'=> 400,
        'serviceCode' => 'any',
        'caseCode' => 00,
        'message' => 'Bad Request',
        'description' => 'General request failed error, including message parsing failed.',
    ];
    const MSG_INVALID_FIELD_FORMAT = [
        'category' => 'Message',
        'httpCode'=> 400,
        'serviceCode' => 'any',
        'caseCode' => 01,
        'message' => 'Invalid Field Format {field name}',
        'description' => 'Invalid format',
    ];
    const MSG_INVALID_MANDTORY_FORMAT = [
        'category' => 'Message',
        'httpCode'=> 400,
        'serviceCode' => 'any',
        'caseCode' => 02,
        'message' => 'Invalid Mandatory Field {field name}',
        'description' => 'Missing or invalid format on mandatory field',
    ];
    const MSG_UNAUTHORIZED = [
        'category' => 'System',
        'httpCode'=> 401,
        'serviceCode' => 'any',
        'caseCode' => 00,
        'message' => 'Unauthorized. [reason]',
        'description' => 'General unauthorized error (No Interface Def, API is Invalid, Oauth Failed, Verify Client Secret Fail, Client Forbidden Access API, Unknown Client, Key not Found)',
    ];
    const MSG_INVALID_TOKEN_B2B = [
        'category' => 'System',
        'httpCode'=> 401,
        'serviceCode' => 'any',
        'caseCode' => 01,
        'message' => 'Invalid Token (B2B)',
        'description' => 'Token found in request is invalid (Access Token Not Exist, Access Token Expiry)',
    ];
    const MSG_CUSTOMER_TOKEN = [
        'category' => 'System',
        'httpCode'=> 401,
        'serviceCode' => 'any',
        'caseCode' => 02,
        'message' => 'Invalid Customer Token',
        'description' => 'Token found in request is invalid (Access Token Not Exist, Access Token Expiry)',
    ];
    const MSG_NOT_FOUND_B2B_TOKEN = [
        'category' => 'System',
        'httpCode'=> 401,
        'serviceCode' => 'any',
        'caseCode' => 03,
        'message' => 'Token Not Found (B2B)',
        'description' => 'Token not found in the system. This occurs on any API that requires token as input parameter',
    ];
    const MSG_NOT_FOUND_CUSTOMER_TOKEN = [
        'category' => 'System',
        'httpCode'=> 401,
        'serviceCode' => 'any',
        'caseCode' => 04,
        'message' => 'Customer Token Not Found',
        'description' => 'Token not found in the system. This occurs on any API that requires token as input parameter',
    ];

}

