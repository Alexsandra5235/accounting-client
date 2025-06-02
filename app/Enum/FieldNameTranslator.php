<?php

namespace App\Enum;

enum FieldNameTranslator
{
    public static function translate(string $key): string
    {
        $map = [
            // Основные поля
            'id' => 'ID',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',

            // Пациент
            'patient.id' => 'ID пациента',
            'patient.name' => 'ФИО пациента',
            'patient.birth_day' => 'Дата рождения',
            'patient.gender' => 'Пол',
            'patient.medical_card' => 'Номер мед. карты',
            'patient.passport' => 'Паспорт',
            'patient.nationality' => 'Национальность',
            'patient.address' => 'Адрес проживания',
            'patient.register_place' => 'Адрес регистрации',
            'patient.snils' => 'СНИЛС',
            'patient.polis' => 'Полис',

            // Диагноз
            'patient.diagnosis.id' => 'ID диагноза',
            'patient.diagnosis.state_id' => 'Состояние пациента ID',
            'patient.diagnosis.wound_id' => 'Тип ранения ID',
            'patient.diagnosis.state.id' => 'ID состояния',
            'patient.diagnosis.state.code' => 'Код состояния',
            'patient.diagnosis.state.value' => 'Состояние',
            'patient.diagnosis.wound.id' => 'ID ранения',
            'patient.diagnosis.wound.code' => 'Код ранения',
            'patient.diagnosis.wound.value' => 'Тип ранения',

            // Приём
            'log_receipt.date_receipt' => 'Дата приёма',
            'log_receipt.time_receipt' => 'Время приёма',
            'log_receipt.datetime_alcohol' => 'Дата/время употребления алкоголя',
            'log_receipt.phone_agent' => 'Телефон агента',
            'log_receipt.delivered' => 'Кем доставлен',
            'log_receipt.fact_alcohol' => 'Наличие алкоголя',
            'log_receipt.result_research' => 'Результат исследования',
            'log_receipt.section_medical' => 'Медицинский участок',

            // Выписка
            'log_discharge.datetime_discharge' => 'Дата/время выписки',
            'log_discharge.datetime_inform' => 'Дата/время информирования',
            'log_discharge.outcome' => 'Исход',
            'log_discharge.section_transferred' => 'Переведен в отделение',

            // Отказ
            'log_reject.reason_refusal' => 'Причина отказа',
            'log_reject.name_medical_worker' => 'Медицинский работник',
            'log_reject.add_info' => 'Доп. информация',
        ];

        return $map[$key] ?? $key;
    }
}
