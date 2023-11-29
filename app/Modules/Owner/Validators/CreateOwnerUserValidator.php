<?php

namespace App\Modules\Owner\Validators;

use App\Modules\Owner\Repositories\OwnerFieldRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


class CreateOwnerUserValidator extends FormRequest
{

    private $fields;

    public function authorize()
    {
        return Auth::user()->ability('superAdmin', ['owner-edit']);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->fields = (new OwnerFieldRepository())->allToArray();
        $rules = [];
        foreach ($this->fields as $field) {
            $r = [];
            if (in_array('required', $field['rules'])) {
                $r[] = 'required';
            } else {
                $r[] = 'nullable';
            }
            if (in_array('isEmail', $field['rules'])) {
                $r[] = 'email';
            }
            if ($field['type'] == 'date') {
                $r[] = 'date';
            } else {
                if ($field['type'] == 'boolean') {
                    $r[] = 'boolean';
                } else {
                    $r[] = 'string';
                }
            }

            $rules['owner.' . $field['name']] = $r;
        }
        $rules['steads'] = [
            'required',
            'array'
//        Rule::in(BanUserTypeRepository::getTypeKey())
        ];
        return $rules;
    }

    public function attributes()
    {
        $data = [];
        foreach ($this->fields as $item) {
            $data['owner.' . $item['name']] = $item['label'];
        }
        $data['steads'] = 'участок(и)';
        return $data;
    }


}