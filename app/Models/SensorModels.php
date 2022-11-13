<?php

namespace App\Models;

use CodeIgniter\Model;

class SensorModels extends Model
{
    protected $DBGroup              = 'default';
	protected $table                = 'sensor_log';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		"s1_status",
		"s2_status",
		"timestamp",
	]; 
 
	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;
}