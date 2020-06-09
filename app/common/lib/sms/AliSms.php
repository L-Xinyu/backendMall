<?php
/**
 * create by XinyuLi
 * @since 09/06/2020 21:36
 */

declare(strict_types=1);
namespace app\common\lib\sms;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class AliSms
{
    /**
     * Aliyun send messageCode
     * @param string $phone
     * @param int $code
     * @return bool 表示短信发送成功或失败
     * @throws ClientException
     */
    public static function sendCode(string $phone, int $code): bool
    {
        if (empty($phone) || empty($code)) {
            return false;
        }

        AlibabaCloud::accessKeyClient(config('aliyun.access_key_id'),config('aliyun.access_key_secret'))
            ->regionId(config('aliyun.region_id'))
            ->asDefaultClient();

        $templateParam = [
            'code' => $code,
        ];
        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host(config('aliyun.host'))
                ->options([
                    'query' => [
                        'RegionId' => "cn-hangzhou",
                        'PhoneNumbers' => $phone,
                        'SignName' => config('aliyun.sign_name'),
                        'TemplateCode' => config('aliyun.template_code'),
                        'TemplateParam' => json_encode($templateParam),
                    ],
                ])
                ->request();
            print_r($result->toArray());
        } catch (ClientException $e) {
            return false;
            //echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            return false;
            //echo $e->getErrorMessage() . PHP_EOL;
        }
        return true;
    }
}