<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\SensorModels;
use App\Models\ControlSettings;

class Api extends ResourceController
{
    
    use ResponseTrait;

    public function index()
    {
        echo '{}';
    }

    public function postSensorLog() {
        $sensorModels = new SensorModels();
        $controlSettings = new ControlSettings();
        $reqHeader = $this->request->headers();
        $rawRequest = $this->request->getRawInput();
        $sensorData = [
            's1_status' => $rawRequest['sensor_1'],
            's2_status' => $rawRequest['sensor_2'],
            'timestamp' => date('Y-m-d H:i:s'),
        ];
        if($sensorModels->insert($sensorData)){
            $settingsData = $controlSettings->findAll();
            $responseData = [
                'is_manual' => intval($settingsData[0]['settings_value']),
                'valve_1' => intval($settingsData[1]['settings_value']),
                'valve_2' => intval($settingsData[2]['settings_value']),
                'is_pump' => intval($settingsData[3]['settings_value']),
            ];
            return $this->responseBuilder(200, 'Berhasil update log', $responseData);
        }else{
            return $this->responseBuilder(400, 'Gagal update log', null);
        }
    }
    public function getSettings() {

    }
    public function updateSettings() {
        $controlSettings = new ControlSettings();
        $reqHeader = $this->request->headers();
        $rawRequest = $this->request->getRawInput();
        $settingsId = $rawRequest['id'];
        $controllerData = [
            'settings_value' => $rawRequest['value'],
            'last_updated' => date('Y-m-d H:i:s'),
        ];
        if($controlSettings->update($settingsId, $controllerData)){
            $settingsData = $controlSettings->fidAll();
            $responseData = [
                'is_manual' => intval($settingsData[0]['settings_value']),
                'valve_1' => intval($settingsData[1]['settings_value']),
                'valve_2' => intval($settingsData[2]['settings_value']),
                'is_pump' => intval($settingsData[3]['settings_value']),
            ];
            return $this->responseBuilder(200, 'Berhasil update status', $responseData);
        }else{
            return $this->responseBuilder(400, 'Gagal update status', null);
        }
    }
    public function getLatestLogs() {
        $sensorModels = new SensorModels();
        $sensorData = $sensorModels->orderBy('id', 'DESC')->first();
        $s1 = ($sensorData['s1_status'] > 0) ? 'WET' : 'DRY';
        $s2 = ($sensorData['s1_status'] > 0) ? 'WET' : 'DRY';
        $responseData = [
            'sensor_1' => $s1,
            'sensor_2' => $s2,
        ];
        if($sensorData){
            return $this->responseBuilder(200, 'Success', $responseData);
        }else{
            return $this->responseBuilder(404, 'Fail', null);
        }
    }
    public function getAllSettings() {
        $controlSettings = new ControlSettings();
        $settingsData = $controlSettings->findAll();
        $responseData = [
            'is_manual' => intval($settingsData[0]['settings_value']),
            'valve_1' => intval($settingsData[1]['settings_value']),
            'valve_2' => intval($settingsData[2]['settings_value']),
            'is_pump' => intval($settingsData[3]['settings_value']),
        ];
        if($settingsData){
            return $this->responseBuilder(200, 'Success', $responseData);
        }else{
            return $this->responseBuilder(404, 'Fail', null);
        }
    }

    function responseBuilder($code = null, $message = null, $data = null) {
        $response = [
            'status' => $code,
            'messages' => $message,
            'data' => $data,
        ];
        return $this->respond($response);
    }
}
