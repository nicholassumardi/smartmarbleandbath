<?php

return [
	'mode'             => 'utf-8',
	'format'           => 'A4',
	'author'           => '',
	'subject'          => '',
	'keywords'         => '',
	'creator'          => 'TJS',
	'display_mode'     => 'fullpage',
	'tempDir'          => base_path('../temp/'),
	'pdf_a'            => false,
	'pdf_a_auto'       => false,
	'icc_profile_path' => '',
	'font_path' => base_path('resources/fonts/'),
	'font_data' => [
		'lato' => [
			'R'  => 'Lato-Regular.ttf',    // regular font
			'B'  => 'Lato-Bold.ttf',       // optional: bold font
			'I'  => 'Lato-Italic.ttf',     // optional: italic font
			'BI' => 'Lato-BoldItalic.ttf' // optional: bold-italic font
		]
	]
];
