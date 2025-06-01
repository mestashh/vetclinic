@extends('layouts.app')

@section('title', 'Новости')

@section('content')
    <style>
        .news-container {
            max-width: 800px;
            margin: 2rem auto;
            background: #ffffff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            font-family: sans-serif;
            overflow: hidden;
            box-sizing: border-box;
        }

        .news-title {
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 2rem;
            color: #1f2937;
        }

        .news-toggle {
            display: block;
            margin: 0 auto 2rem;
            background-color: #10b981;
            color: white;
            padding: 0.5rem 1.5rem;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }

        .news-toggle:hover {
            background-color: #059669;
        }

        .news-form {
            display: none;
            margin-bottom: 2rem;
            width: 100%;
            box-sizing: border-box;
        }

        .news-input,
        .news-textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 1rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        .news-textarea {
            resize: vertical;
            min-height: 120px;
        }

        .news-submit {
            background-color: #2563eb;
            color: white;
            padding: 0.5rem 1.5rem;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }

        .news-submit:hover {
            background-color: #1d4ed8;
        }

        .news-item {
            margin-bottom: 2rem;
            padding: 1rem;
            background: #f9fafb;
            border-left: 4px solid #2563eb;
            border-radius: 4px;
            white-space: pre-wrap;
            overflow-wrap: break-word;
            word-break: break-word;
        }

        .news-item-title {
            font-weight: bold;
            font-size: 1.125rem;
            margin-bottom: 0.5rem;
        }
    </style>

    <div class="news-container">
        <h1 class="news-title">Новости</h1>

        @if(Auth::check() && Auth::user()->role === 'superadmin')
            <button id="toggleNewsForm" class="news-toggle">Добавить новость</button>

            <div class="news-form" id="newsForm">
                <input id="newsTitle" class="news-input" placeholder="Заголовок новости">
                <textarea id="newsText" class="news-textarea" placeholder="Текст новости..."></textarea>
                <button id="newsSubmitBtn" class="news-submit">Сохранить</button>
            </div>
        @endif

        <div id="newsRoot">
            @foreach($news as $n)
                <div class="news-item">
                    <div class="news-item-title">{{ $n->title }}</div>
                    <div>{{ $n->text }}</div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        window.currentUserRole = "{{ Auth::check() ? Auth::user()->role : '' }}";
        window.initialNews = @json($news);
    </script>
    @vite(['src/app.js'])
@endsection
