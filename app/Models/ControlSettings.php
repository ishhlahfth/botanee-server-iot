<?php

namespace App\Models;

use CodeIgniter\Model;

class ControlSettings extends Model
{
    protected $DBGroup              = 'default';
	protected $table                = 'control_settings';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		"settings_key",
		"settings_value",
		"last_updated",
	]; 
 
	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;
}