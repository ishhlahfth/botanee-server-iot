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
        $sensor1data = [
            'settings_value' => $rawRequest['sensor_1'],
            'last_updated' => date('Y-m-d H:i:s'),
        ];
        $s1upd = $controlSettings->update(6, $sensor1data);
        $sensor2data = [
            'settings_value' => $rawRequest['sensor_2'],
            'last_updated' => date('Y-m-d H:i:s'),
        ];
        $s2upd = $controlSettings->update(7, $sensor2data);
        
        if($s1upd && $s2upd){
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
            $settingsData = $controlSettings->findAll();
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
        $controlSettings = new ControlSettings();
        $sensor1Data = $controlSettings->find(6);
        $sensor2Data = $controlSettings->find(7);

        $s1 = ($sensor1Data['settings_value'] > 0) ? 'WET' : 'DRY';
        $s2 = ($sensor2Data['settings_value'] > 0) ? 'WET' : 'DRY';
        $responseData = [
            'sensor_1' => $s1,
            'sensor_2' => $s2,
        ];
        if($responseData){
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
