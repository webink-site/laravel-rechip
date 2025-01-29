<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CatalogSearchRequest extends FormRequest
{

    public function rules()
    {
        return [
            // Общий обязательный параметр
            'service' => 'required|string|exists:services,slug',

            // Вариант 1: Полный фильтр
            'brand' => 'nullable|string|exists:brands,slug',
            'model' => 'nullable|string|exists:models,slug',
            'configuration' => 'nullable|string',
            'engine' => 'nullable|string|exists:engine,slug',

            // Вариант 2: Только service и catalog
            'catalog' => [
                'nullable',
                'string',
                'exists:catalog,slug',
                function ($attribute, $value, $fail) {
                    if ($this->input('catalog') &&
                        ($this->input('brand') || $this->input('model') || $this->input('configuration') || $this->input('engine'))) {
                        $fail('Параметр "catalog" не может использоваться вместе с "brand", "model", "configuration", "engine".');
                    }
                },
            ],

            // Дополнительные правила для обеспечения полной фильтрации, если catalog не указан
            'brand' => [
                'nullable',
                'string',
                'exists:brands,slug',
                function ($attribute, $value, $fail) {
                    if ($this->missing('catalog') &&
                        (!$this->input('brand') && !$this->input('model') && !$this->input('configuration') && !$this->input('engine'))) {
                        $fail('При отсутствии "catalog" параметр "service" должен использоваться вместе с "brand", "model", "configuration", "engine".');
                    }
                },
            ],
            'model' => [
                'nullable',
                'string',
                'exists:models,slug',
                function ($attribute, $value, $fail) {
                    if ($this->missing('catalog') &&
                        (!$this->input('brand') && !$this->input('model') && !$this->input('configuration') && !$this->input('engine'))) {
                        $fail('При отсутствии "catalog" параметр "service" должен использоваться вместе с "brand", "model", "configuration", "engine".');
                    }
                },
            ],
            'configuration' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if ($this->missing('catalog') &&
                        (!$this->input('brand') && !$this->input('model') && !$this->input('configuration') && !$this->input('engine'))) {
                        $fail('При отсутствии "catalog" параметр "service" должен использоваться вместе с "brand", "model", "configuration", "engine".');
                    }
                },
            ],
            'engine' => [
                'nullable',
                'string',
                'exists:engine,slug',
                function ($attribute, $value, $fail) {
                    if ($this->missing('catalog') &&
                        (!$this->input('brand') && !$this->input('model') && !$this->input('configuration') && !$this->input('engine'))) {
                        $fail('При отсутствии "catalog" параметр "service" должен использоваться вместе с "brand", "model", "configuration", "engine".');
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'service.required' => 'Параметр "service" обязателен.',
            'service.exists' => 'Услуга не найдена.',
            'brand.exists' => 'Бренд не найден.',
            'model.exists' => 'Модель не найдена.',
            'configuration.exists' => 'Конфигурация не найдена.',
            'engine.exists' => 'Двигатель не найден.',
            'catalog.exists' => 'Каталог не найден.',
            'catalog.string' => 'Параметр "catalog" должен быть строкой.',
        ];
    }
}