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

    }
    public function getLatestLog() {

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
