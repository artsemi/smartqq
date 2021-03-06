<?php
/*
 * This file is part of the slince/smartqq package.
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slince\SmartQQ\Request;

use GuzzleHttp\Psr7\Response;
use Slince\SmartQQ\Credential;
use Slince\SmartQQ\Message\Request\Message;

class SendMessageRequest extends Request
{
    protected $method = RequestInterface::REQUEST_METHOD_POST;

    protected $referer = 'http://d1.web2.qq.com/cfproxy.html?v=20151105001&callback=1';

    public function makeMessageParameter(Message $message, Credential $credential)
    {
        return [
            'content' => (string)$message->getContent(),
            'face' => $message->getFace(),
            'clientid' => $credential->getClientId(),
            'msg_id' => $message->getMsgId(),
            'psessionid' => $credential->getPSessionId(),
        ];
    }

    /**
     * @param Response $response
     *
     * @return bool
     */
    public static function parseResponse(Response $response)
    {
        $jsonData = \GuzzleHttp\json_decode($response->getBody(), true);
        return isset($jsonData['errCode']) && $jsonData['errCode'] === 0;
//        //由于接口返回错误但消息仍可以正常发出故此处不做判断直接返回成功
//        return true;
    }
}
