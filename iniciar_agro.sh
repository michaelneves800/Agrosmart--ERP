#!/bin/bash

echo "🚀 Iniciando o ecossistema AgroSmart..."

# 1. Iniciar o Cérebro Python em segundo plano
echo "🧠 Ligando Inteligência Artificial (Python)..."
cd ai_service
source venv/bin/activate
python3 ai_service.py &
cd ..

# 2. Iniciar o Vite (Frontend) em segundo plano
echo "🎨 Compilando interface (Vite)..."
npm run dev &

# 3. Iniciar o Servidor Laravel (PHP)
echo "🚜 Abrindo servidor principal na porta 8003..."
php artisan serve --port=8003



para iniciar   digitar no terminal.  >> ./iniciar_agro.sh