<?php

return array(
    
    'OBULLO:VALIDATOR:REQUIRED'        => 'The %s field is required.',
    'OBULLO:VALIDATOR:CONTAINS'        => 'The %s field contains unaccepted value.',
    'OBULLO:VALIDATOR:EMAIL'           => "The %s field must contain a valid email address.",
    'OBULLO:VALIDATOR:EMAILS'          => "The %s field must contain all valid email addresses.",
    'OBULLO:VALIDATOR:VALIDURL'        => "The %s field must contain a valid URL.",
    'OBULLO:VALIDATOR:VALIDIP'         => "The %s field must contain a valid IP.",
    'OBULLO:VALIDATOR:MIN'             => "The %s field must be at least %s characters in length.",
    'OBULLO:VALIDATOR:MAX'             => "The %s field can not exceed %s characters in length.",
    'OBULLO:VALIDATOR:EXACT'           => "The %s field must be exactly %s characters in length.",
    'OBULLO:VALIDATOR:ALPHA'           => "The %s field may only contain alphabetical characters.",
    'OBULLO:VALIDATOR:ALNUM'           => "The %s field may only contain alpha-numeric characters.",
    'OBULLO:VALIDATOR:ALPHADASH'       => "The %s field may only contain alphabetic characters, underscores, and dashes.",
    'OBULLO:VALIDATOR:ALNUMDASH'       => "The %s field may only contain alpha-numeric characters, underscores, and dashes.",
    'OBULLO:VALIDATOR:ISNUMERIC'       => "The %s field must contain only numeric characters.",
    'OBULLO:VALIDATOR:MATCHES'         => "The %s field does not match the %s field.",
    'OBULLO:VALIDATOR:NOSPACE'         => "The %s field can not contain space characters.",
    'OBULLO:VALIDATOR:DATE'            => "The %s field must contain a valid date.",
    'OBULLO:VALIDATOR:ISDECIMAL'       => "The %s field must contain only decimal characters.",
    'OBULLO:VALIDATOR:ISJSON'          => "The %s field must contain a valid json data.",
    'OBULLO:VALIDATOR:CSRF:INVALID'    => "The form submitted did not originate from the expected site.",
    'OBULLO:VALIDATOR:CSRF:REQUIRED'   => "The %s field is required.",

    'OBULLO:VALIDATOR:CAPTCHA:LABEL' => "Captcha",
    'OBULLO:VALIDATOR:CAPTCHA:NOT_FOUND' => "The captcha failure code not found.",
    'OBULLO:VALIDATOR:CAPTCHA:EXPIRED' => "The captcha code has been expired.",
    'OBULLO:VALIDATOR:CAPTCHA:INVALID' => "Invalid captcha code.",
    'OBULLO:VALIDATOR:CAPTCHA:SUCCESS' => "Captcha code verified.",
    'OBULLO:VALIDATOR:CAPTCHA:VALIDATION' => "The %s field validation is wrong.",
    'OBULLO:VALIDATOR:CAPTCHA:REFRESH_BUTTON_LABEL' => "Refresh Captcha",
    
    'OBULLO:VALIDATOR:RECAPTCHA:MISSING_INPUT_SECRET' => "The secret parameter is missing.",
    'OBULLO:VALIDATOR:RECAPTCHA:INVALID_INPUT_SECRET' => "The secret parameter is invalid or malformed.",
    'OBULLO:VALIDATOR:RECAPTCHA:MISSING_INPUT_RESPONSE' => "The response parameter is missing.",
    'OBULLO:VALIDATOR:RECAPTCHA:INVALID_INPUT_RESPONSE' => "The response parameter is invalid or malformed.",

    'OBULLO:VALIDATOR:IBAN:NOTSUPPORTED' => 'Unknown country within the IBAN.',
    'OBULLO:VALIDATOR:IBAN:SEPANOTSUPPORTED' => 'Countries outside the Single Euro Payments Area (SEPA) are not supported.',
    'OBULLO:VALIDATOR:IBAN:FALSEFORMAT' => 'The input has a false IBAN format.',
    'OBULLO:VALIDATOR:IBAN:CHECKFAILED' => 'The input has failed the IBAN check.',
);