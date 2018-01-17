<?php

return [
    'alipay' => [
        // 支付宝提供的 APP_ID
        'app_id' => '2017102709554378',
        //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
        'ali_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAydSzno1dQt7CIR5figIDGC5uYzje5C6ISugeZZSlMoSpSwCpA82Ackxm7YXpucJncVeJ32YsULtpJ5D+ybGrvV2j8wAWFLB5/ewR8sKExF8oYXc+j5Hmg7r8UHw51gGthcvcwj/O90KEDP5JWN9bD6EA0BlGXai72avPcql+gFde/1anc+1Rfi9AUip3jSXLU1+zR4zNXI97CSUcaFAasvA72/nOBdmKmK9Cfd4f5qT5bI485sabpkpw56W2ANuenxb5oirQSnoytTlcGnOdxujPOAKCrrej27Jwp6EHb0sQdIPamMqbTNYAPyFgQSSjrAfPF7QOn7kOacnlS3b1fwIDAQAB",
        //商户私钥, 请把生成的私钥文件中字符串拷贝在此
        'private_key' => "MIIEowIBAAKCAQEAydSzno1dQt7CIR5figIDGC5uYzje5C6ISugeZZSlMoSpSwCpA82Ackxm7YXpucJncVeJ32YsULtpJ5D+ybGrvV2j8wAWFLB5/ewR8sKExF8oYXc+j5Hmg7r8UHw51gGthcvcwj/O90KEDP5JWN9bD6EA0BlGXai72avPcql+gFde/1anc+1Rfi9AUip3jSXLU1+zR4zNXI97CSUcaFAasvA72/nOBdmKmK9Cfd4f5qT5bI485sabpkpw56W2ANuenxb5oirQSnoytTlcGnOdxujPOAKCrrej27Jwp6EHb0sQdIPamMqbTNYAPyFgQSSjrAfPF7QOn7kOacnlS3b1fwIDAQABAoIBAFZksuJalqIqIiT1EGZNyC9QqLXWQSqhdHvD8kb/ItuGB8VwKejTzXiflat0mACI46iTlRPIc423OyLRoQ6K+/2aGLTKw6jlhcKYi3JDq3s3VFysI15nUKnwn1KAam1d2H4eLogDsj8K/OVm79SX/y2oeGWbfBBZSte5AJqPTPP4L7CBpCe7zz2JlxEhUMiHNAwemkKR9PIuXtDY/PoKq97oiV6UdeCEq+K3ULRp0YVaOykN46N37l1eU2BGayPS/VjVWtG3hPwVAt4YRT0ggUVw5oqSaNJhw4t+i/YANpSeJHNXFwWs8UXSv9ineiByqF/Y6ExlG/ajYnZi1qk3RakCgYEA6BA/088jNb/FHYa3WeWZ5a6QkdovxtqftdNt6Td0L41JnuBFpeQrtqrDVaQKh3PbSTLLTJp+G+xbqWtZIRXX1aJ81NwtIpNYJWbB4TTPNrtgmsZUwDtFSeSB4MTfOztF8ytw7aIAeXKDpTjfTlEqptKkFjyPyzNLH0H1bNz1fxMCgYEA3qYlNjSp7DSaANKeb47aTtTZcGOpMSQaviUG1y1lAP0Jy/FBqbu5FctXySdxqQvYKn8pPUAyGzSMAZnU11TxgUeNorlbqBRS0c6GV7SFgj+4PfnZ5Xxpawvbufoimw3qAG0Kw5mScsotp82tMCvQn3+8u1EyE4Q8Io2PKURIQWUCgYEAxyEKZAoeQITp88ghlPY79KayTzVWQeBfo3plmfWHsTYfskDoAs2j5P7q1pYIoup7hHgdkwIOrI9IqDwBIy7HIf5n949m+4BL5uwh+cmC9YgcATOmjb1OW3XFLMZCd2UJRPxFzuNwXMYyyJfgpidn3fZp7trQ2KCPGadTnbKMxhkCgYBsvMn5sji2wm3gLvzv0N2vmGZlbFbqSA7DhIPVvTbSB8KsFrSR97uJ+Fh0Rk5NWBqHjrkOT/bosH4cHgwD6llUYSS8LzijWuGj0/BqjyCRHxuVMRwI5vz5Kb5zEsWp4l7BPIAATVVnuHQZuup1V0C5VQJQnXQOcguIeIhNiCqdvQKBgG+oVhe8LOSRcZneFbQ7WIoqJchrU8zI+lAFclLyOYEq+0PSTObib0JxG4vhKCG1TxcnEOF1e7jCnL8NVC3xD3THp/IP6nz+nQYUxJyxokqFnU+VyxE7kO5LG8VhLGC9UfSYYs8B0QS39129kLsJbBwXyFyUQTUkqCOiKVvZh9FS",
        // 同步通知 url，*强烈建议加上本参数*
        'return_url' => env('APP_URL').'ali_notify',
        // 异步通知 url，*强烈建议加上本参数*
        'notify_url' => env('APP_URL').'ali_return',
        //日志
        'log' => [ // optional
            'file' => storage_path('logs/alipay.log'),
            'level' => 'debug'
        ],

    ],
];