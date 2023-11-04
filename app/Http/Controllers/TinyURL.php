<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TinyURL extends Controller
{
    public function run($key)
    {
        if ($urlData = Cache::get("url_$key")) {
            return redirect($urlData->rowUrl);
        }
        abort(404);
    }

    public function setUrl(Request $request)
    {
        $rules = [
            'rowUrl' => 'required|url',
            'ageing' => 'required|integer|between:1,4320',
            'limited' => 'nullable|integer|between:0,64',
        ];

        $messages = [
            'rowUrl.required' => 'rowUrl 参数是必须的',
            'rowUrl.url' => 'rowUrl 必须是一个有效的 URL',

            'ageing.required' => 'ageing 参数是必须的',
            'ageing.integer' => 'ageing 必须是一个整数',
            'ageing.between' => 'ageing 须为[0,64]',

            'limited.integer' => 'ageing 必须是一个整数',
            'limited.between' => 'limited 须为[0,64]',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'error' => '参数校验失败',
                'message' => $validator->errors()->all(),
            ], 400);
        }
        $params = $validator->validated();
        $params['limited'] = $params['limited'] ?? 0;
        do {
            $key = strtolower(Str::random(4));
        } while (Cache::has("url_$key"));

        $urlData = [
            'key' => $key,
            'url' => $request->root() . "/$key",
            'rowUrl' => $params['rowUrl'],
            'ageing' => $params['ageing'],
            'limited' => $params['limited'],
            'timestamp' => time()
        ];
        Cache::put("url_$key", (object)$urlData,now()->addMinutes($params['ageing']));
        return [
            'result' => $urlData
        ];
    }

    public function getUrl(Request $request)
    {
        $rules = [
            'key' => 'required|regex:/^[a-zA-Z0-9]{4}$/',
        ];

        $messages = [
            'key.required' => 'rowUrl 参数是必须的',
            'key.regex' => 'rowUrl 参数值非法',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'error' => '参数校验失败',
                'message' => $validator->errors()->all(),
            ], 400);
        }
        $params = $validator->validated();
        $params['key'] = strtolower($params['key']);

        return [
            'result' => Cache::get("url_{$params['key']}")
        ];
    }
}
