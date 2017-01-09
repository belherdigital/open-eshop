<?php defined('SYSPATH') OR die('No direct script access.');

return array(

	'alpha'         => __(':field must contain only letters'),
	'alpha_dash'    => __(':field must contain only numbers, letters and dashes'),
	'alpha_numeric' => __(':field must contain only letters and numbers'),
	'color'         => __(':field must be a color'),
	'credit_card'   => __(':field must be a credit card number'),
	'date'          => __(':field must be a date'),
	'decimal'       => __(':field must be a decimal with :param2 places'),
	'digit'         => __(':field must be a digit'),
	'email'         => __(':field must be an email address'),
	'email_domain'  => __(':field must contain a valid email domain'),
	'equals'        => __(':field must equal :param2'),
	'exact_length'  => __(':field must be exactly :param2 characters long'),
	'in_array'      => __(':field must be one of the available options'),
	'ip'            => __(':field must be an ip address'),
	'matches'       => __(':field must be the same as :param3'),
	'min_length'    => __(':field must be at least :param2 characters long'),
	'max_length'    => __(':field must not exceed :param2 characters long'),
	'not_empty'     => __(':field must not be empty'),
	'numeric'       => __(':field must be numeric'),
	'phone'         => __(':field must be a phone number'),
	'range'         => __(':field must be within the range of :param2 to :param3'),
	'regex'         => __(':field does not match the required format'),
	'url'           => __(':field must be a url'),
	'no_banned_words' => __(':field must not contain banned words'),

);
