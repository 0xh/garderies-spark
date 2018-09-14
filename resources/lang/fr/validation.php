<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => "Le champ :attribute doit être accepté.",
    'active_url'           => "Le champ :attribute n'est pas une URL valide.",
    'after'                => "Le champ :attribute doit être une date après :date.",
    'after_or_equal'       => "Le champ :attribute doit être une date égale ou après :date.",
    'alpha'                => "Le champ :attribute ne peut contenir que des lettres.",
    'alpha_dash'           => "Le champ :attribute ne peut contenir que des lettres, nombres ou tirets.",
    'alpha_num'            => "Le champ :attribute ne peut contenir que des lettres et nombres.",
    'array'                => "Le champ :attribute doit être un tableau.",
    'before'               => "Le champ :attribute doit être une date avant :date.",
    'before_or_equal'      => "Le champ :attribute doir être une date avant ou égale à :date.",
    'between'              => [
        'numeric' => "Le champ :attribute doit être entre :min et :max.",
        'file'    => "Le champ :attribute doit être entre :min et :max kilobytes.",
        'string'  => "Le champ :attribute doit être entre :min et :max caractères.",
        'array'   => "Le champ :attribute doit avoir entre :min et :max objets.",
    ],
    'boolean'              => "Le champ :attribute doit être vrai ou faux.",
    'confirmed'            => "Le champ :attribute confirmation ne correspond pas.",
    'date'                 => "Le champ :attribute n'est pas une date valide.",
    'date_format'          => "Le champ :attribute ne correspond pas au format :format.",
    'different'            => "Le champ :attribute et :other doivent être différents.",
    'digits'               => "Le champ :attribute doit avoir :digits nombres.",
    'digits_between'       => "Le champ :attribute doit avoir entre :min et :max nombres.",
    'dimensions'           => "Le champ :attribute a des dimensions d'image invalides.",
    'distinct'             => "Le champ :attribute a une valeur dupliquée.",
    'email'                => "Le champ :attribute doit être une adresse e-mail valide.",
    'exists'               => "Le champ :attribute sélectionné est invalide.",
    'file'                 => "Le champ :attribute doit être un fichier.",
    'filled'               => "Le champ :attribute doit avoir une valeur.",
    'image'                => "Le champ :attribute doit être une image.",
    'in'                   => "Le champ :attribute sélectionné est invalide.",
    'in_array'             => "Le champ :attribute n'existe pas dans :other.",
    'integer'              => "Le champ :attribute doit être un entier.",
    'ip'                   => "Le champ :attribute doit être une adresse IP valide.",
    'ipv4'                 => "Le champ :attribute doit être une adresse IPv4 valide.",
    'ipv6'                 => "Le champ :attribute doit être une adresse IPv6 valide.",
    'json'                 => "Le champ :attribute doit être une chaîne JSON valide.",
    'max'                  => [
        'numeric' => "Le champ :attribute ne peut pas être plus grand que :max.",
        'file'    => "Le champ :attribute ne peut pas être plus grand que :max kilobytes.",
        'string'  => "Le champ :attribute ne peut pas avoir plus que :max caractères.",
        'array'   => "Le champ :attribute ne peut pas avoir plus grand que :max objets.",
    ],
    'mimes'                => "Le champ :attribute doit être un fichier de type : :values.",
    'mimetypes'            => "Le champ :attribute doit être un fichier de type : :values.",
    'min'                  => [
        'numeric' => "Le champ :attribute doit être au moins :min.",
        'file'    => "Le champ :attribute doit être au moins de :min kilobytes.",
        'string'  => "Le champ :attribute doit avoir au moins :min caractères.",
        'array'   => "Le champ :attribute doit avoir au moins :min objets.",
    ],
    'not_in'               => "Le champ :attribute sélectionné est invalide.",
    'not_regex'            => "Le format du champ :attribute est invalide.",
    'numeric'              => "Le champ :attribute doit être un nombre.",
    'present'              => "Le champ :attribute doit être présent.",
    'regex'                => "Le format du champ :attribute est invalide.",
    'required'             => "Le champ :attribute est requis.",
    'required_if'          => "Le champ :attribute est requis quand :other est :value.",
    'required_unless'      => "Le champ :attribute est requis à moins que :other soit dans :values.",
    'required_with'        => "Le champ :attribute est requis quand :values est présent.",
    'required_with_all'    => "Le champ :attribute est requis quand :values est présent.",
    'required_without'     => "Le champ :attribute est requis quand :values n'est pas présent.",
    'required_without_all' => "Le champ :attribute est requis quand aucun des :values n'est présent.",
    'same'                 => "Le champ :attribute et :other doivent correspondre.",
    'size'                 => [
        'numeric' => "Le champ :attribute doit être :size.",
        'file'    => "Le champ :attribute doit peser au moins :size kilobytes.",
        'string'  => "Le champ :attribute doit avoir au moins :size caractères.",
        'array'   => "Le champ :attribute doit contenir :size objets.",
    ],
    'string'               => "Le champ :attribute doit être une chaîne de caractères.",
    'timezone'             => "Le champ :attribute doit être une zone valide.",
    'unique'               => "Le champ :attribute a déjà été pris.",
    'uploaded'             => "Le champ :attribute n'a pas pu être téléversé.",
    'url'                  => "Le format du champ :attribute est invalide.",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'name'      => 'Nom',
        'email'     => 'E-mail',
        'password'  => 'Mot de passe',
        'file'      => 'Fichier',
        'terms'     => 'Condition générales'
    ],

];
