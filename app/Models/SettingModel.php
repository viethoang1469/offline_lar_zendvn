<?php

namespace App\Models;

use App\Models\AdminModel;
use Illuminate\Support\Facades\DB;

class SettingModel extends AdminModel
{
    public function __construct()
    {
        $this->table = 'setting';
        $this->fieldSearchAccepted = ['key_value'];
        $this->crudNotAccepted = ['_token'];
        $this->timestamps = false;
    }

    public function saveItem($params = null, $options = null){
        $result = null;

        if ($options['task'] == 'general') {
            $value = json_encode($this->prepareParams($params), JSON_UNESCAPED_UNICODE);
            $keyValue = 'setting-general';
            $this->where('key_value', $keyValue)->update(['value' => $value]);
        }

        if ($options['task'] == 'email-account') {
            $value = json_encode($this->prepareParams($params), JSON_UNESCAPED_UNICODE);
            $keyValue = 'setting-email';
            $this->where('key_value', $keyValue)->update(['value' => $value]);
        }

        if ($options['task'] == 'email-bcc') {
            $keyValue = 'setting-bcc';
            $this->where('key_value', $keyValue)->update(['value' => $params['bcc']]);
        }

        if ($options['task'] == 'social') {
            $value = json_encode($this->prepareParams($params), JSON_UNESCAPED_UNICODE);
            $keyValue = 'setting-social';
            $this->where('key_value', $keyValue)->update(['value' => $value]);
        }
    }

    public function getItem($params = null, $options = null)
    {
        $result = null;

        if ($params['type'] == 'general') {
            $item = $this->select('value')->where('key_value', 'setting-general')->firstOrFail()->toArray();
            $result = json_decode($item['value'], true);
        }

        if ($params['type'] == 'email') {
            $item = $this->select('value')->where('key_value', 'setting-email')->firstOrFail()->toArray();
            $result = json_decode($item['value'], true);
            $result['bcc'] = $this->select('value')->where('key_value', 'setting-bcc')->first()->value;
        }

        if ($params['type'] == 'social') {
            $item = $this->select('value')->where('key_value', 'setting-social')->firstOrFail()->toArray();
            $result = json_decode($item['value'], true);
        }

        return $result;
    }
}
