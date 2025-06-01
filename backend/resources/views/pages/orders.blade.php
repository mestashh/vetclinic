@extends('layouts.app')

@section('title', 'Заявка на пополнение')

@section('content')
    <style>
        .orders-wrapper {
            max-width: 900px;
            margin: 2rem auto;
            padding: 2rem;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            font-family: sans-serif;
        }

        label {
            font-weight: bold;
        }

        select, input, textarea {
            width: 100%;
            padding: 0.5rem;
            margin-top: 0.3rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .submit-btn {
            background: #10b981;
            color: white;
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        .order-card {
            background: #f9fafb;
            padding: 1rem;
            margin-top: 1rem;
            border-radius: 6px;
            border: 1px solid #eee;
        }

        .order-card p {
            margin: 0.3rem 0;
        }
    </style>

    <div class="orders-wrapper">
        <h1>Заявка на пополнение</h1>

        <div id="orderForm">
            <label>Услуга</label>
            <select id="serviceSelect">
                <option value="">— выберите услугу —</option>
            </select>

            <label>Вариант</label>
            <select id="itemSelect" disabled>
                <option value="">— выберите вариант —</option>
            </select>

            <label>Количество</label>
            <input type="number" id="qtyInput" placeholder="Введите количество">

            <label>Цена</label>
            <input type="number" id="priceInput" placeholder="Введите цену вручную">

            <label>Комментарий</label>
            <textarea id="commentInput" rows="2" placeholder="Дополнительная информация (необязательно)"></textarea>

            <p><strong>Цена заявки:</strong> <span id="totalPrice">0₽</span></p>

            <button class="submit-btn" id="submitBtn">Сохранить заявку</button>
        </div>

        <div id="ordersList">
            <h2>Ранее оформленные заявки</h2>
            {{-- вставка через JS --}}
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        window.initialServices = @json($services);
        window.initialOrders = @json($orders);
    </script>
    @vite(['src/app.js'])
@endsection
