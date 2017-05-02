<?php

namespace App\Validation\Rules;

use App\Models\Jobseeker;
use Respect\Validation\Rules\AbstractRule;

class EmailAvailable extends AbstractRule
{

	public function validate($input)
	{
		return Jobseeker::where('email', $input)->count() === 0;
	}
}