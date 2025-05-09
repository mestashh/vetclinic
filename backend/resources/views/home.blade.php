@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <style>
        .hero {
            max-width: 1000px;
            margin: 3rem auto;
            background: #ffffff;
            padding: 3rem 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .hero h1 {
            font-size: 2rem;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.125rem;
            color: #4b5563;
            margin-bottom: 2rem;
        }

        .highlight {
            color: #10b981;
            font-weight: 600;
        }

        .feature-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2rem;
        }

        .feature {
            flex: 1 1 250px;
            background-color: #f9fafb;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
            text-align: left;
        }

        .feature h3 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2563eb;
            margin-bottom: 0.5rem;
        }

        .feature p {
            font-size: 0.95rem;
            color: #374151;
        }

        @media (max-width: 768px) {
            .feature-list {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>

    <div class="hero">
        <h1>Добро пожаловать в <span class="highlight">Ветеринарную клинику!</span></h1>
        <p>Мы заботимся о здоровье ваших питомцев с любовью, вниманием и профессионализмом.</p>

        <div class="feature-list">
            <div class="feature">
                <h3>🐾 Учет пациентов</h3>
                <p>Полная информация о каждом питомце — от клички и породы до возраста и медицинской истории.</p>
            </div>
            <div class="feature">
                <h3>🩺 Запись на приём</h3>
                <p>Простая система записи к ветеринарам. Выберите удобную дату и время — всё онлайн.</p>
            </div>
            <div class="feature">
                <h3>💊 Услуги</h3>
                <p>Список доступных процедур и диагностик с описанием и ценой — всё для вашего удобства.</p>
            </div>
        </div>
    </div>
@endsection
