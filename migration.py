# -*- coding: utf-8 -*-
import pandas as pd
import psycopg2
from psycopg2.extras import execute_values
from datetime import datetime
import json
import re

# === 1. Настройки подключения к базе данных ===

db_host = '127.0.0.1'
db_name = 'rechip'
db_user = 'root'
db_password = 'password123'
db_port = '5433'

# === 2. Функция для подключения к базе данных ===

def get_db_connection():
    return psycopg2.connect(
        host=db_host,
        database=db_name,
        user=db_user,
        password=db_password,
        port=db_port
    )

# === 3. Миграция товаров ===

def migrate_products(csv_file_path):
    # Чтение CSV-файла с указанием кодировки и разделителя
    df = pd.read_csv(csv_file_path, encoding='utf-8', sep=';')

    # Исключаем товары, где 'Признак отображения в каталоге' = False
    df = df[df['Признак отображения в каталоге'] == True]

    # Переименование столбцов
    df.rename(columns={
        'ID товара': 'id',
        'Артикул товара': 'carbase_modification_id',
        'Наименование товара': 'auto_full_name',
        'Марка авто': 'brand',
        'Модель авто': 'model',
        'Поколение авто': 'generation',
        'Конфигурация авто': 'configuration',
        'Модификация авто': 'modification',
        'Показатели прироста': 'stages_increase_params',
        # 'Признак отображения в каталоге': 'show_in_catalog',  # Не импортируем это поле
        # 'Основные услуги': 'main_services',  # Будем обрабатывать отдельно
        # 'Дополнительные услуги': 'additional_services'  # Будем обрабатывать отдельно
    }, inplace=True)

    # Удаляем колонку 'Признак отображения в каталоге', так как она нам больше не нужна
    df.drop(columns=['Признак отображения в каталоге'], inplace=True)

    # Добавление полей 'created_at' и 'updated_at'
    df['created_at'] = datetime.now()
    df['updated_at'] = datetime.now()

    # Преобразование 'Показатели прироста' в JSON
    def parse_stages(text):
        stages = []
        if pd.isnull(text):
            return json.dumps(stages, ensure_ascii=False)
        stage_texts = text.strip().split('\n')
        for stage_text in stage_texts:
            stage_pattern = r'(Stage \d+) \((\d+)\): (.+)'
            stage_match = re.match(stage_pattern, stage_text.strip())
            if stage_match:
                stage_name = stage_match.group(1)
                price = stage_match.group(2)
                params_text = stage_match.group(3)

                params = []
                # Разбиваем параметры по ';', учитывая возможное отсутствие ';' в конце строки
                params_list = re.split(r';\s*(?=[А-Яа-я]{2,})', params_text.strip())
                for param_text in params_list:
                    param_pattern = r'(.+?) \(Завод: (.*?), Тюнинг: (.*?), Прирост: (.*?)\)'
                    param_match = re.match(param_pattern, param_text.strip())
                    if param_match:
                        param_name = param_match.group(1).strip()
                        factory_value = param_match.group(2).strip()
                        tuned_value = param_match.group(3).strip()
                        increase_value = param_match.group(4).strip()
                        params.append({
                            'param_name': param_name,
                            'factory_value': factory_value,
                            'tuned_value': tuned_value,
                            'increase_value': increase_value
                        })
                stages.append({
                    'stage': stage_name,
                    'price': price,
                    'params': params
                })
        return json.dumps(stages, ensure_ascii=False)

    # Применяем функцию к столбцу 'stages_increase_params'
    df['stages_increase_params'] = df['stages_increase_params'].apply(parse_stages)

    # Приведение типов и обработка данных
    df['id'] = pd.to_numeric(df['id'], errors='coerce', downcast='integer')

    # Заменяем NaN на None
    df = df.where(pd.notnull(df), None)

    # Определяем столбцы для вставки
    columns = [
        'id',
        'auto_full_name',
        'brand',
        'model',
        'generation',
        'configuration',
        'modification',
        'carbase_modification_id',
        'created_at',
        'updated_at',
        'stages_increase_params'
    ]

    data_to_insert = df[columns]

    # Подключение к базе данных
    conn = get_db_connection()
    cur = conn.cursor()

    # Вставка данных в таблицу автомобилей
    insert_query = f"""
        INSERT INTO autos ({', '.join(columns)})
        VALUES %s
        ON CONFLICT (id) DO NOTHING
    """
    records = data_to_insert.values.tolist()
    execute_values(cur, insert_query, records)
    conn.commit()
    print("Автомобили успешно импортированы.")

    # Обработка услуг для каждого автомобиля
    for index, row in df.iterrows():
        auto_id = row['id']

        # === Обработка основных услуг ===
        main_services_text = row.get('Основные услуги')
        if pd.notnull(main_services_text):
            main_services_lines = main_services_text.strip().split('\n')
            for line in main_services_lines:
                # Извлекаем ID услуги и цену
                main_service_pattern = r'(\d+)\s*-\s*(.*?)\s*\(([\d\s]+) ₽\)'
                main_service_match = re.match(main_service_pattern, line.strip())
                if main_service_match:
                    service_external_id = int(main_service_match.group(1))
                    price = int(main_service_match.group(3).replace(' ', ''))

                    # Получаем внутренний ID услуги из таблицы 'services'
                    cur.execute("SELECT id FROM services WHERE id = %s", (service_external_id,))
                    service_result = cur.fetchone()
                    if service_result:
                        service_id = service_result[0]
                        # Вставляем связь в таблицу 'auto_service'
                        cur.execute("""
                            INSERT INTO auto_service (auto_id, service_id, price, created_at, updated_at)
                            VALUES (%s, %s, %s, %s, %s)
                            """, (auto_id, service_id, price, datetime.now(), datetime.now()))

        # === Обработка дополнительных услуг ===
        additional_services_text = row.get('Дополнительные услуги')
        if pd.notnull(additional_services_text):
            additional_services_lines = additional_services_text.strip().split('\n')
            for line in additional_services_lines:
                # Извлекаем ID услуги и цену
                additional_service_pattern = r'(\d+)\s*-\s*(.*?)\s*\(([\d\s]+) ₽\)'
                additional_service_match = re.match(additional_service_pattern, line.strip())
                if additional_service_match:
                    service_external_id = int(additional_service_match.group(1))
                    price = int(additional_service_match.group(3).replace(' ', ''))

                    # Получаем внутренний ID услуги из таблицы 'additional_services'
                    cur.execute("SELECT id FROM additional_services WHERE id = %s", (service_external_id,))
                    service_result = cur.fetchone()
                    if service_result:
                        service_id = service_result[0]
                        # Вставляем связь в таблицу 'auto_additional_service'
                        cur.execute("""
                            INSERT INTO auto_additional_service (auto_id, additional_service_id, price, created_at, updated_at)
                            VALUES (%s, %s, %s, %s, %s)
                            """, (auto_id, service_id, price, datetime.now(), datetime.now()))

    # Фиксируем изменения и закрываем соединение
    conn.commit()
    cur.close()
    conn.close()
    print("Связи автомобилей с услугами успешно созданы.")

# === 4. Запуск процесса миграции ===

if __name__ == '__main__':
    csv_file_path = 'woo_base.csv'

    # Миграция товаров и создание связей с услугами
    migrate_products(csv_file_path)
