# -*- coding: utf-8 -*-
import pandas as pd
import psycopg2
from psycopg2.extras import execute_values

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

# === 3. Миграция данных ===

def migrate_car_identifiers(csv_file_path):
    # Чтение CSV-файла с указанием кодировки, разделителя и параметра low_memory=False для предотвращения DtypeWarning
    df = pd.read_csv(csv_file_path, encoding='utf-8', sep=',', low_memory=False)

    # Переименование столбцов для удобства
    df.rename(columns={
        'mark-id': 'mark_id',
        'model-id': 'model_id',
        'generation-id': 'generation_id',
        'complectation-id': 'carbase_modification_id'
    }, inplace=True)

    # Удаляем строки, где значения complectation-id отсутствуют
    df = df[df['carbase_modification_id'].notnull()]

    # Приведение типов и обработка данных
    df['carbase_modification_id'] = df['carbase_modification_id'].astype(str)
    df['mark_id'] = df['mark_id'].astype(str)
    df['model_id'] = df['model_id'].astype(str)
    df['generation_id'] = df['generation_id'].astype(str)

    # Отладочный вывод для проверки данных
    print("Пример данных для миграции:")
    print(df[['carbase_modification_id', 'mark_id', 'model_id', 'generation_id']].head())

    # Подключение к базе данных
    conn = get_db_connection()
    cur = conn.cursor()

    # Обновление данных в таблице автомобилей
    update_query = """
        UPDATE autos
        SET mark_id = data.mark_id,
            model_id = data.model_id,
            generation_id = data.generation_id
        FROM (VALUES %s) AS data(carbase_modification_id, mark_id, model_id, generation_id)
        WHERE autos.carbase_modification_id = data.carbase_modification_id
    """

    # Преобразование данных в список для выполнения запроса
    records = df[['carbase_modification_id', 'mark_id', 'model_id', 'generation_id']].values.tolist()

    # Отладочный вывод для проверки данных перед выполнением запроса
    print("Пример данных для обновления (первые 5 записей):")
    print(records[:5])

    # Выполнение обновления данных
    execute_values(cur, update_query, records)

    # Фиксация изменений
    conn.commit()

    # Закрытие соединения
    cur.close()
    conn.close()

    print("Данные автомобилей успешно обновлены.")

# === 4. Запуск скрипта миграции ===

if __name__ == '__main__':
    csv_file_path = 'car_data.csv'  # Укажи путь к вашему CSV-файлу

    # Запуск миграции данных
    migrate_car_identifiers(csv_file_path)
